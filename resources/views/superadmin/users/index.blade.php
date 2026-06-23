@extends('superadmin.layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Dashboard
    </a>
</div>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-800 font-display">Manajemen Akun Siswa</h2>
        <p class="text-slate-500 mt-1">Daftar akun login siswa yang aktif menggunakan sistem Perpustakaan dan Bengkel.</p>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl text-sm mb-6 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl text-sm mb-6 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V7a1 1 0 112 0v2a1 1 0 11-2 0zm0 4a1 1 0 102 0 1 1 0 00-2 0z" clip-rule="evenodd"/></svg>
    <span class="font-medium">{{ session('error') }}</span>
</div>
@endif

<!-- Stats Row -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Akun</p>
        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-l-4 border-l-rose-500">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jurusan TKR</p>
        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['tkr'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-l-4 border-l-indigo-500">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jurusan TKJ</p>
        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['tkj'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-l-4 border-l-emerald-500">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jurusan RPL</p>
        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['rpl'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-l-4 border-l-purple-500">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jurusan MM</p>
        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['mm'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-l-4 border-l-cyan-500">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jurusan DG</p>
        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['dg'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 border-l-4 border-l-amber-500">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jurusan TEI</p>
        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['tei'] }}</h3>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <!-- Filter & Search Header -->
    <div class="p-6 border-b border-slate-100">
        <form method="GET" action="{{ route('superadmin.users.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan Nama, NIS, Username, Kelas..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
            </div>
            <div class="w-full md:w-48">
                <select name="jurusan" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusans as $jur)
                        <option value="{{ $jur }}" {{ request('jurusan') == $jur ? 'selected' : '' }}>{{ $jur }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors flex-1 md:flex-initial">
                    Filter
                </button>
                @if(request('search') || request('jurusan'))
                    <a href="{{ route('superadmin.users.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">NIS</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jurusan</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $i => $u)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-400 text-xs font-medium">{{ $users->firstItem() + $i }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-slate-800 text-sm">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-mono text-sm font-semibold">{{ $u->nis ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded">{{ $u->username }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                {{ $u->jurusan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 text-sm">{{ $u->kelas ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500 text-xs">{{ $u->email ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <div class="flex items-center justify-center gap-1.5">
                                <form action="{{ route('superadmin.users.reset_password', $u) }}" method="POST" class="inline-block reset-form" data-name="{{ $u->name }}">
                                    @csrf
                                    <button type="button" onclick="confirmReset(this)" class="bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-xs border border-blue-200 px-2.5 py-1.5 rounded-lg transition-all">
                                        Reset PW
                                    </button>
                                </form>
                                <a href="{{ route('superadmin.users.edit', $u) }}" class="bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-xs border border-amber-200 px-2.5 py-1.5 rounded-lg transition-all">
                                    Edit
                                </a>
                                <form action="{{ route('superadmin.users.destroy', $u) }}" method="POST" class="inline-block delete-form" data-name="{{ $u->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs border border-rose-200 px-2.5 py-1.5 rounded-lg transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-sm">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>Belum ada akun siswa yang terdaftar.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(button) {
    const form = button.closest('form');
    const name = form.getAttribute('data-name');
    
    Swal.fire({
        title: 'Hapus Akun Siswa?',
        text: `Apakah Anda yakin ingin menghapus akun ${name}? Data akun akan hilang secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold shadow-md',
            cancelButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

function confirmReset(button) {
    const form = button.closest('form');
    const name = form.getAttribute('data-name');
    
    Swal.fire({
        title: 'Reset Password?',
        text: `Password akun ${name} akan dikembalikan menjadi default (NIS siswa).`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Reset!',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold shadow-md',
            cancelButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endpush
