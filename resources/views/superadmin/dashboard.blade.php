@extends('superadmin.layouts.app')

@section('content')
<!-- Welcome banner with gradient -->
<div class="bg-gradient-to-r from-violet-600 to-indigo-700 rounded-3xl p-8 mb-8 text-white shadow-lg shadow-indigo-600/10 relative overflow-hidden">
    <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
    <div class="relative z-10 max-w-2xl">
        <h2 class="text-3xl font-extrabold tracking-tight">Selamat Datang, Superadmin!</h2>
        <p class="text-indigo-100/90 mt-2 text-sm leading-relaxed">
            Selamat datang di Control Center SMK Ma'arif Talang. Di sini Anda memiliki kontrol penuh atas verifikasi NIS, registrasi akun siswa, dan manajemen kredensial admin sub-sistem.
        </p>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('error'))
<div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl text-sm mb-8 shadow-sm">
    <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V7a1 1 0 112 0v2a1 1 0 11-2 0zm0 4a1 1 0 102 0 1 1 0 00-2 0z" clip-rule="evenodd"/></svg>
    <span class="font-semibold">{{ session('error') }}</span>
</div>
@endif

<!-- Dynamic Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Akun Siswa -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Akun Siswa</p>
            <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalSiswa }}</h3>
        </div>
    </div>

    <!-- Master NIS Terverifikasi -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="w-14 h-14 bg-violet-50 text-violet-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">NIS Terverifikasi</p>
            <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalSiswaMaster }}</h3>
        </div>
    </div>

    <!-- Total Sirkulasi Bengkel -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a3 3 0 106 0 3 3 0 00-6 0z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sirkulasi Bengkel</p>
            <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalPeminjamanBengkel }}</h3>
        </div>
    </div>

    <!-- Total Sirkulasi Perpus -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sirkulasi Perpus</p>
            <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalPeminjamanPerpus }}</h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Left Column: Quick Check NIS & System Status -->
    <div class="space-y-8">
        <!-- Quick Check NIS Widget -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-base font-bold text-slate-800 mb-2">Cek NIS Cepat</h3>
            <p class="text-xs text-slate-400 mb-4 leading-normal">Ketik nomor NIS untuk memeriksa status registrasi siswa secara instan.</p>
            
            <div class="flex gap-2">
                <input type="text" id="quick-nis-input" placeholder="Contoh: 12345"
                    class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                <button type="button" onclick="quickCheckNis()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 rounded-xl text-xs transition-colors flex items-center justify-center">
                    Cek
                </button>
            </div>
            
            <!-- Result Area -->
            <div id="check-result-container" class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 hidden transition-all">
                <div class="flex items-start gap-3">
                    <div id="result-status-icon" class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"></div>
                    <div class="flex-1 space-y-1">
                        <h4 id="result-name" class="text-sm font-bold text-slate-800"></h4>
                        <p id="result-meta" class="text-xs text-slate-500 font-medium"></p>
                        <p id="result-detail" class="text-xs text-slate-400 leading-normal font-medium mt-1 pt-1.5 border-t border-slate-200/50"></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Center & Right: Chart.js Graphs -->
    <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-800 mb-2">Analisis Distribusi Akun Siswa</h3>
            <p class="text-xs text-slate-400 mb-6">Visualisasi perbandingan jumlah pendaftaran akun siswa aktif per jurusan.</p>
        </div>
        
        <div class="h-64 flex items-center justify-center relative">
            <canvas id="jurusanChart" class="max-h-full"></canvas>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-6 gap-2 mt-6 pt-6 border-t border-slate-100 text-center">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TKJ</p>
                <h4 class="text-base font-black text-indigo-600 mt-0.5">{{ $jurusanDistribution['TKJ'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TKR</p>
                <h4 class="text-base font-black text-rose-500 mt-0.5">{{ $jurusanDistribution['TKR'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">RPL</p>
                <h4 class="text-base font-black text-emerald-600 mt-0.5">{{ $jurusanDistribution['RPL'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">MM</p>
                <h4 class="text-base font-black text-purple-600 mt-0.5">{{ $jurusanDistribution['MM'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">DG</p>
                <h4 class="text-base font-black text-cyan-600 mt-0.5">{{ $jurusanDistribution['DG'] }}</h4>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TEI</p>
                <h4 class="text-base font-black text-amber-500 mt-0.5">{{ $jurusanDistribution['TEI'] }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Quick Actions Card -->
    <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <h3 class="text-base font-bold text-slate-800 mb-6">Menu Fitur Utama</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Action 1: Master NIS -->
            <a href="{{ route('superadmin.siswa.index') }}" class="group border border-slate-100 hover:border-violet-200 bg-slate-50/50 hover:bg-violet-50/20 p-5 rounded-2xl transition-all duration-300 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 bg-violet-100 text-violet-700 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-violet-700 transition-colors">NIS Terverifikasi</h4>
                    <p class="text-[11px] text-slate-400 font-medium leading-normal">Pre-approved NIS master list & Excel CSV upload.</p>
                </div>
            </a>

            <!-- Action 2: Accounts -->
            <a href="{{ route('superadmin.users.index') }}" class="group border border-slate-100 hover:border-indigo-200 bg-slate-50/50 hover:bg-indigo-50/20 p-5 rounded-2xl transition-all duration-300 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-indigo-700 transition-colors">Akun Siswa</h4>
                    <p class="text-[11px] text-slate-400 font-medium leading-normal">Kelola akun, edit biodata, & reset sandi siswa.</p>
                </div>
            </a>

            <!-- Action 3: Admins -->
            <a href="{{ route('superadmin.admins.index') }}" class="group border border-slate-100 hover:border-pink-200 bg-slate-50/50 hover:bg-pink-50/20 p-5 rounded-2xl transition-all duration-300 shadow-sm flex flex-col justify-between">
                <div class="w-10 h-10 bg-pink-100 text-pink-700 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-pink-700 transition-colors">Kelola Admin</h4>
                    <p class="text-[11px] text-slate-400 font-medium leading-normal">Kelola info sandi Admin Bengkel & Perpustakaan.</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Live Activity Logs: Bengkel -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-slate-800">🔧 Aktivitas Bengkel</h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-2 py-1 rounded-full">Live</span>
        </div>

        <!-- Mini Stats -->
        <div class="grid grid-cols-2 gap-2 mb-5">
            <div class="bg-blue-50 rounded-xl p-3 text-center">
                <p id="stat-pinjam-hari-ini" class="text-xl font-black text-blue-600">{{ $bengkelStats['peminjaman_hari_ini'] }}</p>
                <p class="text-[10px] text-blue-500 font-semibold mt-0.5">Pinjam Hari Ini</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-3 text-center">
                <p id="stat-menunggu-persetujuan" class="text-xl font-black text-amber-600">{{ $bengkelStats['menunggu_persetujuan'] }}</p>
                <p class="text-[10px] text-amber-500 font-semibold mt-0.5">Menunggu Konfirmasi</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-3 text-center">
                <p id="stat-sedang-dipinjam" class="text-xl font-black text-emerald-600">{{ $bengkelStats['sedang_dipinjam'] }}</p>
                <p class="text-[10px] text-emerald-500 font-semibold mt-0.5">Sedang Dipinjam</p>
            </div>
            <div class="bg-teal-50 rounded-xl p-3 text-center">
                <p id="stat-kembali-hari-ini" class="text-xl font-black text-teal-600">{{ $bengkelStats['dikembalikan_hari_ini'] }}</p>
                <p class="text-[10px] text-teal-500 font-semibold mt-0.5">Kembali Hari Ini</p>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
            @forelse($bengkelActivities as $activity)
                <div class="flex gap-3 text-xs leading-normal">
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div class="w-2.5 h-2.5 rounded-full {{ $activity['color'] }} mt-1"></div>
                        <div class="w-px flex-1 bg-slate-100 mt-1"></div>
                    </div>
                    <div class="pb-2 flex-1">
                        <p class="text-slate-700 font-medium leading-snug">{{ $activity['title'] }}</p>
                        <span class="text-slate-400 text-[10px] font-bold">{{ $activity['time_diff'] }}</span>
                    </div>
                </div>
            @empty
                <div class="text-xs text-slate-400 text-center py-6">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Belum ada aktivitas bengkel tercatat.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     SECTION: LOG AKTIVITAS GABUNGAN
══════════════════════════════════════════════════════════════════════════ --}}
<div class="mt-10 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800">📋 Log Aktivitas Sistem</h3>
            <p class="text-sm text-slate-500 mt-0.5">Riwayat lengkap aktivitas peminjaman, pengembalian, dan pengelolaan barang secara real-time.</p>
        </div>
        {{-- Filter Kategori --}}
        <div class="flex gap-2 flex-wrap" id="log-filter-btns">
            <button onclick="filterLog('all')" class="log-filter-btn active px-4 py-1.5 rounded-xl text-xs font-bold border transition-all bg-indigo-600 text-white border-indigo-600">Semua</button>
            <button onclick="filterLog('Bengkel')" class="log-filter-btn px-4 py-1.5 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-blue-50 hover:border-blue-300 transition-all">🔧 Bengkel</button>
            <button onclick="filterLog('Perpus')" class="log-filter-btn px-4 py-1.5 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:border-indigo-300 transition-all">📚 Perpus</button>
            <button onclick="filterLog('Barang')" class="log-filter-btn px-4 py-1.5 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-rose-50 hover:border-rose-300 transition-all">⚙️ Alat</button>
            <button onclick="filterLog('Buku')" class="log-filter-btn px-4 py-1.5 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-violet-50 hover:border-violet-300 transition-all">📕 Buku</button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="divide-y divide-slate-50 max-h-[480px] overflow-y-auto" id="activity-log-container">
            @forelse($activityLog as $log)
            <div class="log-item flex items-start gap-4 px-6 py-4 hover:bg-slate-50/60 transition-colors" data-category="{{ $log['category'] }}">
                {{-- Dot + Icon --}}
                <div class="flex-shrink-0 w-9 h-9 rounded-2xl {{ $log['color'] }}/10 border border-current/10 flex items-center justify-center text-lg mt-0.5">
                    <span>{{ $log['icon'] }}</span>
                </div>
                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $log['badge'] }}">{{ $log['category'] }}</span>
                        <p class="text-sm text-slate-700 font-medium leading-snug">{{ $log['title'] }}</p>
                    </div>
                    <span class="text-[11px] text-slate-400 font-semibold mt-0.5 block">{{ $log['time_diff'] }} · {{ $log['time']->format('d M Y, H:i') }}</span>
                </div>
                {{-- Dot indicator --}}
                <div class="w-2 h-2 rounded-full {{ $log['color'] }} flex-shrink-0 mt-2"></div>
            </div>
            @empty
            <div class="px-6 py-16 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm font-medium">Belum ada aktivitas yang tercatat.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     SECTION: STATISTIK PEMINJAMAN REAL-TIME
══════════════════════════════════════════════════════════════════════════ --}}
<div class="mt-10 mb-8" id="statistik-realtime-section">

    {{-- Header dengan indikator live --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h3 class="text-xl font-extrabold text-slate-800">📊 Statistik Peminjaman Real-time</h3>
            <p class="text-sm text-slate-500 mt-0.5">Tren waktu, distribusi jurusan, dan siapa yang sedang meminjam — diperbarui otomatis setiap 30 detik.</p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <span class="flex items-center gap-2 text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Live
            </span>
            <span class="text-xs text-slate-400 font-medium">
                Update: <span id="rt-last-updated" class="font-bold text-slate-600">{{ $lastUpdated }}</span>
            </span>
        </div>
    </div>

    {{-- Row 1: Tren Waktu + Distribusi Jurusan --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">

        {{-- Chart: Tren 7 Hari --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-1">
                <h4 class="text-sm font-bold text-slate-800">📈 Tren Peminjaman 7 Hari Terakhir</h4>
                <div class="flex items-center gap-3 text-[10px] font-bold">
                    <span class="flex items-center gap-1"><span class="w-3 h-1.5 bg-blue-500 rounded-full inline-block"></span> Bengkel</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-1.5 bg-indigo-400 rounded-full inline-block"></span> Perpus</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 mb-4">Perbandingan jumlah transaksi peminjaman harian.</p>
            <div class="h-56 relative">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Chart: Distribusi Jurusan --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-1">
                <h4 class="text-sm font-bold text-slate-800">🏫 Peminjaman per Jurusan</h4>
                <div class="flex items-center gap-3 text-[10px] font-bold">
                    <span class="flex items-center gap-1"><span class="w-3 h-1.5 bg-blue-500 rounded-full inline-block"></span> Bengkel</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-1.5 bg-indigo-400 rounded-full inline-block"></span> Perpus</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 mb-4">Total seluruh peminjaman yang pernah dilakukan per jurusan.</p>
            <div class="h-56 relative">
                <canvas id="jurusanBarChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Row 2: Siapa yang Sedang Meminjam --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

        {{-- Aktif Bengkel --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-slate-50 flex items-center justify-between">
                <div>
                    <h4 class="font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 bg-blue-500 text-white rounded-xl flex items-center justify-center text-sm">⚙️</span>
                        Sedang Meminjam — Bengkel
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">Siswa yang saat ini memiliki peminjaman alat aktif</p>
                </div>
                <span id="rt-count-bengkel" class="text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                    {{ $aktifBengkel->count() }} siswa
                </span>
            </div>
            <div id="rt-aktif-bengkel-list" class="divide-y divide-slate-50 max-h-80 overflow-y-auto">
                @forelse($aktifBengkel as $p)
                <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-slate-50/50 transition-colors">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($p['nama'], 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $p['nama'] }}</p>
                        <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                            <span class="text-[10px] font-bold bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded">{{ $p['jurusan'] }}</span>
                            <span class="text-[10px] text-slate-400">{{ $p['kelas'] }}</span>
                            <span class="text-[10px] text-slate-500 font-medium truncate max-w-[120px]">⚙️ {{ $p['item'] }}</span>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        @if($p['status'] === 'menunggu_konfirmasi')
                            <span class="text-[10px] bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full">Proses Kembali</span>
                        @else
                            <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full">Dipinjam</span>
                        @endif
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $p['tgl'] }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-slate-400 text-sm">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Tidak ada peminjaman alat aktif saat ini.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Aktif Perpus --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-slate-50 flex items-center justify-between">
                <div>
                    <h4 class="font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 bg-indigo-500 text-white rounded-xl flex items-center justify-center text-sm">📚</span>
                        Sedang Meminjam — Perpustakaan
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">Anggota yang saat ini memiliki buku belum dikembalikan</p>
                </div>
                <span id="rt-count-perpus" class="text-xs font-bold bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">
                    {{ $aktifPerpus->count() }} siswa
                </span>
            </div>
            <div id="rt-aktif-perpus-list" class="divide-y divide-slate-50 max-h-80 overflow-y-auto">
                @forelse($aktifPerpus as $p)
                <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-slate-50/50 transition-colors">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($p['nama'], 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $p['nama'] }}</p>
                        <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                            <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded">{{ $p['jurusan'] }}</span>
                            <span class="text-[10px] text-slate-400">{{ $p['kelas'] }}</span>
                            <span class="text-[10px] text-slate-500 font-medium truncate max-w-[120px]">📖 {{ $p['item'] }}</span>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        @if($p['status'] === 'menunggu_konfirmasi')
                            <span class="text-[10px] bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full">Proses Kembali</span>
                        @else
                            <span class="text-[10px] bg-indigo-100 text-indigo-700 font-bold px-2 py-0.5 rounded-full">Dipinjam</span>
                        @endif
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $p['tgl'] }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-slate-400 text-sm">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Tidak ada peminjaman buku aktif saat ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     SECTION: RANKING SISWA TERAKTIF
══════════════════════════════════════════════════════════════════════════ --}}

<div class="mb-10">
    <div class="mb-5">
        <h3 class="text-xl font-extrabold text-slate-800">🏆 Siswa Teraktif</h3>
        <p class="text-sm text-slate-500 mt-0.5">Peringkat siswa berdasarkan jumlah peminjaman yang telah dikembalikan (pinjam + kembalikan = aktif). Digunakan sebagai dasar penghargaan siswa teraktif.</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

        {{-- ── Ranking Bengkel ───────────────────────────────────────────── --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-slate-50 flex items-center justify-between">
                <div>
                    <h4 class="font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 bg-blue-500 text-white rounded-xl flex items-center justify-center text-sm">⚙️</span>
                        Teraktif — Alat Bengkel
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">Berdasarkan total pengembalian alat yang berhasil diselesaikan</p>
                </div>
                <span class="text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1 rounded-full">Top {{ $topSiswaBengkel->count() }}</span>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($topSiswaBengkel as $siswa)
                <div class="px-5 py-4 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                    {{-- Rank Medal --}}
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl font-black text-sm
                        @if($siswa['rank'] == 1) bg-amber-400 text-white shadow-md shadow-amber-200
                        @elseif($siswa['rank'] == 2) bg-slate-300 text-white
                        @elseif($siswa['rank'] == 3) bg-orange-400 text-white
                        @else bg-slate-100 text-slate-500 @endif">
                        @if($siswa['rank'] <= 3)
                            {{ ['🥇','🥈','🥉'][$siswa['rank']-1] }}
                        @else
                            {{ $siswa['rank'] }}
                        @endif
                    </div>

                    {{-- Avatar & Info --}}
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-sm
                            @if($siswa['rank'] == 1) bg-amber-100 text-amber-700
                            @elseif($siswa['rank'] == 2) bg-slate-200 text-slate-600
                            @elseif($siswa['rank'] == 3) bg-orange-100 text-orange-700
                            @else bg-indigo-100 text-indigo-700 @endif">
                            {{ strtoupper(substr($siswa['name'], 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $siswa['name'] }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                <span class="text-[10px] font-bold bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded">{{ $siswa['jurusan'] }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $siswa['kelas'] }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progress & Count --}}
                    <div class="text-right flex-shrink-0 min-w-[80px]">
                        <p class="text-base font-black text-blue-600">{{ $siswa['total_pinjam'] }}</p>
                        <p class="text-[10px] text-slate-400 font-semibold">kali pinjam</p>
                        <div class="mt-1.5 w-full bg-slate-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 transition-all"
                                style="width: {{ round(($siswa['total_pinjam'] / $maxBengkel) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-slate-400 text-sm">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Belum ada data peminjaman bengkel yang selesai.
                </div>
                @endforelse
            </div>
        </div>

        {{-- ── Ranking Perpustakaan ──────────────────────────────────────── --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-slate-50 flex items-center justify-between">
                <div>
                    <h4 class="font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 bg-indigo-500 text-white rounded-xl flex items-center justify-center text-sm">📚</span>
                        Teraktif — Perpustakaan
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">Berdasarkan total pengembalian buku yang berhasil diselesaikan</p>
                </div>
                <span class="text-xs font-bold bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">Top {{ $topSiswaPerpus->count() }}</span>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($topSiswaPerpus as $siswa)
                <div class="px-5 py-4 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                    {{-- Rank Medal --}}
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl font-black text-sm
                        @if($siswa['rank'] == 1) bg-amber-400 text-white shadow-md shadow-amber-200
                        @elseif($siswa['rank'] == 2) bg-slate-300 text-white
                        @elseif($siswa['rank'] == 3) bg-orange-400 text-white
                        @else bg-slate-100 text-slate-500 @endif">
                        @if($siswa['rank'] <= 3)
                            {{ ['🥇','🥈','🥉'][$siswa['rank']-1] }}
                        @else
                            {{ $siswa['rank'] }}
                        @endif
                    </div>

                    {{-- Avatar & Info --}}
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-sm
                            @if($siswa['rank'] == 1) bg-amber-100 text-amber-700
                            @elseif($siswa['rank'] == 2) bg-slate-200 text-slate-600
                            @elseif($siswa['rank'] == 3) bg-orange-100 text-orange-700
                            @else bg-indigo-100 text-indigo-700 @endif">
                            {{ strtoupper(substr($siswa['name'], 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $siswa['name'] }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded">{{ $siswa['jurusan'] }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $siswa['kelas'] }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progress & Count --}}
                    <div class="text-right flex-shrink-0 min-w-[80px]">
                        <p class="text-base font-black text-indigo-600">{{ $siswa['total_pinjam'] }}</p>
                        <p class="text-[10px] text-slate-400 font-semibold">kali pinjam</p>
                        <div class="mt-1.5 w-full bg-slate-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full bg-gradient-to-r from-indigo-400 to-indigo-600 transition-all"
                                style="width: {{ round(($siswa['total_pinjam'] / $maxPerpus) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-slate-400 text-sm">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Belum ada data peminjaman perpustakaan yang selesai.
                </div>
                @endforelse
            </div>
        </div>

    </div>{{-- end grid ranking --}}
</div>

@endsection


@push('scripts')
<!-- Add Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Check NIS AJax function
function quickCheckNis() {
    const nis = document.getElementById('quick-nis-input').value.trim();
    const resultContainer = document.getElementById('check-result-container');
    const nameEl = document.getElementById('result-name');
    const metaEl = document.getElementById('result-meta');
    const detailEl = document.getElementById('result-detail');
    const iconEl = document.getElementById('result-status-icon');
    
    if (!nis) {
        Swal.fire({
            title: 'Input Kosong!',
            text: 'Masukkan nomor NIS terlebih dahulu.',
            icon: 'warning',
            confirmButtonColor: '#4f46e5'
        });
        return;
    }
    
    resultContainer.classList.add('hidden');
    
    fetch(`{{ url('/superadmin/api/check-nis') }}/${nis}`)
        .then(response => response.json())
        .then(data => {
            resultContainer.classList.remove('hidden');
            
            if (data.status === 'not_found') {
                iconEl.className = 'w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0';
                iconEl.innerHTML = '<svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>';
                nameEl.textContent = 'Data Tidak Ditemukan';
                metaEl.textContent = `NIS: ${nis}`;
                detailEl.innerHTML = `<span class="text-rose-600 font-semibold">${data.message}</span>`;
            } else if (data.status === 'registered') {
                iconEl.className = 'w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0';
                iconEl.innerHTML = '<svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
                nameEl.textContent = data.name;
                metaEl.textContent = `NIS: ${nis} | Kelas: ${data.kelas} | Jurusan: ${data.jurusan}`;
                detailEl.innerHTML = `<span class="text-emerald-600 font-bold">● ${data.message}</span><br>Username: <code class="font-mono bg-white border border-slate-100 rounded px-1.5 text-slate-700">${data.username}</code>`;
            } else {
                iconEl.className = 'w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0';
                iconEl.innerHTML = '<svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                nameEl.textContent = data.name;
                metaEl.textContent = `NIS: ${nis} | Kelas: ${data.kelas} | Jurusan: ${data.jurusan}`;
                detailEl.innerHTML = `<span class="text-amber-600 font-bold">● ${data.message}</span><br><a href="{{ route('superadmin.users.create') }}?nis=${nis}" class="text-indigo-600 hover:underline font-bold mt-1 inline-block">Daftarkan Akun Sekarang &rarr;</a>`;
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                title: 'Error!',
                text: 'Gagal menghubungi server.',
                icon: 'error'
            });
        });
}

// Chart.js render
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('jurusanChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['TKJ', 'TKR', 'RPL', 'MM', 'DG', 'TEI'],
            datasets: [{
                data: [
                    {{ $jurusanDistribution['TKJ'] }},
                    {{ $jurusanDistribution['TKR'] }},
                    {{ $jurusanDistribution['RPL'] }},
                    {{ $jurusanDistribution['MM'] }},
                    {{ $jurusanDistribution['DG'] }},
                    {{ $jurusanDistribution['TEI'] }}
                ],
                backgroundColor: [
                    '#4f46e5', // indigo (TKJ)
                    '#f43f5e', // rose (TKR)
                    '#10b981', // emerald (RPL)
                    '#9333ea', // purple (MM)
                    '#0891b2', // cyan (DG)
                    '#d97706'  // amber/tei (TEI)
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            family: 'Plus Jakarta Sans',
                            size: 11,
                            weight: '600'
                        },
                        padding: 20
                    }
                }
            },
            cutout: '65%'
        }
    });
});
</script>

<script>
// ── Filter Log Aktivitas ──────────────────────────────────────────────────────
function filterLog(category) {
    const items    = document.querySelectorAll('.log-item');
    const buttons  = document.querySelectorAll('.log-filter-btn');

    // Toggle tombol aktif
    buttons.forEach(btn => {
        btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
        btn.classList.add('border-slate-200', 'text-slate-600');
    });
    event.target.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
    event.target.classList.remove('border-slate-200', 'text-slate-600');

    // Tampilkan / sembunyikan item
    items.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.style.display = '';
            item.style.opacity = '0';
            setTimeout(() => { item.style.opacity = '1'; item.style.transition = 'opacity 0.2s'; }, 10);
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

<script>
// ── Real-time Statistik Charts & Polling ──────────────────────────────────────

// Initial data from server (PHP → JS)
const initialTrendDates   = @json($trendDates);
const initialTrendBengkel = @json($trendBengkel);
const initialTrendPerpus  = @json($trendPerpus);
const initialJurusanKeys  = ['TKR','TKJ','RPL','MM','DG','TEI'];
const initialBengkelJurus = @json(array_values($peminjamanBengkelPerJurusan));
const initialPerpusJurus  = @json(array_values($peminjamanPerpusPerJurusan));

const jurusanColors = {
    TKR: '#f43f5e',
    TKJ: '#4f46e5',
    RPL: '#10b981',
    MM:  '#9333ea',
    DG:  '#0891b2',
    TEI: '#d97706',
};

// ── 1. Tren 7-Hari Line Chart ─────────────────────────────────────────────────
let trendChart;
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('trendChart').getContext('2d');
    trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: initialTrendDates,
            datasets: [
                {
                    label: 'Bengkel',
                    data: initialTrendBengkel,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Perpustakaan',
                    data: initialTrendPerpus,
                    borderColor: '#818cf8',
                    backgroundColor: 'rgba(129,140,248,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#818cf8',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 12, weight: '700' },
                    bodyFont:  { size: 11 },
                    padding: 10,
                    cornerRadius: 10,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: '600' }, color: '#94a3b8' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { size: 10 }, color: '#94a3b8',
                        precision: 0,
                        stepSize: 1,
                    }
                }
            }
        }
    });
});

// ── 2. Peminjaman per Jurusan Grouped Bar Chart ───────────────────────────────
let jurusanBarChart;
document.addEventListener('DOMContentLoaded', () => {
    const ctx2 = document.getElementById('jurusanBarChart').getContext('2d');
    jurusanBarChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: initialJurusanKeys,
            datasets: [
                {
                    label: 'Bengkel',
                    data: initialBengkelJurus,
                    backgroundColor: 'rgba(59,130,246,0.75)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Perpustakaan',
                    data: initialPerpusJurus,
                    backgroundColor: 'rgba(99,102,241,0.75)',
                    borderRadius: 6,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 12, weight: '700' },
                    bodyFont:  { size: 11 },
                    padding: 10,
                    cornerRadius: 10,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, weight: '700' }, color: '#64748b' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { size: 10 }, color: '#94a3b8',
                        precision: 0,
                        stepSize: 1,
                    }
                }
            }
        }
    });
});

