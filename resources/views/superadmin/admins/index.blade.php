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
        <h2 class="text-3xl font-extrabold text-slate-800 font-display">Manajemen Akun Admin</h2>
        <p class="text-slate-500 mt-1">Kelola data dan profil untuk Admin Bengkel dan Admin Perpustakaan.</p>
    </div>
    <div>
        <a href="{{ route('superadmin.admins.create') }}" class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Admin
        </a>
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

@if($errors->any())
<div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl text-sm mb-6 shadow-sm">
    <p class="font-bold mb-1.5">Terjadi kesalahan input:</p>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    @forelse($admins as $admin)
        @php
            $isPerpus = $admin->role === 'admin_perpus';
            $bgBadge = $isPerpus ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200';
            $avatarColor = $isPerpus ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700';
            $roleText = $isPerpus ? 'Admin Perpustakaan (SIPB)' : 'Admin Bengkel (SIPAB)';
        @endphp
        
        <div class="bg-white rounded-3xl p-6 border border-slate-150 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ $bgBadge }}">
                        {{ $roleText }}
                    </span>
                    <span class="text-slate-300 font-mono text-xs">ID: #{{ $admin->id }}</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl {{ $avatarColor }} flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $admin->name }}</h3>
                        <p class="text-sm text-slate-500 font-mono">Username: {{ $admin->username }}</p>
                    </div>
                </div>
                
                <div class="pt-2 border-t border-slate-100 space-y-1">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Email Terhubung</p>
                    <p class="text-sm text-slate-700">{{ $admin->email ?? '-' }}</p>
                </div>
            </div>
            
            <div class="pt-6 mt-6 border-t border-slate-100 flex gap-2">
                <a href="{{ route('superadmin.admins.edit', $admin) }}" class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold py-2 px-4 rounded-xl text-sm transition-all border border-slate-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit
                </a>
                <form action="{{ route('superadmin.admins.destroy', $admin) }}" method="POST" class="flex-1 delete-admin-form" data-name="{{ $admin->name }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDeleteAdmin(this)" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold py-2 px-4 rounded-xl text-sm transition-all border border-rose-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-3xl p-8 text-center text-slate-400 border border-dashed border-slate-200 col-span-2">
            Belum ada akun admin yang dibuat.
        </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
    function confirmDeleteAdmin(btn) {
        const form = btn.closest('.delete-admin-form');
        const adminName = form.getAttribute('data-name');

        Swal.fire({
            title: 'Hapus Akun Admin?',
            html: `Akun <strong>${adminName}</strong> akan dihapus secara permanen.<br><span style="font-size:0.85rem;color:#64748b">Tindakan ini tidak dapat dibatalkan.</span>`,
            icon: 'warning',
            iconColor: '#f43f5e',
            showCancelButton: true,
            confirmButtonText: '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl shadow-2xl',
                title: 'text-slate-800 font-extrabold',
                confirmButton: 'rounded-xl font-bold px-5 py-2.5 text-sm',
                cancelButton: 'rounded-xl font-bold px-5 py-2.5 text-sm',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endpush
