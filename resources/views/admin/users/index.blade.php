@extends('layouts.app')
@section('title', 'Daftar Siswa TKR')
@section('page-title', 'Daftar Akun Siswa (Jurusan TKR)')
@section('page-subtitle', 'Daftar akun login siswa aktif jurusan Teknik Kendaraan Ringan (TKR)')
@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl text-sm mb-6 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l-1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    <span class="font-medium">{{ session('error') }}</span>
</div>
@endif

<div class="card space-y-6">
    {{-- Header: search --}}
    <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <form method="GET" class="flex gap-2 w-full">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama / NIS / username / kelas..."
                class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>
            @if(request()->filled('search'))
            <a href="{{ route('admin.users.index') }}"
                class="px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Nama</th>
                    <th class="table-th">NIS</th>
                    <th class="table-th">Username</th>
                    <th class="table-th">Kelas</th>
                    <th class="table-th">Email</th>
                    <th class="table-th">Peminjaman Aktif</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td text-slate-400">{{ $users->firstItem() + $loop->index }}</td>
                    <td class="table-td">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-slate-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="table-td text-slate-600 text-sm font-semibold">{{ $user->nis ?? '-' }}</td>
                    <td class="table-td">
                        <span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded">{{ $user->username }}</span>
                    </td>
                    <td class="table-td text-slate-600 text-sm">{{ $user->kelas ?? '-' }}</td>
                    <td class="table-td">
                        @php
                            $emailDefault = !$user->email || str_ends_with($user->email, '@siswa.sch.id');
                        @endphp
                        @if($emailDefault)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 text-red-600 border border-red-200 px-2 py-0.5 rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l-1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                Belum diisi
                            </span>
                        @else
                            <a href="mailto:{{ $user->email }}" class="text-xs text-blue-600 hover:underline font-medium" title="{{ $user->email }}">
                                {{ Str::limit($user->email, 22) }}
                            </a>
                        @endif
                    </td>
                    <td class="table-td">
                        <span class="{{ $user->aktif_count > 0 ? 'text-amber-600 font-semibold' : 'text-slate-400' }}">
                            {{ $user->aktif_count }} alat sedang dipinjam
                        </span>
                    </td>
                </tr>
                @empty
                                <tr>
                    <td colspan="7" class="table-td text-center text-slate-400 py-10">
                        Tidak ada data akun siswa TKR ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $users->links() }}</div>
    @endif
</div>

@endsection
