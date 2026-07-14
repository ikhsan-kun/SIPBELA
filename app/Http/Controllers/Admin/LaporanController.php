<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
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

        // Data siswa untuk dropdown filter — hanya jurusan TKR
        $siswas = User::where('role', 'siswa')
            ->where('jurusan', 'like', '%Teknik Kendaraan Ringan%')
            ->orderBy('name')
            ->get();

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

    /**
     * API endpoint untuk data statistik sidebar.
     * Mendukung filter: period (7d, 30d, custom), user_id
     */
    public function statistik(Request $request)
    {
        $period   = $request->get('period', '30d');
        $userId   = $request->get('user_id');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        // ── Tentukan rentang tanggal ───────────────────────────────────────
        $now = now();
        if ($period === '7d') {
            $from = $now->copy()->subDays(6)->startOfDay();
            $to   = $now->copy()->endOfDay();
        } elseif ($period === '30d') {
            $from = $now->copy()->subDays(29)->startOfDay();
            $to   = $now->copy()->endOfDay();
        } elseif ($period === 'custom' && $dateFrom && $dateTo) {
            $from = \Carbon\Carbon::parse($dateFrom)->startOfDay();
            $to   = \Carbon\Carbon::parse($dateTo)->endOfDay();
        } else {
            $from = $now->copy()->subDays(29)->startOfDay();
            $to   = $now->copy()->endOfDay();
        }

        // ── Base query ────────────────────────────────────────────────────
        $baseQuery = Peminjaman::with(['user:id,name', 'barang:id,nama_barang'])
            ->whereBetween('tanggal_pinjam', [$from, $to]);

        if ($userId) {
            $baseQuery->where('user_id', $userId);
        }

        $allData = $baseQuery->get();

        // ── 1. Summary ────────────────────────────────────────────────────
        $total        = $allData->count();
        $dipinjam     = $allData->where('status', 'dipinjam')->count();
        $menunggu     = $allData->where('status', 'menunggu_konfirmasi')->count();
        $dikembalikan = $allData->where('status', 'dikembalikan')->count();

        // ── 2. Trend harian / mingguan ────────────────────────────────────
        $diffDays    = (int) $from->diffInDays($to) + 1;
        $trendLabels = [];
        $trendData   = [];

        if ($diffDays <= 31) {
            for ($i = 0; $i < $diffDays; $i++) {
                $day = $from->copy()->addDays($i);
                $trendLabels[] = $day->format('d M');
                $trendData[]   = $allData->filter(
                    fn($p) => $p->tanggal_pinjam->isSameDay($day)
                )->count();
            }
        } else {
            $current = $from->copy()->startOfWeek();
            while ($current->lte($to)) {
                $weekEnd = $current->copy()->endOfWeek();
                $trendLabels[] = $current->format('d M');
                $trendData[]   = $allData->filter(
                    fn($p) => $p->tanggal_pinjam->between($current, $weekEnd)
                )->count();
                $current->addWeek();
            }
        }

        // ── 3. Top 5 Barang ───────────────────────────────────────────────
        $topBarang = $allData->groupBy('barang_id')->map(function ($grp) {
            return [
                'nama'  => optional($grp->first()->barang)->nama_barang ?? 'N/A',
                'total' => $grp->count(),
            ];
        })->sortByDesc('total')->take(5)->values();

        // ── 4. Top 5 Siswa ────────────────────────────────────────────────
        $topSiswa = $allData->groupBy('user_id')->map(function ($grp) {
            return [
                'nama'  => optional($grp->first()->user)->name ?? 'N/A',
                'total' => $grp->count(),
            ];
        })->sortByDesc('total')->take(5)->values();

        return response()->json([
            'period_label' => $from->translatedFormat('d M Y') . ' – ' . $to->translatedFormat('d M Y'),
            'summary' => [
                'total'        => $total,
                'dipinjam'     => $dipinjam,
                'menunggu'     => $menunggu,
                'dikembalikan' => $dikembalikan,
            ],
            'trend' => [
                'labels' => $trendLabels,
                'data'   => $trendData,
            ],
            'top_barang' => $topBarang,
            'top_siswa'  => $topSiswa,
        ]);
    }
}
