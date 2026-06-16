<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
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
        });

        $jumlahDikembalikan = $peminjaman->jumlah ?? 1;

        return back()->with('success',
            "Pengembalian {$jumlahDikembalikan} unit \"{$peminjaman->barang->nama_barang}\" atas nama {$peminjaman->user->name} berhasil dikonfirmasi. Stok bertambah {$jumlahDikembalikan}."
        );
    }
}
