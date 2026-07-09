<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BengkelNotification;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('barang', fn($q) => $q->where('nama_barang', 'like', "%{$search}%"));
        }

        $peminjamans = $query->latest()->paginate(10)->withQueryString();

        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function prosesKembali($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        // Pastikan statusnya masih aktif (dipinjam atau menunggu_konfirmasi)
        if (!in_array($peminjaman->status, ['dipinjam', 'menunggu_konfirmasi'])) {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($peminjaman) {
            // 1. Update status dan tanggal kembali
            $peminjaman->update([
                'status'          => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
            ]);

            // 2. Kembalikan stok barang sejumlah yang dipinjam
            $peminjaman->barang->increment('stok', $peminjaman->jumlah ?? 1);

            // 3. Tambahkan cycle pemakaian (Predictive Maintenance)
            $barang = $peminjaman->barang;
            $barang->jumlah_dipakai += ($peminjaman->jumlah ?? 1);
            
            // Otomatis ubah kondisi ke 'diperbaiki' jika sudah mencapai batas
            if ($barang->batas_pemakaian > 0 && $barang->jumlah_dipakai >= $barang->batas_pemakaian) {
                $barang->kondisi = 'diperbaiki';
            }
            
            $barang->save();
        });

        $jumlahDikembalikan = $peminjaman->jumlah ?? 1;

        // Notifikasi ke Siswa: pengembalian dikonfirmasi
        BengkelNotification::siswaReturnConfirmed(
            $peminjaman->user_id,
            $peminjaman->barang->nama_barang,
            $jumlahDikembalikan
        );

        return back()->with('success',
            "Pengembalian {$jumlahDikembalikan} unit \"{$peminjaman->barang->nama_barang}\" atas nama {$peminjaman->user->name} berhasil dikonfirmasi. Stok bertambah {$jumlahDikembalikan}."
        );
    }

    public function setujuiPinjam($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if ($peminjaman->status !== 'menunggu_persetujuan') {
            return back()->with('error', 'Status peminjaman tidak valid untuk disetujui.');
        }

        // Cek stok apakah cukup
        if ($peminjaman->barang->stok < $peminjaman->jumlah) {
            return back()->with('error', 'Gagal menyetujui: Stok barang saat ini tidak mencukupi.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update(['status' => 'dipinjam']);
            $peminjaman->barang->decrement('stok', $peminjaman->jumlah);
        });

        // Notifikasi ke Siswa: peminjaman disetujui
        BengkelNotification::siswaRequestApproved(
            $peminjaman->user_id,
            $peminjaman->barang->nama_barang,
            $peminjaman->jumlah
        );

        return back()->with('success', "Peminjaman {$peminjaman->jumlah} unit \"{$peminjaman->barang->nama_barang}\" atas nama {$peminjaman->user->name} berhasil disetujui. Stok telah dikurangi.");
    }

    public function tolakPinjam($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'menunggu_persetujuan') {
            return back()->with('error', 'Status peminjaman tidak valid untuk ditolak.');
        }

        $peminjaman->load('barang', 'user');
        $peminjaman->update(['status' => 'ditolak']);

        // Notifikasi ke Siswa: peminjaman ditolak
        BengkelNotification::siswaRequestRejected(
            $peminjaman->user_id,
            $peminjaman->barang->nama_barang
        );

        return back()->with('success', 'Pengajuan peminjaman berhasil ditolak.');
    }
}
