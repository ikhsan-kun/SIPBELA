<?php

namespace App\Http\Controllers\Perpustakaan\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusPengembalian;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = PerpusPengembalian::with(['peminjaman.user', 'peminjaman.buku']);

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_kembali', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_kembali', '<=', $request->sampai);
        }

        $pengembalians = $query->latest()->paginate(15)->withQueryString();

        // Total denda dalam periode
        $totalDenda = $query->sum('denda');

        // Statistik ringkas
        $totalPeminjaman   = PerpusPeminjaman::count();
        $totalDikembalikan = PerpusPeminjaman::where('status', 'dikembalikan')->count();
        $totalAktif        = PerpusPeminjaman::whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count();
        $totalTerlambat    = PerpusPeminjaman::whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->where('batas_kembali', '<', now()->toDateString())
            ->count();

        return view('perpustakaan.admin.laporan.index', compact(
            'pengembalians',
            'totalDenda',
            'totalPeminjaman',
            'totalDikembalikan',
            'totalAktif',
            'totalTerlambat'
        ));
    }

    public function export(Request $request)
    {
        $query = PerpusPeminjaman::with(['user', 'buku', 'pengembalian']);

        // Samakan filter dengan index (disini index pakai pengembalian, tapi user minta laporan peminjaman)
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }

        $data = $query->orderBy('tanggal_pinjam', 'asc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('perpustakaan.admin.laporan.pdf', compact('data'));
        // Set paper size to A4 landscape because the table is wide
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('Laporan_Perpustakaan_' . date('Y-m-d') . '.pdf');
    }
}
