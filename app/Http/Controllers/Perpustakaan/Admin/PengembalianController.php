<?php

namespace App\Http\Controllers\Perpustakaan\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusPengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        // Tampilkan peminjaman yang masih berstatus 'dipinjam' atau 'menunggu_konfirmasi'
        $query = PerpusPeminjaman::with(['user', 'buku', 'pengembalian'])
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%"))
                  ->orWhereHas('buku', fn($b) => $b->where('judul', 'like', "%{$s}%"));
            });
        }

        $peminjamans = $query->latest()->paginate(10)->withQueryString();

        return view('perpustakaan.admin.pengembalian.index', compact('peminjamans'));
    }

    /**
     * LOGIKA KRITIS: Proses Pengembalian + Hitung Denda
     * Denda: Rp 1.000 per hari keterlambatan
     */
    public function proses(Request $request, $id)
    {
        $peminjaman = PerpusPeminjaman::with('buku')->findOrFail($id);

        if (!in_array($peminjaman->status, ['dipinjam', 'menunggu_konfirmasi'])) {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan sebelumnya.');
        }

        // Karena input tanggal_kembali dihapus di UI, kita gunakan hari ini
        $tanggalKembali = Carbon::today()->startOfDay();
        $batasKembali   = $peminjaman->batas_kembali->startOfDay();

        // Hitung keterlambatan
        $hariTerlambat = 0;
        $denda         = 0;

        if ($tanggalKembali->gt($batasKembali)) {
            $hariTerlambat = (int) abs($tanggalKembali->diffInDays($batasKembali));
            $denda         = $hariTerlambat * 1000;
        }

        DB::transaction(function () use ($peminjaman, $tanggalKembali, $hariTerlambat, $denda) {
            // 1. Simpan data pengembalian
            PerpusPengembalian::create([
                'peminjaman_id'   => $peminjaman->id,
                'tanggal_kembali' => $tanggalKembali->toDateString(),
                'hari_terlambat'  => $hariTerlambat,
                'denda'           => $denda,
            ]);

            // 2. Update status peminjaman
            $peminjaman->update(['status' => 'dikembalikan']);

            // 3. Tambah stok buku kembali sesuai jumlah
            $peminjaman->buku->increment('stok', $peminjaman->jumlah ?? 1);
        });

        $msg = "Buku \"{$peminjaman->buku->judul}\" berhasil dikonfirmasi pengembaliannya.";
        if ($denda > 0) {
            $msg .= " Terlambat {$hariTerlambat} hari. Denda: Rp " . number_format($denda, 0, ',', '.');
        }

        return back()->with('success', $msg);
    }
}