// ── 3. Real-time Polling (setiap 30 detik) ────────────────────────────────────
const RT_API_URL   = '{{ route("superadmin.statistik.realtime") }}';
const RT_INTERVAL  = 30000; // 30 detik

function buildAktifRow(p, type) {
    const isBengkel = (type === 'bengkel');
    const avatarCls = isBengkel
        ? 'bg-blue-100 text-blue-700'
        : 'bg-indigo-100 text-indigo-700';
    const badgeCls = isBengkel
        ? 'bg-blue-50 text-blue-600'
        : 'bg-indigo-50 text-indigo-600';
    const emoji = isBengkel ? '⚙️' : '📖';

    let statusBadge = '';
    if (p.status === 'menunggu_konfirmasi') {
        statusBadge = `<span class="text-[10px] bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full">Proses Kembali</span>`;
    } else {
        const cls = isBengkel ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700';
        statusBadge = `<span class="text-[10px] ${cls} font-bold px-2 py-0.5 rounded-full">Dipinjam</span>`;
    }

    const initial = (p.nama && p.nama.length > 0) ? p.nama[0].toUpperCase() : '?';

    return `
    <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-slate-50/50 transition-colors">
        <div class="w-9 h-9 rounded-full ${avatarCls} flex items-center justify-center font-bold text-sm flex-shrink-0">
            ${initial}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-slate-800 truncate">${p.nama}</p>
            <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                <span class="text-[10px] font-bold ${badgeCls} px-1.5 py-0.5 rounded">${p.jurusan}</span>
                <span class="text-[10px] text-slate-400">${p.kelas}</span>
                <span class="text-[10px] text-slate-500 font-medium truncate max-w-[120px]">${emoji} ${p.item}</span>
            </div>
        </div>
        <div class="text-right flex-shrink-0">
            ${statusBadge}
            <p class="text-[10px] text-slate-400 mt-0.5">${p.tgl}</p>
        </div>
    </div>`;
}

