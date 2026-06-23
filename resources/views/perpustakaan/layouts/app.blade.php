<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'SIPB') — Sistem Informasi Perpustakaan</title>
    <meta name="description" content="Sistem Informasi Perpustakaan Sekolah" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        perpus: {
                            50:  '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-link {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1rem; color: #86efac;
            transition: all 0.2s; font-size: 0.875rem; font-weight: 500;
            white-space: nowrap; border-bottom: 2px solid transparent;
            text-decoration: none;
        }
        .nav-link:hover { color: #fff; border-bottom-color: #4ade80; background: rgba(255,255,255,0.08); }
        .nav-link.active { color: #fff; border-bottom-color: #22c55e; background: rgba(22,163,74,0.25); }
        .card {
            background: #fff; border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.07);
            border: 1px solid #f1f5f9;
        }
        .btn-perpus {
            background: #16a34a; color: #fff; padding: 0.5rem 1.25rem;
            border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;
            border: none; cursor: pointer; text-decoration: none;
        }
        .btn-perpus:hover { background: #15803d; transform: translateY(-1px); }
        .btn-danger {
            background: #ef4444; color: #fff; padding: 0.375rem 0.75rem;
            border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.375rem;
            border: none; cursor: pointer; text-decoration: none;
        }
        .btn-danger:hover { background: #dc2626; }
        .btn-warning {
            background: #f59e0b; color: #fff; padding: 0.375rem 0.75rem;
            border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.375rem;
            border: none; cursor: pointer; text-decoration: none;
        }
        .btn-warning:hover { background: #d97706; }
        .btn-info {
            background: #3b82f6; color: #fff; padding: 0.375rem 0.75rem;
            border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.375rem;
            border: none; cursor: pointer; text-decoration: none;
        }
        .btn-info:hover { background: #2563eb; }
        /* Badges */
        .badge-dipinjam   { display:inline-flex;align-items:center;padding:0.125rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;background:#fef3c7;color:#92400e; }
        .badge-menunggu   { display:inline-flex;align-items:center;padding:0.125rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;background:#e0f2fe;color:#0369a1; }
        .badge-dikembalikan { display:inline-flex;align-items:center;padding:0.125rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;background:#d1fae5;color:#065f46; }
        .badge-terlambat  { display:inline-flex;align-items:center;padding:0.125rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;background:#fee2e2;color:#991b1b; }
        .table-th { padding:0.75rem 1rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em; }
        .table-td { padding:0.75rem 1rem;font-size:0.875rem;color:#334155; }
        .no-scrollbar::-webkit-scrollbar { display:none; }
        .no-scrollbar { -ms-overflow-style:none;scrollbar-width:none; }
        /* Stat card */
        .stat-card {
            background: white; border-radius: 1rem;
            padding: 1.5rem; border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">


<!-- ── Layout Container ── -->
<div class="flex min-h-screen">
    @if(auth()->check() && in_array(auth()->user()->role, ['admin_perpus', 'superadmin']))
    <!-- ── Sidebar (Admin Perpus) ── -->
    <aside class="w-64 bg-green-900 text-green-100 flex-shrink-0 hidden lg:flex flex-col sticky top-0 h-screen shadow-2xl z-50">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-green-800 flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center shadow-lg shadow-green-950/40">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg tracking-tight leading-none">SIPB</h1>
                <p class="text-green-400 text-xs mt-1">Admin Perpus</p>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">
            <p class="text-[10px] font-bold text-green-500 uppercase tracking-widest px-3 mb-2">Main Menu</p>
            <a href="{{ route('perpustakaan.admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('perpustakaan.admin.dashboard') ? 'bg-green-600 text-white shadow-lg shadow-green-950/30' : 'hover:bg-green-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
            <a href="{{ route('perpustakaan.admin.buku.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('perpustakaan.admin.buku*') ? 'bg-green-600 text-white shadow-lg shadow-green-950/30' : 'hover:bg-green-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span class="font-medium text-sm">Data Buku</span>
            </a>
            <a href="{{ route('perpustakaan.admin.peminjaman.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('perpustakaan.admin.peminjaman*') ? 'bg-green-600 text-white shadow-lg shadow-green-950/30' : 'hover:bg-green-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="font-medium text-sm">Peminjaman</span>
            </a>
            <a href="{{ route('perpustakaan.admin.pengembalian.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('perpustakaan.admin.pengembalian*') ? 'bg-green-600 text-white shadow-lg shadow-green-950/30' : 'hover:bg-green-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                <span class="font-medium text-sm">Pengembalian</span>
            </a>

            <div class="pt-4 mt-4 border-t border-green-800">
                <p class="text-[10px] font-bold text-green-500 uppercase tracking-widest px-3 mb-2">Management</p>
                <a href="{{ route('perpustakaan.admin.laporan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('perpustakaan.admin.laporan*') ? 'bg-green-600 text-white shadow-lg shadow-green-950/30' : 'hover:bg-green-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="font-medium text-sm">Laporan</span>
                </a>
                <a href="{{ route('perpustakaan.admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('perpustakaan.admin.users*') ? 'bg-green-600 text-white shadow-lg shadow-green-950/30' : 'hover:bg-green-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-medium text-sm">Data Anggota</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-green-800 bg-green-900/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-green-300 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="font-semibold text-sm">Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    @endif

    <!-- ── Main Area ── -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">
        <!-- Top Navbar -->
        <header class="bg-white border-b border-slate-200 h-16 sticky top-0 z-50 flex items-center shadow-sm px-4 lg:px-8">
            <div class="flex items-center justify-between w-full">
                <!-- Mobile Logo -->
                <div class="flex lg:hidden items-center gap-3">
                     <div class="w-8 h-8 bg-green-600 rounded flex items-center justify-center text-white">L</div>
                     <span class="font-bold text-slate-800">SIPB</span>
                </div>

                <!-- Breadcrumbs/Page Title -->
                <div class="hidden lg:flex flex-col">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Library / SMK Ma'arif Talang</p>
                    <h2 class="text-sm font-bold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-2 bg-green-50 text-green-700 px-3 py-1.5 rounded-full text-[11px] font-bold border border-green-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ now()->translatedFormat('d M Y') }}
                    </div>
                    
                    @if(auth()->check())
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-slate-800 text-xs font-bold leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-slate-500 text-[10px] mt-1 capitalize">{{ auth()->user()->role === 'admin_perpus' ? 'Admin' : 'Siswa' }} Perpus</p>
                        </div>
                        <div class="w-9 h-9 bg-green-50 text-green-600 border border-green-100 rounded-full flex items-center justify-center text-xs font-bold shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>

                        <!-- Tombol Ganti Password -->
                        <a href="{{ route('password.change', ['from' => 'perpus']) }}" title="Ganti Password" class="p-2 ml-2 text-slate-400 hover:text-green-500 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </a>

                        @if(auth()->user()->role === 'siswa')
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-red-500 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </header>

        <!-- Secondary Nav (For Siswa Perpus) -->
        @if(auth()->check() && auth()->user()->role === 'siswa')
        <div class="bg-green-900 overflow-x-auto no-scrollbar lg:px-8 sticky top-16 z-40">
            <div class="flex">
                <a href="{{ route('perpustakaan.siswa.dashboard') }}" class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition-all {{ request()->routeIs('perpustakaan.siswa.dashboard') ? 'text-white border-b-2 border-green-500 bg-green-500/10' : 'text-green-300 hover:text-white' }}">
                    Dashboard
                </a>
                <a href="{{ route('perpustakaan.siswa.buku.index') }}" class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition-all {{ request()->routeIs('perpustakaan.siswa.buku*') ? 'text-white border-b-2 border-green-500 bg-green-500/10' : 'text-green-300 hover:text-white' }}">
                    Katalog Buku
                </a>
                <a href="{{ route('perpustakaan.siswa.peminjaman.create') }}" class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition-all {{ request()->routeIs('perpustakaan.siswa.peminjaman.create') ? 'text-white border-b-2 border-green-500 bg-green-500/10' : 'text-green-300 hover:text-white' }}">
                    Pinjam Buku
                    @if(session()->has('cart_perpus') && count(session('cart_perpus')) > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ml-1">{{ count(session('cart_perpus')) }}</span>
                    @endif
                </a>
                <a href="{{ route('perpustakaan.siswa.riwayat') }}" class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition-all {{ request()->routeIs('perpustakaan.siswa.riwayat') ? 'text-white border-b-2 border-green-500 bg-green-500/10' : 'text-green-300 hover:text-white' }}">
                    Riwayat
                </a>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto custom-scrollbar" id="main-content">
            <!-- Pull to Refresh Indicator -->
            <div id="pull-refresh" class="hidden flex items-center justify-center gap-2 py-4 bg-green-50 border-b border-green-100 text-green-600 text-sm font-semibold transition-all duration-300">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <span id="pull-refresh-text">Memuat data terbaru...</span>
            </div>
            <!-- Content Header (Mobile) -->
            <div class="bg-white border-b border-slate-200 lg:hidden">
                <div class="px-4 py-5">
                    <h1 class="text-xl font-extrabold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 mt-1">@yield('page-subtitle', '')</p>
                </div>
            </div>

            <!-- Content Body -->
            <div class="p-4 lg:p-8 max-w-7xl">
                @if(session('success'))
                <div id="alert-success" class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm mb-6 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                @endif
                @if(session('error'))
                <div id="alert-error" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm mb-6 shadow-sm">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414-1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm mb-6 shadow-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- ── Footer Watermark ── -->
        <footer class="bg-green-950 border-t border-green-900 px-4 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 py-4 max-w-7xl">
                <!-- Kiri: Logo + Nama Kampus -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg overflow-hidden border border-green-800 flex-shrink-0 bg-green-900 flex items-center justify-center">
                        <img src="{{ asset('images/logo_kampus.jpg') }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             alt="Logo Universitas Harkat Negeri"
                             class="w-full h-full object-contain">
                        <span style="display:none" class="w-full h-full bg-green-700 flex items-center justify-center text-white text-[10px] font-extrabold rounded-lg">UHN</span>
                    </div>
                    <div>
                        <p class="text-green-100 text-xs font-bold leading-tight">Universitas Harkat Negeri</p>
                        <p class="text-green-600 text-[10px] font-medium mt-0.5">Sistem Informasi Perpustakaan</p>
                    </div>
                </div>

                <!-- Kanan: Nama + Tugas Akhir -->
                <div class="flex items-center gap-3">
                    <span class="text-green-800 text-[10px] hidden sm:inline">&copy; {{ date('Y') }}</span>
                    <div class="text-right">
                        <p class="text-green-200 text-xs font-bold">Fadlian Yusup</p>
                        <span class="inline-flex items-center gap-1 bg-green-800/50 border border-green-700/40 text-green-400 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 21H3a12.083 12.083 0 012.84-10.422L12 14z"/></svg>
                            Tugas Akhir 2026
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Auto-dismiss alerts
    setTimeout(() => {
        ['alert-success','alert-error'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.style.transition='opacity 0.5s'; el.style.opacity='0'; setTimeout(()=>el.remove(),500); }
        });
    }, 4000);

    // Delete confirmation
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const msg = this.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?';
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: msg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(r => { if (r.isConfirmed) this.submit(); });
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>
