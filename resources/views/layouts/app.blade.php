<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'SIPBELA') — Sistem Peminjaman Alat Bengkel & Buku Perpustakaan</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <meta name="description" content="Sistem Peminjaman Alat Bengkel dan Buku Perpustakaan SMK" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:  { DEFAULT: '#2563EB', dark: '#1D4ED8', light: '#3B82F6' },
                        accent:   { DEFAULT: '#F59E0B', dark: '#D97706' },
                        success:  '#10B981',
                        danger:   '#EF4444',
                        warning:  '#F59E0B',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Nav links */
        .nav-link {
            display: flex; align-items: center; gap: 0.625rem;
            padding: 0.75rem 1rem; color: #94a3b8;
            transition: all 0.2s; font-size: 0.875rem; font-weight: 500;
            white-space: nowrap; border-bottom: 2px solid transparent;
            text-decoration: none;
        }
        .nav-link:hover { color: #fff; border-bottom-color: #64748b; background: rgba(255,255,255,0.05); }
        .nav-link.active { color: #fff; border-bottom-color: #3b82f6; background: rgba(30,64,175,0.2); }
        /* Cards */
        .card {
            background: #fff; border-radius: 1rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }
        /* Buttons */
        .btn-primary {
            background: #2563eb; color: #fff; padding: 0.5rem 1rem;
            border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;
            border: none; cursor: pointer; text-decoration: none;
        }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-danger {
            background: #ef4444; color: #fff; padding: 0.375rem 0.75rem;
            border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.375rem;
            border: none; cursor: pointer; text-decoration: none;
        }
        .btn-danger:hover { background: #dc2626; }
        .btn-success {
            background: #10b981; color: #fff; padding: 0.375rem 0.75rem;
            border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.375rem;
            border: none; cursor: pointer; text-decoration: none;
        }
        .btn-success:hover { background: #059669; }
        .btn-perpus {
            background: #16a34a; color: #fff; padding: 0.5rem 1.25rem;
            border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;
            border: none; cursor: pointer; text-decoration: none;
        }
        .btn-perpus:hover { background: #15803d; transform: translateY(-1px); }
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
        .badge-dipinjam {
            display: inline-flex; align-items: center; padding: 0.125rem 0.625rem;
            border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
            background: #fef3c7; color: #92400e;
        }
        .badge-menunggu {
            display: inline-flex; align-items: center; padding: 0.125rem 0.625rem;
            border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
            background: #e0f2fe; color: #0369a1;
        }
        .badge-dikembalikan {
            display: inline-flex; align-items: center; padding: 0.125rem 0.625rem;
            border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
            background: #d1fae5; color: #065f46;
        }
        .badge-baik {
            display: inline-flex; align-items: center; padding: 0.125rem 0.625rem;
            border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
            background: #d1fae5; color: #065f46;
        }
        .badge-rusak {
            display: inline-flex; align-items: center; padding: 0.125rem 0.625rem;
            border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
            background: #fee2e2; color: #991b1b;
        }
        .badge-diperbaiki {
            display: inline-flex; align-items: center; padding: 0.125rem 0.625rem;
            border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
            background: #dbeafe; color: #1e40af;
        }
        /* Table */
        .table-th {
            padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem;
            font-weight: 600; color: #64748b; text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .table-td { padding: 0.75rem 1rem; font-size: 0.875rem; color: #334155; }
        /* Scrollbar hide */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
        
        /* Notification dropdown */
        .notif-dropdown {
            display: none; position: absolute; right: 0; top: calc(100% + 8px);
            width: 340px; background: #fff; border: 1px solid #e2e8f0;
            border-radius: 1rem; box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            z-index: 1000; overflow: hidden;
        }
        .notif-dropdown.open { display: block; animation: fadeInDown 0.18s ease; }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .notif-item { padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; cursor: pointer; }
        .notif-item a { text-decoration: none; color: inherit; display: block; }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #eff6ff; border-left: 3px solid #3b82f6; }
        .notif-item.unread:hover { background: #dbeafe; }
        /* Sidebar Styles */
        @media (min-width: 1024px) {
            .sidebar-active main { margin-left: 16rem; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">
    <!-- Script tab_authenticated dihapus untuk mencegah redirect logout paksa antar sub-sistem -->

<!-- ── Layout Container ── -->
<div class="flex min-h-screen">
    @if(auth()->check() && auth()->user()->isAdmin())
    <!-- ── Sidebar (Admin) ── -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 hidden lg:flex flex-col sticky top-0 h-screen shadow-2xl z-50">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg tracking-tight leading-none">SIPBELA</h1>
                <p class="text-slate-500 text-xs mt-1">Admin Workshop</p>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2">Main Menu</p>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
            <a href="{{ route('admin.barangs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.barangs*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="font-medium text-sm">Data Barang</span>
            </a>
            <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.peminjaman*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="font-medium text-sm">Peminjaman</span>
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.laporan*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="font-medium text-sm">Laporan</span>
            </a>

            <div class="pt-4 mt-4 border-t border-slate-800">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-2">Management</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.users*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="font-medium text-sm">Data Siswa</span>
                </a>
                <a href="{{ route('admin.materis.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.materis*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span class="font-medium text-sm">Materi</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span class="font-semibold text-sm">Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    @endif

    <!-- ── Main Area ── -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">
        <!-- Top Navbar (Both Admin & Siswa) -->
        <header class="bg-white border-b border-slate-200 h-16 sticky top-0 z-50 flex items-center shadow-sm px-4 lg:px-8">
            <div class="flex items-center justify-between w-full">
                <!-- Mobile Menu Button (Optional) -->
                <div class="flex lg:hidden items-center gap-3">
                     <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center text-white">S</div>
                     <span class="font-bold text-slate-800">SIPBELA</span>
                </div>

                <!-- Breadcrumbs/Page Title (Desktop) -->
                <div class="hidden lg:flex flex-col">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">SIPBELA / SMK Ma'arif Talang</p>
                    <h2 class="text-sm font-bold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-2 bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full text-[11px] font-bold border border-slate-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ now()->translatedFormat('d M Y') }}
                    </div>
                    
                    @if(auth()->check())
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-slate-800 text-xs font-bold leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-slate-500 text-[10px] mt-1 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <div class="w-9 h-9 bg-blue-50 text-blue-600 border border-blue-100 rounded-full flex items-center justify-center text-xs font-bold shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        
                        <!-- Tombol Ganti Password -->
                        <a href="{{ route('password.change', ['from' => 'bengkel']) }}" title="Ganti Password" class="p-2 ml-2 text-slate-400 hover:text-blue-500 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </a>

                        @if(in_array(auth()->user()->role, ['siswa', 'admin_bengkel']))
                        <!-- Bell Notifikasi -->
                        <div class="relative" id="notif-wrapper">
                            <button id="notif-btn" onclick="toggleNotifDropdown()" title="Notifikasi"
                                class="relative p-2 text-slate-400 hover:text-blue-500 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white hidden">0</span>
                            </button>
                            <!-- Dropdown Panel -->
                            <div id="notif-dropdown" class="notif-dropdown">
                                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50">
                                    <h3 class="font-bold text-slate-800 text-sm">🔔 Notifikasi</h3>
                                    <button onclick="markAllRead()" class="text-[11px] text-blue-600 hover:underline font-medium">Tandai semua dibaca</button>
                                </div>
                                <div id="notif-list" class="max-h-80 overflow-y-auto custom-scrollbar">
                                    <div class="text-center py-8 text-slate-400 text-sm" id="notif-empty">
                                        <svg class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                        Tidak ada notifikasi
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->role === 'siswa')
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-red-500 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </header>

        <!-- Secondary Nav (For Siswa - Mobile/Horizontal) -->
        @if(auth()->check() && auth()->user()->role === 'siswa')
        <div class="bg-slate-900 overflow-x-auto no-scrollbar lg:px-8 sticky top-16 z-40">
            <div class="flex">
                <a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition-all {{ request()->routeIs('siswa.dashboard') ? 'text-white border-b-2 border-blue-500 bg-blue-500/10' : 'text-slate-400 hover:text-white' }}">
                    Katalog Alat
                </a>
                <a href="{{ route('siswa.peminjaman.create') }}" class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition-all {{ request()->routeIs('siswa.peminjaman.create') ? 'text-white border-b-2 border-blue-500 bg-blue-500/10' : 'text-slate-400 hover:text-white' }}">
                    Pinjam Alat
                </a>
                <a href="{{ route('siswa.riwayat') }}" class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition-all {{ request()->routeIs('siswa.riwayat') ? 'text-white border-b-2 border-blue-500 bg-blue-500/10' : 'text-slate-400 hover:text-white' }}">
                    Riwayat
                </a>
                <a href="{{ route('siswa.materi') }}" class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition-all {{ request()->routeIs('siswa.materi') ? 'text-white border-b-2 border-blue-500 bg-blue-500/10' : 'text-slate-400 hover:text-white' }}">
                    Materi
                </a>
            </div>
        </div>
        @endif

        <!-- Main Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto custom-scrollbar" id="main-content">
            <!-- Pull to Refresh Indicator -->
            <div id="pull-refresh" class="hidden flex items-center justify-center gap-2 py-4 bg-blue-50 border-b border-blue-100 text-blue-600 text-sm font-semibold transition-all duration-300">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <span id="pull-refresh-text">Memuat data terbaru...</span>
            </div>
            <!-- Content Header (Mobile Title) -->
            <div class="bg-white border-b border-slate-200 lg:hidden">
                <div class="px-4 py-5">
                    <h1 class="text-xl font-extrabold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 mt-1">@yield('page-subtitle', '')</p>
                </div>
            </div>

            <!-- Content Body -->
            <div class="p-4 lg:p-8 max-w-7xl">
                <!-- Flash Messages -->
                @if(session('success'))
                <div id="alert-success" class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm mb-6 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                @endif
                @if(session('error'))
                <div id="alert-error" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm mb-6 shadow-sm">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- ── Footer Watermark ── -->
        <footer class="bg-slate-900 border-t border-slate-800 px-4 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 py-4 max-w-7xl">
                <!-- Kiri: Logo + Nama Kampus -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg overflow-hidden border border-slate-700 flex-shrink-0 bg-slate-800 flex items-center justify-center">
                        <img src="{{ asset('images/logo_kampus.jpg') }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             alt="Logo Universitas Harkat Negeri"
                             class="w-full h-full object-contain">
                        <span style="display:none" class="w-full h-full bg-blue-700 flex items-center justify-center text-white text-[10px] font-extrabold rounded-lg">UHN</span>
                    </div>
                    <div>
                        <p class="text-slate-200 text-xs font-bold leading-tight">Universitas Harkat Negeri</p>
                        <p class="text-slate-500 text-[10px] font-medium mt-0.5">Sistem Peminjaman Alat Bengkel & Buku Perpustakaan</p>
                    </div>
                </div>

                <!-- Kanan: Nama + Tugas Akhir -->
                <div class="flex items-center gap-3">
                    <span class="text-slate-600 text-[10px] hidden sm:inline">&copy; {{ date('Y') }}</span>
                    <div class="text-right">
                        <p class="text-slate-300 text-xs font-bold">Fadlian Yusup</p>
                        <span class="inline-flex items-center gap-1 bg-blue-900/50 border border-blue-800/40 text-blue-400 text-[10px] font-semibold px-2 py-0.5 rounded-full">
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
    setTimeout(() => {
        ['alert-success', 'alert-error'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }
        });
    }, 4000);

    document.addEventListener('DOMContentLoaded', function() {
        // Handler form hapus (Delete)
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = this.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?';
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        // Handler form Reset Maintenance
        const resetForms = document.querySelectorAll('.reset-maintenance-form');
        resetForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = this.getAttribute('data-message') || 'Reset siklus pemakaian menjadi 0? Pastikan alat sudah diservis.';
                Swal.fire({
                    title: 'Konfirmasi Reset Siklus Servis',
                    text: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb', // Blue
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Sudah Diservis!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });

    // ── Notification System ────────────────────────────────────────────────────
    @if(auth()->check() && in_array(auth()->user()->role, ['siswa', 'admin_bengkel']))
    const _notifCsrf = document.querySelector('meta[name="csrf-token"]').content;
    const _notifUrl  = '{{ auth()->user()->role === "siswa" ? route("siswa.notifikasi.fetch") : route("admin.notifikasi.fetch") }}';
    const _markUrl   = '{{ auth()->user()->role === "siswa" ? route("siswa.notifikasi.baca") : route("admin.notifikasi.baca") }}';
    let _notifOpen   = false;

    function toggleNotifDropdown() {
        _notifOpen = !_notifOpen;
        document.getElementById('notif-dropdown').classList.toggle('open', _notifOpen);
        if (_notifOpen) {
            loadNotifications();
        }
    }

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('notif-wrapper');
        if (wrapper && !wrapper.contains(e.target) && _notifOpen) {
            _notifOpen = false;
            document.getElementById('notif-dropdown').classList.remove('open');
        }
    });

    function loadNotifications() {
        fetch(_notifUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            renderNotifications(data.notifications, data.unread_count);
        }).catch(() => {});
    }

    // Mapping tipe notif ke URL aksi berdasarkan role
    const _notifActionUrls = {
        @if(auth()->check() && auth()->user()->role === 'admin_bengkel')
        'request_submitted': '{{ route("admin.peminjaman.index") }}',
        'return_submitted':  '{{ route("admin.peminjaman.index") }}',
        @elseif(auth()->check() && auth()->user()->role === 'siswa')
        'request_submitted': '{{ route("siswa.riwayat") }}',
        'request_approved':  '{{ route("siswa.riwayat") }}',
        'request_rejected':  '{{ route("siswa.riwayat") }}',
        'return_confirmed':  '{{ route("siswa.riwayat") }}',
        'due_soon':          '{{ route("siswa.riwayat") }}',
        @endif
    };
    const _markOneUrl = '{{ auth()->check() && auth()->user()->role === "admin_bengkel" ? route("admin.notifikasi.baca") : (auth()->check() && auth()->user()->role === "siswa" ? route("siswa.notifikasi.baca") : "#") }}';

    function goToNotifAction(type) {
        const url = _notifActionUrls[type];
        // Tandai semua dibaca dulu, lalu arahkan ke halaman aksi
        fetch(_markUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': _notifCsrf, 'Accept': 'application/json' }
        }).then(() => {
            if (url) window.location.href = url;
        }).catch(() => {
            if (url) window.location.href = url;
        });
    }

    function renderNotifications(notifications, unreadCount) {
        const badge  = document.getElementById('notif-badge');
        const list   = document.getElementById('notif-list');
        const empty  = document.getElementById('notif-empty');

        if (badge) {
            badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
            badge.classList.toggle('hidden', unreadCount === 0);
        }

        if (!notifications || notifications.length === 0) {
            if (empty) empty.style.display = '';
            return;
        }
        if (empty) empty.style.display = 'none';

        const typeIcons = {
            'request_submitted': '⏳',
            'request_approved':  '✅',
            'request_rejected':  '❌',
            'return_submitted':  '🔄',
            'return_confirmed':  '✅',
            'due_soon':          '⚠️',
        };

        list.innerHTML = notifications.map(n => {
            const icon     = typeIcons[n.type] || '🔔';
            const isUnread = !n.read_at;
            const timeAgo  = formatTimeAgo(n.created_at);
            const hasAction = !!_notifActionUrls[n.type];
            const clickAttr = hasAction ? `onclick="goToNotifAction('${n.type}')" style="cursor:pointer"` : '';
            return `<div class="notif-item ${isUnread ? 'unread' : ''}" ${clickAttr}>
                <div class="flex items-start gap-2">
                    <span class="text-lg flex-shrink-0">${icon}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-800 leading-tight">${n.title}${hasAction ? ' <span class="text-blue-400 text-[10px]">→ Lihat</span>' : ''}</p>
                        <p class="text-xs text-slate-500 mt-0.5 leading-snug">${n.message}</p>
                        <p class="text-[10px] text-slate-400 mt-1">${timeAgo}</p>
                    </div>
                    ${isUnread ? '<span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1"></span>' : ''}
                </div>
            </div>`;
        }).join('');
    }

    function markAllRead() {
        fetch(_markUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': _notifCsrf, 'Accept': 'application/json' }
        }).then(() => loadNotifications()).catch(() => {});
    }

    function formatTimeAgo(dateStr) {
        if (!dateStr) return '';
        const now  = new Date();
        const date = new Date(dateStr);
        const diff = Math.floor((now - date) / 1000);
        if (diff < 60) return 'Baru saja';
        if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
        if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
        return Math.floor(diff / 86400) + ' hari lalu';
    }

    // Poll unread count every 30 seconds
    function pollUnreadCount() {
        const unreadUrl = '{{ auth()->user()->role === "siswa" ? route("siswa.notifikasi.unread") : route("admin.notifikasi.unread") }}';
        fetch(unreadUrl, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('notif-badge');
            if (badge) {
                badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                badge.classList.toggle('hidden', data.unread_count === 0);
            }
        }).catch(() => {});
    }

    // Initial poll + set interval
    document.addEventListener('DOMContentLoaded', () => {
        pollUnreadCount();
        setInterval(pollUnreadCount, 30000);
    });
    @endif
</script>
@stack('scripts')
</body>
</html>