function buildEmptyRow(type) {
    const isBengkel = (type === 'bengkel');
    const msg = isBengkel
        ? 'Tidak ada peminjaman alat aktif saat ini.'
        : 'Tidak ada peminjaman buku aktif saat ini.';
    return `
    <div class="px-6 py-12 text-center text-slate-400 text-sm">
        <svg class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        ${msg}
    </div>`;
}

function refreshRealtimeStats() {
    fetch(RT_API_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            // Update trend chart
            if (trendChart) {
                trendChart.data.labels = data.trend_dates;
                trendChart.data.datasets[0].data = data.trend_bengkel;
                trendChart.data.datasets[1].data = data.trend_perpus;
                trendChart.update('active');
            }

            // Update jurusan bar chart
            if (jurusanBarChart) {
                const keys = ['TKR','TKJ','RPL','MM','DG','TEI'];
                jurusanBarChart.data.datasets[0].data = keys.map(k => data.bengkel_jurusan[k] ?? 0);
                jurusanBarChart.data.datasets[1].data = keys.map(k => data.perpus_jurusan[k] ?? 0);
                jurusanBarChart.update('active');
            }

            // Update aktif bengkel list
            const bengkelList = document.getElementById('rt-aktif-bengkel-list');
            const countBengkel = document.getElementById('rt-count-bengkel');
            if (bengkelList && data.aktif_bengkel) {
                const rows = data.aktif_bengkel;
                countBengkel.textContent = rows.length + ' siswa';
                if (rows.length === 0) {
                    bengkelList.innerHTML = buildEmptyRow('bengkel');
                } else {
                    bengkelList.innerHTML = rows.map(p => buildAktifRow(p, 'bengkel')).join('');
                }
            }

            // Update aktif perpus list
            const perpusList = document.getElementById('rt-aktif-perpus-list');
            const countPerpus = document.getElementById('rt-count-perpus');
            if (perpusList && data.aktif_perpus) {
                const rows = data.aktif_perpus;
                countPerpus.textContent = rows.length + ' siswa';
                if (rows.length === 0) {
                    perpusList.innerHTML = buildEmptyRow('perpus');
                } else {
                    perpusList.innerHTML = rows.map(p => buildAktifRow(p, 'perpus')).join('');
                }
            }

            // Update mini stats bengkel
            if (data.bengkel_stats) {
                const s = data.bengkel_stats;
                const elPinjam   = document.getElementById('stat-pinjam-hari-ini');
                const elMenunggu = document.getElementById('stat-menunggu-persetujuan');
                const elDipinjam = document.getElementById('stat-sedang-dipinjam');
                const elKembali  = document.getElementById('stat-kembali-hari-ini');
                if (elPinjam)   elPinjam.textContent   = s.peminjaman_hari_ini   ?? 0;
                if (elMenunggu) elMenunggu.textContent = s.menunggu_persetujuan  ?? 0;
                if (elDipinjam) elDipinjam.textContent = s.sedang_dipinjam       ?? 0;
                if (elKembali)  elKembali.textContent  = s.dikembalikan_hari_ini ?? 0;
            }

            // Update last-updated timestamp
            const tsEl = document.getElementById('rt-last-updated');
            if (tsEl) tsEl.textContent = data.last_updated;
        })
        .catch(err => {
            console.warn('[RT Stats] Gagal memuat data:', err);
        });
}

// Mulai polling setelah 30 detik pertama
document.addEventListener('DOMContentLoaded', () => {
    setInterval(refreshRealtimeStats, RT_INTERVAL);
});
</script>
@endpush

