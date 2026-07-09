@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan sistem peminjaman alat bengkel')

@section('content')
<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="card p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Barang</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_barang'] }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $stats['total_stok'] }} unit tersedia</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Dipinjam Aktif</p>
                <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['peminjaman_aktif'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Sedang dipinjam siswa</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Siswa</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_siswa'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Akun terdaftar</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Kembali Hari Ini</p>
                <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['dikembalikan_hari'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Pengembalian hari ini</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Materi</p>
                <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['total_materi'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Materi pembelajaran</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>
    </div>
</div>

<!-- Widget Predictive Maintenance (Tampil jika ada alat yang butuh servis) -->
@if($barang_maintenance->count() > 0)
<div class="mb-6 p-1 rounded-2xl bg-gradient-to-r from-rose-500 via-red-500 to-rose-600 shadow-sm shadow-red-200/50">
    <div class="bg-white rounded-xl p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Peringatan Servis / Kalibrasi Alat</h3>
                <p class="text-sm text-slate-500">Alat berikut telah mencapai atau melewati batas maksimal siklus pemakaian.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($barang_maintenance as $bm)
            <div class="border border-red-100 bg-red-50/30 rounded-xl p-4 flex flex-col justify-between">
                <div>
                    <h4 class="font-bold text-red-800">{{ $bm->nama_barang }}</h4>
                    <p class="text-xs text-red-600/80 mb-3">{{ $bm->kode_barang }}</p>
                    
                    <div class="flex justify-between text-xs text-red-900 font-medium mb-1">
                        <span>Dipakai: {{ $bm->jumlah_dipakai }}x</span>
                        <span>Batas: {{ $bm->batas_pemakaian }}x</span>
                    </div>
                    <div class="w-full bg-red-200 rounded-full h-1.5 mb-4 overflow-hidden">
                        @php $pct = min(($bm->jumlah_dipakai / $bm->batas_pemakaian) * 100, 100); @endphp
                        <div class="bg-red-600 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                
                <form action="{{ route('admin.barangs.reset-maintenance', $bm->id) }}" method="POST" class="reset-maintenance-form" data-message="Apakah '{{ $bm->nama_barang }}' sudah diservis dan siap digunakan kembali? Siklus pemakaian akan di-reset ke 0.">
                    @csrf
                    <button type="submit" class="w-full text-center py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors">
                        Tandai Sudah Diservis
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Bottom Section -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Peminjaman Terbaru -->
    <div class="card xl:col-span-2">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800 text-sm">Peminjaman Terbaru</h2>
            <a href="{{ route('admin.peminjaman.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="table-th">Siswa</th>
                        <th class="table-th">Barang</th>
                        <th class="table-th">Tgl Pinjam</th>
                        <th class="table-th">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($peminjaman_terbaru as $p)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="table-td font-medium">{{ $p->user->name }}</td>
                        <td class="table-td">{{ $p->barang->nama_barang }}</td>
                        <td class="table-td">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="table-td">
                            @if($p->status === 'dipinjam')
                                <span class="badge-dipinjam">⏳ Dipinjam</span>
                            @elseif($p->status === 'menunggu_konfirmasi')
                                <span class="badge-menunggu">⏳ Menunggu Konfirmasi</span>
                            @else
                                <span class="badge-dikembalikan">✓ Kembali</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="table-td text-center text-slate-400 py-6">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stok Minim -->
    <div class="card">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800 text-sm">⚠️ Stok Minim</h2>
            <p class="text-xs text-slate-400 mt-0.5">Barang dengan stok ≤ 2</p>
        </div>
        <div class="p-5 space-y-3">
            @forelse($barang_stok_minim as $b)
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                <div>
                    <p class="text-sm font-medium text-slate-700">{{ $b->nama_barang }}</p>
                    <p class="text-xs text-slate-400">{{ $b->kode_barang }}</p>
                </div>
                <span class="text-lg font-bold {{ $b->stok === 0 ? 'text-red-500' : 'text-amber-500' }}">
                    {{ $b->stok }}
                </span>
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-4">Semua stok aman ✓</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
