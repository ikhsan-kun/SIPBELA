@extends('layouts.app')

@section('title', 'Manajemen Barang')
@section('page-title', 'Manajemen Barang')
@section('page-subtitle', 'Kelola data alat praktikum bengkel')

@section('content')
<div class="card">
    <!-- Header -->
    <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <form method="GET" class="flex gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama / kode barang..."
                class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="kondisi" class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kondisi</option>
                <option value="baik" {{ request('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak" {{ request('kondisi') === 'rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="diperbaiki" {{ request('kondisi') === 'diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
            </select>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filter
            </button>
            @if(request()->hasAny(['search', 'kondisi']))
            <a href="{{ route('admin.barangs.index') }}" class="px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </form>
        <a href="{{ route('admin.barangs.create') }}" class="btn-primary flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Barang
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Kode</th>
                    <th class="table-th">Nama Barang</th>
                    <th class="table-th">Stok</th>
                    <th class="table-th">Pemakaian</th>
                    <th class="table-th">Kondisi</th>
                    <th class="table-th text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($barangs as $barang)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td text-slate-400">{{ $barangs->firstItem() + $loop->index }}</td>
                    <td class="table-td">
                        <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded">{{ $barang->kode_barang }}</span>
                    </td>
                    <td class="table-td font-medium text-slate-800">{{ $barang->nama_barang }}</td>
                    <td class="table-td">
                        <span class="font-bold {{ $barang->stok === 0 ? 'text-red-500' : ($barang->stok <= 2 ? 'text-amber-500' : 'text-emerald-600') }}">
                            {{ $barang->stok }}
                        </span>
                    </td>
                    <td class="table-td">
                        @if($barang->batas_pemakaian > 0)
                            <div class="w-24">
                                <div class="flex justify-between text-[10px] font-bold mb-1 {{ $barang->butuhMaintenance() ? 'text-red-600' : 'text-slate-500' }}">
                                    <span>{{ $barang->jumlah_dipakai }}x</span>
                                    <span>{{ $barang->batas_pemakaian }}x</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5">
                                    @php $pct = min(($barang->jumlah_dipakai / $barang->batas_pemakaian) * 100, 100); @endphp
                                    <div class="h-1.5 rounded-full {{ $barang->butuhMaintenance() ? 'bg-red-500 shadow-sm shadow-red-200' : 'bg-blue-500' }}" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @else
                            <span class="text-xs text-slate-400 italic">Tanpa batas</span>
                        @endif
                    </td>
                    <td class="table-td">
                        <span class="badge-{{ $barang->kondisi }}">{{ ucfirst($barang->kondisi) }}</span>
                    </td>
                    <td class="table-td">
                        <div class="flex items-center justify-center gap-2">
                            @if($barang->butuhMaintenance())
                            <form method="POST" action="{{ route('admin.barangs.reset-maintenance', $barang) }}" class="inline reset-maintenance-form" data-message="Reset siklus pemakaian untuk '{{ $barang->nama_barang }}' menjadi 0? Pastikan alat sudah diservis.">
                                @csrf
                                <button type="submit" class="bg-red-100 text-red-600 hover:bg-red-600 hover:text-white px-2.5 py-1.5 rounded-lg text-xs font-bold transition-colors" title="Reset Siklus Servis">
                                    🔧 Reset
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.barangs.edit', $barang) }}" class="btn-warning" title="Edit Barang">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.barangs.destroy', $barang) }}"
                                class="delete-form inline" data-message="Hapus barang {{ $barang->nama_barang }}? Tindakan ini tidak dapat dibatalkan.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" title="Hapus Barang">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="table-td text-center text-slate-400 py-10">
                        <svg class="w-12 h-12 mx-auto text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Tidak ada barang ditemukan.
                        <a href="{{ route('admin.barangs.create') }}" class="text-blue-600 hover:underline ml-1">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($barangs->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">
        {{ $barangs->links() }}
    </div>
    @endif
</div>
@endsection
