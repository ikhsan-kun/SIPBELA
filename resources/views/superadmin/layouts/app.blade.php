<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard - SMK Ma'arif Talang</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #fafbfc;
            background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.03) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(244, 63, 94, 0.02) 0px, transparent 50%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased">
    <!-- Script tab_authenticated_superadmin dihapus untuk mencegah redirect logout paksa antar sub-sistem -->

    <!-- Glowing Top Line Accent -->
    <div class="h-1.5 w-full bg-gradient-to-r from-violet-600 via-indigo-600 to-pink-500"></div>

    <!-- Colorful Glassmorphism Topbar -->
    <header class="bg-gradient-to-r from-violet-900 via-indigo-800 to-indigo-900 sticky top-0 z-40 shadow-lg shadow-indigo-900/20 transition-all border-b border-indigo-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-10">
                    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3.5 group">
                        <div class="w-11 h-11 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl flex items-center justify-center text-white font-extrabold shadow-lg group-hover:bg-white group-hover:text-indigo-700 transition-all">
                            SA
                        </div>
                        <div>
                            <h1 class="text-lg font-extrabold text-white tracking-tight leading-tight group-hover:text-indigo-200 transition-colors">Portal Superadmin</h1>
                            <p class="text-[11px] text-indigo-300 font-bold uppercase tracking-widest leading-none mt-0.5">Control Center</p>
                        </div>
                    </a>
                    
                    <nav class="hidden lg:flex items-center gap-1.5">
                        <a href="{{ route('superadmin.dashboard') }}" 
                           class="px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.dashboard') ? 'bg-white/15 text-white shadow-sm border border-white/10' : 'text-indigo-100/80 hover:text-white hover:bg-white/10' }}">
                           Dashboard
                        </a>
                        <a href="{{ route('superadmin.siswa.index') }}" 
                           class="px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.siswa.*') ? 'bg-white/15 text-white shadow-sm border border-white/10' : 'text-indigo-100/80 hover:text-white hover:bg-white/10' }}">
                           NIS Terverifikasi
                        </a>
                        <a href="{{ route('superadmin.users.index') }}" 
                           class="px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.users.*') ? 'bg-white/15 text-white shadow-sm border border-white/10' : 'text-indigo-100/80 hover:text-white hover:bg-white/10' }}">
                           Akun Siswa
                        </a>
                        <a href="{{ route('superadmin.admins.index') }}" 
                           class="px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('superadmin.admins.*') ? 'bg-white/15 text-white shadow-sm border border-white/10' : 'text-indigo-100/80 hover:text-white hover:bg-white/10' }}">
                           Kelola Admin
                        </a>
                    </nav>
                </div>
                
                <div class="flex items-center gap-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-indigo-100 hover:text-white hover:bg-rose-500/80 hover:shadow-lg hover:shadow-rose-500/20 border border-indigo-700/50 hover:border-rose-500 px-4.5 py-2.5 rounded-xl transition-all duration-200 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Layout -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>

    <!-- Footer Watermark -->
    <footer class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border-t border-indigo-800/40 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-5">
                <!-- Kiri: Logo + Nama Kampus -->
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl overflow-hidden border border-white/10 shadow-lg flex-shrink-0 bg-white/5 flex items-center justify-center">
                        <img src="{{ asset('images/logo_kampus.jpg') }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             alt="Logo Universitas Harkat Negeri"
                             class="w-full h-full object-contain">
                        <span style="display:none" class="w-full h-full bg-indigo-700 flex items-center justify-center text-white text-xs font-extrabold rounded-xl">UHN</span>
                    </div>
                    <div>
                        <p class="text-white text-xs font-bold leading-tight">Universitas Harkat Negeri</p>
                        <p class="text-indigo-400 text-[10px] font-medium mt-0.5 uppercase tracking-wide">Portal Superadmin</p>
                    </div>
                </div>

                <!-- Tengah: Copyright -->
                <div class="text-center hidden sm:block">
                    <p class="text-slate-500 text-[10px] font-medium">&copy; {{ date('Y') }} All rights reserved</p>
                </div>

                <!-- Kanan: Nama + Tugas Akhir -->
                <div class="text-right flex flex-col items-end gap-0.5">
                    <p class="text-white text-xs font-bold">Fadlian Yusup</p>
                    <span class="inline-flex items-center gap-1 bg-indigo-700/50 border border-indigo-600/40 text-indigo-300 text-[10px] font-semibold px-2.5 py-0.5 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 21H3a12.083 12.083 0 012.84-10.422L12 14z"/></svg>
                        Tugas Akhir 2026
                    </span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
