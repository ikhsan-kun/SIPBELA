<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BengkelNotification;
use App\Models\Peminjaman;
use App\Models\Perpustakaan\PerpusBuku;
use App\Models\Perpustakaan\PerpusMasterSiswa;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-sync missing student accounts from master siswa list on load
        User::syncFromMaster();

        // ── Statistik Utama ──────────────────────────────────────────────────
        $totalSiswa          = User::where('role', 'siswa')->count();
        $totalSiswaMaster    = PerpusMasterSiswa::count();
        $totalPeminjamanBengkel = Peminjaman::count();
        $totalPeminjamanPerpus  = PerpusPeminjaman::count();

        // ── Distribusi Jurusan ───────────────────────────────────────────────
        $jurusanDistribution = [
            'TKR' => User::where('role', 'siswa')->whereIn('jurusan', ['TKR', 'Teknik Kendaraan Ringan'])->count(),
            'TKJ' => User::where('role', 'siswa')->whereIn('jurusan', ['TKJ', 'Teknik Komputer Jaringan'])->count(),
            'RPL' => User::where('role', 'siswa')->whereIn('jurusan', ['RPL', 'Rekayasa Perangkat Lunak'])->count(),
            'MM'  => User::where('role', 'siswa')->whereIn('jurusan', ['MM', 'Multimedia'])->count(),
            'DG'  => User::where('role', 'siswa')->whereIn('jurusan', ['DG', 'Desain Grafis'])->count(),
            'TEI' => User::where('role', 'siswa')->whereIn('jurusan', ['TEI', 'Teknik Audio Video'])->count(),
        ];

        // ── Log Aktivitas Gabungan (Bengkel + Perpus + Manajemen Barang) ─────
        $activityLog = collect();

        // Bengkel: pengajuan & pengembalian terbaru (via notifikasi)
        BengkelNotification::with('user')
            ->whereIn('type', ['request_submitted', 'request_approved', 'request_rejected', 'return_submitted', 'return_confirmed'])
            ->latest()->take(15)->get()
            ->each(function ($n) use ($activityLog) {
                $colorMap = [
                    'request_submitted' => ['color' => 'bg-blue-500',    'icon' => '📋', 'badge' => 'bg-blue-100 text-blue-700'],
                    'request_approved'  => ['color' => 'bg-emerald-500', 'icon' => '✅', 'badge' => 'bg-emerald-100 text-emerald-700'],
                    'request_rejected'  => ['color' => 'bg-red-400',     'icon' => '❌', 'badge' => 'bg-red-100 text-red-600'],
                    'return_submitted'  => ['color' => 'bg-amber-500',   'icon' => '📦', 'badge' => 'bg-amber-100 text-amber-700'],
                    'return_confirmed'  => ['color' => 'bg-teal-500',    'icon' => '🔧', 'badge' => 'bg-teal-100 text-teal-700'],
                ];
                $meta = $colorMap[$n->type] ?? ['color' => 'bg-slate-400', 'icon' => '•', 'badge' => 'bg-slate-100 text-slate-500'];
                $activityLog->push([
                    'category'  => 'Bengkel',
                    'color'     => $meta['color'],
                    'icon'      => $meta['icon'],
                    'badge'     => $meta['badge'],
                    'title'     => $n->message,
                    'time'      => $n->created_at,
                    'time_diff' => $n->created_at->diffForHumans(),
                ]);
            });

        // Perpus: peminjaman buku terbaru
        PerpusPeminjaman::with(['user', 'buku'])->latest()->take(10)->get()
            ->each(function ($p) use ($activityLog) {
                $userName = $p->user?->name ?? 'Siswa';
                $bukuName = $p->buku?->judul ?? 'Buku';
                $statusLabel = match($p->status) {
                    'dikembalikan'         => ['icon' => '📚', 'label' => 'Mengembalikan buku', 'badge' => 'bg-teal-100 text-teal-700'],
                    'dipinjam'             => ['icon' => '📖', 'label' => 'Meminjam buku', 'badge' => 'bg-indigo-100 text-indigo-700'],
                    'menunggu_konfirmasi'  => ['icon' => '⏳', 'label' => 'Ajukan pengembalian buku', 'badge' => 'bg-amber-100 text-amber-700'],
                    default                => ['icon' => '📝', 'label' => 'Aktivitas buku', 'badge' => 'bg-slate-100 text-slate-500'],
                };
                $activityLog->push([
                    'category'  => 'Perpus',
                    'color'     => 'bg-indigo-500',
                    'icon'      => $statusLabel['icon'],
                    'badge'     => $statusLabel['badge'],
                    'title'     => "{$userName} {$statusLabel['label']}: {$bukuName}",
                    'time'      => $p->created_at,
                    'time_diff' => $p->created_at->diffForHumans(),
                ]);
            });

        // Manajemen Barang: penambahan barang bengkel terbaru
        Barang::latest()->take(5)->get()
            ->each(function ($b) use ($activityLog) {
                $activityLog->push([
                    'category'  => 'Barang',
                    'color'     => 'bg-rose-400',
                    'icon'      => '⚙️',
                    'badge'     => 'bg-rose-100 text-rose-700',
                    'title'     => "Barang bengkel ditambahkan/diupdate: {$b->nama_barang} (stok: {$b->stok})",
                    'time'      => $b->updated_at,
                    'time_diff' => $b->updated_at->diffForHumans(),
                ]);
            });

        // Manajemen Buku Perpus
        PerpusBuku::latest()->take(5)->get()
            ->each(function ($b) use ($activityLog) {
                $activityLog->push([
                    'category'  => 'Buku',
                    'color'     => 'bg-violet-400',
                    'icon'      => '📕',
                    'badge'     => 'bg-violet-100 text-violet-700',
                    'title'     => "Buku perpus ditambahkan/diupdate: {$b->judul} (stok: {$b->stok})",
                    'time'      => $b->updated_at,
                    'time_diff' => $b->updated_at->diffForHumans(),
                ]);
            });

        $activityLog = $activityLog->sortByDesc('time')->take(20)->values();

        // ── Statistik Bengkel Hari Ini ────────────────────────────────────────
        $bengkelStats = [
            'peminjaman_hari_ini'   => Peminjaman::whereDate('created_at', today())->count(),
            'menunggu_persetujuan'  => Peminjaman::whereIn('status', ['menunggu_persetujuan', 'menunggu_konfirmasi'])->count(),
            'sedang_dipinjam'       => Peminjaman::where('status', 'dipinjam')->count(),
            'dikembalikan_hari_ini' => Peminjaman::where('status', 'dikembalikan')->whereDate('tanggal_kembali', today())->count(),
        ];

        // ── Ranking Siswa Teraktif Bengkel (berdasarkan total peminjaman yg selesai dikembalikan) ──
        $topSiswaBengkel = Peminjaman::select('user_id', DB::raw('COUNT(*) as total_pinjam'))
            ->with('user:id,name,kelas,jurusan,nis')
            ->where('status', 'dikembalikan')           // hanya yang sudah dikembalikan (aktif = pinjam & kembalikan)
            ->groupBy('user_id')
            ->orderByDesc('total_pinjam')
            ->take(10)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank'        => $index + 1,
                    'name'        => $item->user?->name ?? 'Unknown',
                    'kelas'       => $item->user?->kelas ?? '-',
                    'jurusan'     => $item->user?->jurusan ?? '-',
                    'nis'         => $item->user?->nis ?? '-',
                    'total_pinjam'=> $item->total_pinjam,
                ];
            });

        $maxBengkel = $topSiswaBengkel->max('total_pinjam') ?: 1;

        // ── Ranking Siswa Teraktif Perpus (berdasarkan total peminjaman selesai) ──
        $topSiswaPerpus = PerpusPeminjaman::select('user_id', DB::raw('COUNT(*) as total_pinjam'))
            ->with('user:id,name,kelas,jurusan,nis')
            ->where('status', 'dikembalikan')
            ->groupBy('user_id')
            ->orderByDesc('total_pinjam')
            ->take(10)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank'        => $index + 1,
                    'name'        => $item->user?->name ?? 'Unknown',
                    'kelas'       => $item->user?->kelas ?? '-',
                    'jurusan'     => $item->user?->jurusan ?? '-',
                    'nis'         => $item->user?->nis ?? '-',
                    'total_pinjam'=> $item->total_pinjam,
                ];
            });

        $maxPerpus = $topSiswaPerpus->max('total_pinjam') ?: 1;

        // ── Aktivitas Bengkel lama (untuk kompatibilitas) ─────────────────────
        $bengkelActivities = BengkelNotification::with('user')
            ->latest()->take(10)->get()
            ->map(function ($n) {
                $colorMap = [
                    'request_submitted' => 'bg-blue-500',
                    'request_approved'  => 'bg-emerald-500',
                    'request_rejected'  => 'bg-red-400',
                    'return_submitted'  => 'bg-amber-500',
                    'return_confirmed'  => 'bg-teal-500',
                ];
                return [
                    'color'     => $colorMap[$n->type] ?? 'bg-slate-400',
                    'title'     => $n->message,
                    'type'      => $n->type,
                    'time'      => $n->created_at,
                    'time_diff' => $n->created_at->diffForHumans(),
                ];
            });

        $recentActivities = collect();

        // ── Tren Peminjaman 7 Hari Terakhir ──────────────────────────────────────
        $trendDates  = [];
        $trendBengkel = [];
        $trendPerpus  = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $trendDates[]   = $date->translatedFormat('D, d M');
            $trendBengkel[] = Peminjaman::whereDate('created_at', $date)->count();
            $trendPerpus[]  = PerpusPeminjaman::whereDate('created_at', $date)->count();
        }

        // ── Distribusi Peminjaman per Jurusan ─────────────────────────────────────
        $jurusanKeys = ['TKR', 'TKJ', 'RPL', 'MM', 'DG', 'TEI'];

        $peminjamanBengkelPerJurusan = [];
        foreach ($jurusanKeys as $j) {
            $peminjamanBengkelPerJurusan[$j] = Peminjaman::whereHas('user', fn($q) => $q->where('jurusan', $j)->orWhere('jurusan', 'like', "%{$j}%"))->count();
        }

        $peminjamanPerpusPerJurusan = [];
        foreach ($jurusanKeys as $j) {
            $peminjamanPerpusPerJurusan[$j] = PerpusPeminjaman::whereHas('user', fn($q) => $q->where('jurusan', $j)->orWhere('jurusan', 'like', "%{$j}%"))->count();
        }

        // ── Siapa yang Sedang Meminjam (Aktif sekarang) ───────────────────────────
        $aktifBengkel = Peminjaman::with(['user:id,name,kelas,jurusan,nis', 'barang:id,nama_barang'])
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->latest()
            ->take(15)
            ->get()
            ->map(fn($p) => [
                'nama'    => $p->user?->name ?? 'Unknown',
                'kelas'   => $p->user?->kelas ?? '-',
                'jurusan' => $p->user?->jurusan ?? '-',
                'item'    => $p->barang?->nama_barang ?? 'Barang',
                'status'  => $p->status,
                'sejak'   => $p->created_at->diffForHumans(),
                'tgl'     => $p->created_at->format('d/m H:i'),
            ]);

        $aktifPerpus = PerpusPeminjaman::with(['user:id,name,kelas,jurusan', 'buku:id,judul'])
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->latest()
            ->take(15)
            ->get()
            ->map(fn($p) => [
                'nama'    => $p->user?->name ?? 'Unknown',
                'kelas'   => $p->user?->kelas ?? '-',
                'jurusan' => $p->user?->jurusan ?? '-',
                'item'    => $p->buku?->judul ?? 'Buku',
                'status'  => $p->status,
                'sejak'   => $p->created_at->diffForHumans(),
                'tgl'     => $p->created_at->format('d/m H:i'),
            ]);

        $lastUpdated = Carbon::now()->format('H:i:s');

        return view('superadmin.dashboard', compact(
            'totalSiswa',
            'totalSiswaMaster',
            'totalPeminjamanBengkel',
            'totalPeminjamanPerpus',
            'jurusanDistribution',
            'recentActivities',
            'bengkelActivities',
            'bengkelStats',
            'activityLog',
            'topSiswaBengkel',
            'topSiswaPerpus',
            'maxBengkel',
            'maxPerpus',
            'trendDates',
            'trendBengkel',
            'trendPerpus',
            'peminjamanBengkelPerJurusan',
            'peminjamanPerpusPerJurusan',
            'aktifBengkel',
            'aktifPerpus',
            'lastUpdated',
        ));
    }

    public function checkNis($nis)
    {
        $master = PerpusMasterSiswa::where('nis', $nis)->first();
        if (!$master) {
            return response()->json(['status' => 'not_found', 'message' => 'NIS belum terdaftar di data verifikasi sekolah.']);
        }

        $registered = User::where('nis', $nis)->first();
        if ($registered) {
            return response()->json([
                'status'   => 'registered',
                'name'     => $master->nama,
                'jurusan'  => $master->jurusan,
                'kelas'    => $master->kelas,
                'username' => $registered->username,
                'email'    => $registered->email ?? '-',
                'message'  => 'Akun sudah terdaftar aktif.',
            ]);
        }

        return response()->json([
            'status'  => 'verified_only',
            'name'    => $master->nama,
            'jurusan' => $master->jurusan,
            'kelas'   => $master->kelas,
            'message' => 'NIS terverifikasi tapi akun belum didaftarkan.',
        ]);
    }

    /**
     * API: Real-time statistik peminjaman untuk polling dari frontend.
     */
    public function statistikRealtime()
    {
        $jurusanKeys = ['TKR', 'TKJ', 'RPL', 'MM', 'DG', 'TEI'];

        // Tren 7 hari
        $trendDates   = [];
        $trendBengkel = [];
        $trendPerpus  = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $trendDates[]   = $date->translatedFormat('D, d M');
            $trendBengkel[] = Peminjaman::whereDate('created_at', $date)->count();
            $trendPerpus[]  = PerpusPeminjaman::whereDate('created_at', $date)->count();
        }

        // Distribusi jurusan
        $bengkelPerJurusan = [];
        $perpusPerJurusan  = [];
        foreach ($jurusanKeys as $j) {
            $bengkelPerJurusan[$j] = Peminjaman::whereHas('user', fn($q) => $q->where('jurusan', $j)->orWhere('jurusan', 'like', "%{$j}%"))->count();
            $perpusPerJurusan[$j]  = PerpusPeminjaman::whereHas('user', fn($q) => $q->where('jurusan', $j)->orWhere('jurusan', 'like', "%{$j}%"))->count();
        }

        // Aktif meminjam sekarang
        $aktifBengkel = Peminjaman::with(['user:id,name,kelas,jurusan', 'barang:id,nama_barang'])
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->latest()->take(15)->get()
            ->map(fn($p) => [
                'nama'    => $p->user?->name ?? 'Unknown',
                'kelas'   => $p->user?->kelas ?? '-',
                'jurusan' => $p->user?->jurusan ?? '-',
                'item'    => $p->barang?->nama_barang ?? 'Barang',
                'status'  => $p->status,
                'sejak'   => $p->created_at->diffForHumans(),
                'tgl'     => $p->created_at->format('d/m H:i'),
            ]);

        $aktifPerpus = PerpusPeminjaman::with(['user:id,name,kelas,jurusan', 'buku:id,judul'])
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->latest()->take(15)->get()
            ->map(fn($p) => [
                'nama'    => $p->user?->name ?? 'Unknown',
                'kelas'   => $p->user?->kelas ?? '-',
                'jurusan' => $p->user?->jurusan ?? '-',
                'item'    => $p->buku?->judul ?? 'Buku',
                'status'  => $p->status,
                'sejak'   => $p->created_at->diffForHumans(),
                'tgl'     => $p->created_at->format('d/m H:i'),
            ]);

        // Quick stats
        $bengkelStats = [
            'peminjaman_hari_ini'   => Peminjaman::whereDate('created_at', today())->count(),
            'menunggu_persetujuan'  => Peminjaman::whereIn('status', ['menunggu_persetujuan', 'menunggu_konfirmasi'])->count(),
            'sedang_dipinjam'       => Peminjaman::where('status', 'dipinjam')->count(),
            'dikembalikan_hari_ini' => Peminjaman::where('status', 'dikembalikan')->whereDate('tanggal_kembali', today())->count(),
        ];

        return response()->json([
            'trend_dates'       => $trendDates,
            'trend_bengkel'     => $trendBengkel,
            'trend_perpus'      => $trendPerpus,
            'bengkel_jurusan'   => $bengkelPerJurusan,
            'perpus_jurusan'    => $perpusPerJurusan,
            'aktif_bengkel'     => $aktifBengkel,
            'aktif_perpus'      => $aktifPerpus,
            'bengkel_stats'     => $bengkelStats,
            'last_updated'      => Carbon::now()->format('H:i:s'),
            'total_bengkel'     => Peminjaman::count(),
            'total_perpus'      => PerpusPeminjaman::count(),
        ]);
    }
}
