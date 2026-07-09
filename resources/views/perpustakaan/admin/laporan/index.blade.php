@extends('perpustakaan.layouts.app')

@section('title', 'Laporan Perpustakaan')
@section('page-title', 'Laporan Perpustakaan')
@section('page-subtitle', 'Rekap pengembalian dan denda berdasarkan periode')

@section('content')
<!-- Statistik ringkas -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card border-l-4 border-l-green-500">
        <p class="text-2xl font-extrabold text-slate-800">{{ $totalPeminjaman }}</p>
        <p class="text-xs text-slate-500 mt-0.5">Total Peminjaman</p>
    </div>
    <div class="stat-card border-l-4 border-l-blue-500">
        <p class="text-2xl font-extrabold text-slate-800">{{ $totalDikembalikan }}</p>
        <p class="text-xs text-slate-500 mt-0.5">Sudah Dikembalikan</p>
    </div>
    <div class="stat-card border-l-4 border-l-amber-500">
        <p class="text-2xl font-extrabold text-slate-800">{{ $totalAktif }}</p>
        <p class="text-xs text-slate-500 mt-0.5">Sedang Dipinjam</p>
    </div>
    <div class="stat-card border-l-4 border-l-red-500">
        <p class="text-2xl font-extrabold text-red-600">{{ $totalTerlambat }}</p>
        <p class="text-xs text-slate-500 mt-0.5">Terlambat</p>
    </div>
</div>

<!-- Filter periode -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 mb-1">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ request('dari') }}"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 mb-1">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <button type="submit" class="btn-perpus">Filter</button>
        <a href="{{ route('perpustakaan.admin.laporan.index') }}" class="btn-warning">Reset</a>
        
        <a href="{{ route('perpustakaan.admin.laporan.export', request()->all()) }}" class="ml-auto btn-perpus">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Download PDF 
        </a>
    </form>
</div>

<!-- Total Denda -->
@if(request('dari') || request('sampai'))
<div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl mb-6">
    <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
        <span class="text-sm font-medium">Total Denda Periode Ini:</span>
        <span class="text-xl font-extrabold ml-2">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
    </div>
</div>
@endif

<!-- Table -->
<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="font-bold text-slate-800">Detail Pengembalian & Denda</h2>
        <span class="text-xs text-slate-500">{{ $pengembalians->total() }} data</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Anggota</th>
                    <th class="table-th">Buku</th>
                    <th class="table-th">Tgl Pinjam</th>
                    <th class="table-th">Batas Kembali</th>
                    <th class="table-th">Tgl Kembali</th>
                    <th class="table-th text-center">Hari Telat</th>
                    <th class="table-th text-right">Denda</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pengembalians as $i => $k)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td text-slate-400 text-xs">{{ $pengembalians->firstItem() + $i }}</td>
                    <td class="table-td">
                        <div class="font-semibold text-slate-800">{{ $k->peminjaman->user->name }}</div>
                        <div class="text-xs text-slate-400">{{ $k->peminjaman->user->kelas ?? '-' }}</div>
                    </td>
                    <td class="table-td">
                        <div class="font-medium text-slate-700">{{ Str::limit($k->peminjaman->buku->judul, 28) }}</div>
                    </td>
                    <td class="table-td text-slate-600 text-sm">{{ $k->peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="table-td text-slate-600 text-sm">{{ $k->peminjaman->batas_kembali->format('d/m/Y') }}</td>
                    <td class="table-td text-slate-600 text-sm">{{ $k->tanggal_kembali->format('d/m/Y') }}</td>
                    <td class="table-td text-center">
                        @if($k->hari_terlambat > 0)
                            <span class="badge-terlambat">{{ $k->hari_terlambat }} hari</span>
                        @else
                            <span class="badge-dikembalikan">Tepat waktu</span>
                        @endif
                    </td>
                    <td class="table-td text-right">
                        <span class="{{ $k->denda > 0 ? 'text-red-600 font-bold' : 'text-green-600 font-medium' }} text-sm">
                            {{ $k->dendaFormatted() }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="table-td text-center text-slate-400 py-10">Belum ada data pengembalian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengembalians->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">{{ $pengembalians->links() }}</div>
    @endif
</div>
@endsection
