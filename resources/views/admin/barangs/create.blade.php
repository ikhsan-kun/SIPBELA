@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang Baru')
@section('page-subtitle', 'Tambahkan alat baru ke inventaris bengkel')
@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('admin.barangs.store') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Kode Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_barang" value="{{ old('kode_barang') }}"
                        placeholder="Contoh: BRG-011"
                        class="w-full border @error('kode_barang') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('kode_barang')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Stok Awal <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" value="{{ old('stok', 0) }}" min="0"
                        class="w-full border @error('stok') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('stok')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Barang <span class="text-red-500">*</span></label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                    placeholder="Contoh: Kunci Ring Set 8-32mm"
                    class="w-full border @error('nama_barang') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('nama_barang')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Kondisi <span class="text-red-500">*</span></label>
                    <select name="kondisi"
                        class="w-full border @error('kondisi') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="baik" {{ old('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak" {{ old('kondisi') === 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="diperbaiki" {{ old('kondisi') === 'diperbaiki' ? 'selected' : '' }}>Sedang Diperbaiki</option>
                    </select>
                    @error('kondisi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Batas Pemakaian 
                        <span class="group relative inline-block ml-1 cursor-help text-blue-500 hover:text-blue-700">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden w-48 rounded bg-slate-800 px-2 py-1 text-center text-xs text-white group-hover:block z-10">Batas maksimal dipinjam sebelum peringatan servis muncul. Isi 0 jika tanpa batas.</span>
                        </span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="batas_pemakaian" value="{{ old('batas_pemakaian', 50) }}" min="0"
                        class="w-full border @error('batas_pemakaian') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('batas_pemakaian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    placeholder="Deskripsi singkat barang (opsional)"
                    class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Barang
                </button>
                <a href="{{ route('admin.barangs.index') }}"
                    class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm hover:bg-slate-50 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
