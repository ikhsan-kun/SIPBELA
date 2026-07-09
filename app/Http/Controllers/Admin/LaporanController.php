<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);

        // Filter berdasarkan tanggal pinjam
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter siswa
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')->paginate(15)->withQueryString();

        // Summary untuk periode yang difilter
        $summary = [
            'total'        => $query->count(),
            'dipinjam'     => (clone $query)->where('status', 'dipinjam')->count(),
            'dikembalikan' => (clone $query)->where('status', 'dikembalikan')->count(),
        ];

        // Data siswa untuk dropdown filter
        $siswas = \App\Models\User::where('role', 'siswa')->orderBy('name')->get();

        return view('admin.laporan.index', compact('peminjamans', 'summary', 'siswas'));
    }

    public function export(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);

        // Samakan filter dengan index
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $data = $query->orderBy('tanggal_pinjam', 'asc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', compact('data'));
        return $pdf->download('Laporan_Peminjaman_Bengkel_' . date('Y-m-d') . '.pdf');
    }
}
