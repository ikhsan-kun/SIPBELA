@extends('layouts.app')
@section('title', 'Laporan Peminjaman')
@section('page-title', 'Laporan Peminjaman')
@section('page-subtitle', 'Riwayat lengkap transaksi dengan filter periode')

@push('styles')
<style>
    /* ── Sidebar Statistik ─────────────────────────────────────────── */
    .stat-sidebar {
        width: 340px;
        flex-shrink: 0;
    }
    .stat-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .stat-card-header {
        padding: 1rem 1.25rem 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .stat-card-title {
        font-size: 0.8rem; font-weight: 700;
        color: #1e293b; text-transform: uppercase; letter-spacing: 0.06em;
    }
    .stat-card-body { padding: 1rem 1.25rem; }

    /* Period pills */
    .period-pill {
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        font-size: 0.72rem; font-weight: 600;
        border: 1.5px solid #e2e8f0; color: #64748b;
        cursor: pointer; transition: all 0.18s; background: #fff;
    }
    .period-pill.active, .period-pill:hover {
        background: #2563eb; color: #fff; border-color: #2563eb;
    }

    /* Mini stat bars */
    .bar-row { margin-bottom: 0.6rem; }
    .bar-label { font-size: 0.72rem; color: #475569; display: flex; justify-content: space-between; margin-bottom: 0.2rem; }
    .bar-track { height: 7px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
    .bar-fill { height: 100%; border-radius: 99px; transition: width 0.5s ease; }

    /* Spinner overlay */
    .stat-loading {
        position: absolute; inset: 0;
        background: rgba(255,255,255,0.75);
        display: flex; align-items: center; justify-content: center;
        border-radius: 1rem; z-index: 10;
        backdrop-filter: blur(2px);
    }
    .stat-loading.hidden { display: none; }

    /* Summary metric tiles */
    .metric-tile {
        display: flex; flex-direction: column; align-items: center;
        padding: 0.75rem 0.5rem; border-radius: 0.75rem;
        background: #f8fafc; border: 1px solid #f1f5f9;
    }
    .metric-tile .val { font-size: 1.6rem; font-weight: 800; line-height: 1; }
    .metric-tile .lbl { font-size: 0.65rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.3rem; }

    /* Date custom range */
    #custom-date-range { display: none; animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; } }

    /* Siswa selector */
    .siswa-select {
        width: 100%; border: 1.5px solid #e2e8f0; border-radius: 0.625rem;
        padding: 0.4rem 0.75rem; font-size: 0.8rem; color: #334155;
        background: #fff; cursor: pointer;
        transition: border-color 0.2s;
    }
    .siswa-select:focus { outline: none; border-color: #2563eb; }

    /* Trend chart container */
    #trend-chart-wrap { position: relative; height: 140px; }

    /* Top list items */
    .top-item {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.4rem 0; border-bottom: 1px solid #f8fafc;
    }
    .top-item:last-child { border-bottom: none; }
    .top-rank {
        width: 1.4rem; height: 1.4rem; border-radius: 50%;
        font-size: 0.65rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .rank-1 { background: #fef3c7; color: #92400e; }
    .rank-2 { background: #e2e8f0; color: #475569; }
    .rank-3 { background: #fde8d8; color: #9a3412; }
    .rank-other { background: #f1f5f9; color: #64748b; }

    /* Wrap the two columns */
    .laporan-layout {
        display: flex; gap: 1.5rem; align-items: flex-start;
    }
    .laporan-main { flex: 1; min-width: 0; }
    @media (max-width: 1199px) {
        .laporan-layout { flex-direction: column; }
        .stat-sidebar { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="laporan-layout">

    {{-- ── MAIN CONTENT ──────────────────────────────────────────────────── --}}
    <div class="laporan-main">

        {{-- Filter Card --}}
        <div class="card p-5 mb-5">
            <form method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Status</label>
                    <select name="status" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua</option>
                        <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="menunggu_konfirmasi" {{ request('status') === 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Siswa</label>
                    <select name="user_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Siswa</option>
                        @foreach($siswas as $s)
                        <option value="{{ $s->id }}" {{ request('user_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2 md:col-span-4 flex flex-wrap gap-2">
                    <button type="submit" class="btn-primary">Terapkan Filter</button>
                    <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm hover:bg-slate-50">Reset</a>
                    <a href="{{ route('admin.laporan.export', request()->all()) }}" class="ml-auto btn-perpus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download PDF
                    </a>
                </div>
            </form>
        </div>

        {{-- Summary Mini Cards --}}
        <div class="grid grid-cols-3 gap-4 mb-5">
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-slate-800">{{ $peminjamans->total() }}</p>
                <p class="text-xs text-slate-500 mt-1">Total Transaksi</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-amber-600">{{ $summary['dipinjam'] }}</p>
                <p class="text-xs text-slate-500 mt-1">Sedang Dipinjam</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-2xl font-bold text-emerald-600">{{ $summary['dikembalikan'] }}</p>
                <p class="text-xs text-slate-500 mt-1">Sudah Kembali</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="table-th">No</th>
                            <th class="table-th">Siswa</th>
                            <th class="table-th">Barang</th>
                            <th class="table-th">Tgl Pinjam</th>
                            <th class="table-th">Tgl Kembali</th>
                            <th class="table-th">Status</th>
                            <th class="table-th">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($peminjamans as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="table-td text-slate-400">{{ $peminjamans->firstItem() + $loop->index }}</td>
                            <td class="table-td font-medium">{{ $p->user->name }}</td>
                            <td class="table-td">{{ $p->barang->nama_barang }}</td>
                            <td class="table-td">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td class="table-td">{{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d/m/Y') : '—' }}</td>
                            <td class="table-td">
                                @if($p->status === 'dipinjam')
                                    <span class="badge-dipinjam">⏳ Dipinjam</span>
                                @elseif($p->status === 'menunggu_konfirmasi')
                                    <span class="badge-menunggu">⏳ Menunggu Konfirmasi</span>
                                @else
                                    <span class="badge-dikembalikan">✓ Kembali</span>
                                @endif
                            </td>
                            <td class="table-td text-slate-500 max-w-xs truncate">{{ $p->catatan ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="table-td text-center text-slate-400 py-10">Tidak ada data untuk filter ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($peminjamans->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $peminjamans->links() }}</div>
            @endif
        </div>
    </div>{{-- /laporan-main --}}

    {{-- ── SIDEBAR STATISTIK ─────────────────────────────────────────────── --}}
    <aside class="stat-sidebar" id="stat-sidebar">

        {{-- ── Period Selector ── --}}
        <div class="stat-card mb-4">
            <div class="stat-card-header">
                <span class="stat-card-title">📊 Statistik</span>
                <div id="stat-loading-spinner" class="hidden">
                    <svg class="w-4 h-4 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-card-body">
                {{-- Quick period pills --}}
                <div class="flex gap-2 flex-wrap mb-3">
                    <button class="period-pill active" data-period="7d">7 Hari</button>
                    <button class="period-pill" data-period="30d">30 Hari</button>
                    <button class="period-pill" data-period="custom">Custom</button>
                </div>

                {{-- Custom date range --}}
                <div id="custom-date-range" class="mb-3">
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Dari</label>
                            <input type="date" id="stat-date-from"
                                class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Sampai</label>
                            <input type="date" id="stat-date-to"
                                class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    </div>
                    <button id="apply-custom-date" class="mt-2 w-full btn-primary justify-center py-1.5 text-xs">Terapkan</button>
                </div>

                {{-- Filter by Siswa --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Filter per Siswa</label>
                    <select id="stat-user-id" class="siswa-select">
                        <option value="">Semua Siswa</option>
                        @foreach($siswas as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Period Label ── --}}
        <div class="mb-3 px-1">
            <p class="text-[10px] text-slate-400 font-medium" id="stat-period-label">Memuat data...</p>
        </div>

        {{-- ── Summary Tiles ── --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="metric-tile">
                <span class="val text-slate-800" id="s-total">—</span>
                <span class="lbl">Total</span>
            </div>
            <div class="metric-tile">
                <span class="val text-amber-500" id="s-dipinjam">—</span>
                <span class="lbl">Dipinjam</span>
            </div>
            <div class="metric-tile">
                <span class="val text-emerald-500" id="s-kembali">—</span>
                <span class="lbl">Kembali</span>
            </div>
            <div class="metric-tile">
                <span class="val text-blue-500" id="s-menunggu">—</span>
                <span class="lbl">Menunggu</span>
            </div>
        </div>

        {{-- ── Trend Chart ── --}}
        <div class="stat-card mb-4">
            <div class="stat-card-header">
                <span class="stat-card-title">📈 Tren Peminjaman</span>
            </div>
            <div class="stat-card-body" style="padding-bottom:1.25rem">
                <div id="trend-chart-wrap">
                    <canvas id="trend-chart"></canvas>
                </div>
            </div>
        </div>

        {{-- ── Top Barang ── --}}
        <div class="stat-card mb-4">
            <div class="stat-card-header">
                <span class="stat-card-title">🔧 Top Alat Dipinjam</span>
            </div>
            <div class="stat-card-body" id="top-barang-list">
                <p class="text-xs text-slate-400 text-center py-4">Memuat...</p>
            </div>
        </div>

        {{-- ── Top Siswa ── --}}
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">🏆 Top Siswa Peminjam</span>
            </div>
            <div class="stat-card-body" id="top-siswa-list">
                <p class="text-xs text-slate-400 text-center py-4">Memuat...</p>
            </div>
        </div>

    </aside>{{-- /stat-sidebar --}}

</div>{{-- /laporan-layout --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    // ── Config ──────────────────────────────────────────────────────────────
    const API_URL = '{{ route("admin.laporan.statistik") }}';
    let currentPeriod = '7d';
    let currentUserId = '';
    let trendChart    = null;

    // ── DOM refs ─────────────────────────────────────────────────────────────
    const spinner      = document.getElementById('stat-loading-spinner');
    const periodLabel  = document.getElementById('stat-period-label');
    const sTot         = document.getElementById('s-total');
    const sDip         = document.getElementById('s-dipinjam');
    const sKem         = document.getElementById('s-kembali');
    const sMen         = document.getElementById('s-menunggu');
    const topBarang    = document.getElementById('top-barang-list');
    const topSiswa     = document.getElementById('top-siswa-list');
    const customRange  = document.getElementById('custom-date-range');
    const userSelect   = document.getElementById('stat-user-id');
    const dateFrom     = document.getElementById('stat-date-from');
    const dateTo       = document.getElementById('stat-date-to');
    const applyBtn     = document.getElementById('apply-custom-date');

    // ── Helpers ──────────────────────────────────────────────────────────────
    function showLoading(yes) {
        spinner.classList.toggle('hidden', !yes);
    }

    function animateCount(el, target) {
        const start = parseInt(el.textContent) || 0;
        const diff  = target - start;
        const dur   = 400;
        let startTime = null;
        function step(ts) {
            if (!startTime) startTime = ts;
            const prog = Math.min((ts - startTime) / dur, 1);
            el.textContent = Math.round(start + diff * prog);
            if (prog < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    function buildTopList(container, items, nameKey, colorClass) {
        if (!items || !items.length) {
            container.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">Tidak ada data</p>';
            return;
        }
        const max = items[0].total;
        container.innerHTML = items.map((it, i) => {
            const rank  = i + 1;
            const pct   = max > 0 ? (it.total / max * 100).toFixed(0) : 0;
            const ranks = ['rank-1','rank-2','rank-3','rank-other','rank-other'];
            return `
            <div class="top-item">
                <span class="top-rank ${ranks[i] || 'rank-other'}">${rank}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-700 truncate">${it[nameKey] ?? '—'}</p>
                    <div class="bar-track mt-1">
                        <div class="bar-fill ${colorClass}" style="width:0%" data-pct="${pct}"></div>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-600 flex-shrink-0">${it.total}x</span>
            </div>`;
        }).join('');
        // animate bars
        setTimeout(() => {
            container.querySelectorAll('.bar-fill').forEach(b => {
                b.style.width = b.dataset.pct + '%';
            });
        }, 50);
    }

    // ── Chart ────────────────────────────────────────────────────────────────
    function renderChart(labels, data) {
        const ctx = document.getElementById('trend-chart').getContext('2d');
        if (trendChart) trendChart.destroy();

        const gradient = ctx.createLinearGradient(0, 0, 0, 140);
        gradient.addColorStop(0, 'rgba(37,99,235,0.25)');
        gradient.addColorStop(1, 'rgba(37,99,235,0)');

        trendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Peminjaman',
                    data,
                    borderColor: '#2563eb',
                    borderWidth: 2.5,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: data.length <= 10 ? 4 : 2,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#94a3b8',
                        bodyColor: '#fff',
                        padding: 8,
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} peminjaman`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 },
                            maxTicksLimit: 7,
                            maxRotation: 0 }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#94a3b8', font: { size: 10 }, precision: 0 }
                    }
                }
            }
        });
    }

    // ── Fetch ────────────────────────────────────────────────────────────────
    function fetchStats() {
        showLoading(true);

        const params = new URLSearchParams();
        if (currentPeriod === 'custom') {
            if (!dateFrom.value || !dateTo.value) {
                showLoading(false);
                return;
            }
            params.set('period', 'custom');
            params.set('date_from', dateFrom.value);
            params.set('date_to', dateTo.value);
        } else {
            params.set('period', currentPeriod);
        }
        if (currentUserId) params.set('user_id', currentUserId);

        fetch(`${API_URL}?${params.toString()}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => {
            // Period label
            if (d.period_label) periodLabel.textContent = d.period_label;

            // Summary tiles
            animateCount(sTot, d.summary.total);
            animateCount(sDip, d.summary.dipinjam);
            animateCount(sKem, d.summary.dikembalikan);
            animateCount(sMen, d.summary.menunggu);

            // Chart
            if (d.trend && d.trend.labels) {
                renderChart(d.trend.labels, d.trend.data);
            } else if (d.chart) {
                // fallback: old format from DB groupBy
                renderChart(
                    d.chart.map(r => r.date),
                    d.chart.map(r => r.count)
                );
            }

            // Top Barang
            if (d.top_barang) {
                buildTopList(topBarang, d.top_barang, 'nama', 'bg-blue-500');
            } else if (d.top_items) {
                buildTopList(topBarang,
                    d.top_items.map(i => ({ nama: i.barang?.nama_barang, total: i.total })),
                    'nama', 'bg-blue-500');
            }

            // Top Siswa
            if (d.top_siswa) {
                buildTopList(topSiswa, d.top_siswa, 'nama', 'bg-emerald-500');
            } else if (d.top_borrowers) {
                buildTopList(topSiswa,
                    d.top_borrowers.map(b => ({ nama: b.user?.name, total: b.total })),
                    'nama', 'bg-emerald-500');
            }
        })
        .catch(err => {
            console.error('Stat fetch error:', err);
            periodLabel.textContent = 'Gagal memuat data.';
        })
        .finally(() => showLoading(false));
    }

    // ── Event Handlers ───────────────────────────────────────────────────────
    // Period pills
    document.querySelectorAll('.period-pill').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.period-pill').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentPeriod = btn.dataset.period;

            if (currentPeriod === 'custom') {
                customRange.style.display = 'block';
            } else {
                customRange.style.display = 'none';
                fetchStats();
            }
        });
    });

    // Apply custom date
    applyBtn.addEventListener('click', () => {
        if (!dateFrom.value || !dateTo.value) {
            dateFrom.focus();
            return;
        }
        fetchStats();
    });

    // Siswa filter
    userSelect.addEventListener('change', () => {
        currentUserId = userSelect.value;
        if (currentPeriod !== 'custom') fetchStats();
        else if (dateFrom.value && dateTo.value) fetchStats();
    });

    // ── Init ─────────────────────────────────────────────────────────────────
    // Pre-fill default dates for custom
    const today  = new Date();
    const minus30 = new Date(today); minus30.setDate(today.getDate() - 29);
    dateFrom.value = minus30.toISOString().split('T')[0];
    dateTo.value   = today.toISOString().split('T')[0];

    // Load on page ready
    fetchStats();
})();
</script>
@endpush
