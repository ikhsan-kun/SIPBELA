@extends('layouts.app')
@section('title', 'Laporan Peminjaman')
@section('page-title', 'Laporan Peminjaman')
@section('page-subtitle', 'Riwayat lengkap transaksi dengan filter periode')
@section('content')
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
@endsection
