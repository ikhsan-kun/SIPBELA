<?php

namespace App\Http\Controllers\Perpustakaan\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusPeminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = PerpusPeminjaman::with(['user', 'buku', 'pengembalian']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%"))
                  ->orWhereHas('buku', fn($b) => $b->where('judul', 'like', "%{$s}%"));
            });
        }

        // Filter terlambat
        if ($request->filled('terlambat') && $request->terlambat == '1') {
            $query->where('status', 'dipinjam')
                  ->where('batas_kembali', '<', now()->toDateString());
        }

        $peminjamans = $query->latest()->paginate(10)->withQueryString();

        return view('perpustakaan.admin.peminjaman.index', compact('peminjamans'));
    }

    public function perpanjang($id)
    {
        $peminjaman = PerpusPeminjaman::with('buku')->findOrFail($id);

        if ($peminjaman->status !== 'menunggu_perpanjangan') {
            return back()->with('error', 'Status peminjaman bukan menunggu perpanjangan.');
        }

        try {
            // Tambahkan 7 hari ke batas kembali
            $batasBaru = \Carbon\Carbon::parse($peminjaman->batas_kembali)->addDays(7)->toDateString();

            $peminjaman->update([
                'status' => 'dipinjam',
                'batas_kembali' => $batasBaru,
                'jumlah_perpanjangan' => $peminjaman->jumlah_perpanjangan + 1,
            ]);

            return back()->with('success', "Peminjaman buku \"{$peminjaman->buku->judul}\" atas nama \"{$peminjaman->user->name}\" berhasil dikonfirmasi dan diperpanjang hingga " . \Carbon\Carbon::parse($batasBaru)->format('d M Y') . ".");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengkonfirmasi perpanjangan peminjaman: ' . $e->getMessage());
        }
    }
}
