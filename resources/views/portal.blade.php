<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Siswa — SMK Ma'arif Talang</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen flex items-center justify-center p-4">

    <!-- Decorative blobs -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-blue-500/20 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-emerald-500/20 blur-[100px]"></div>
    </div>

    <div class="max-w-4xl w-full">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-white mb-3 tracking-tight drop-shadow-sm">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-indigo-200 text-base font-medium">Silakan pilih layanan yang ingin Anda akses hari ini.</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('error'))
        <div class="mb-8 max-w-xl mx-auto bg-rose-500/20 border border-rose-500/30 text-rose-200 px-5 py-4 rounded-2xl text-sm shadow-sm flex items-start gap-3 justify-center animate-pulse backdrop-blur-md">
            <svg class="w-5 h-5 text-rose-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V7a1 1 0 112 0v2a1 1 0 11-2 0zm0 4a1 1 0 102 0 1 1 0 00-2 0z" clip-rule="evenodd"/></svg>
            <span class="font-semibold text-center leading-normal">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Cards Container -->
        <div class="grid md:grid-cols-2 gap-8">
            
            @php
                $jurusanUpper = strtoupper(trim(auth()->user()->jurusan ?? ''));
                $isTkr = in_array($jurusanUpper, ['TKR', 'TEKNIK KENDARAAN RINGAN']);
            @endphp

            <!-- Bengkel Card -->
            <a href="{{ route('siswa.dashboard') }}" 
               class="group relative bg-white rounded-3xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 hover:shadow-2xl {{ $isTkr ? 'hover:shadow-blue-500/20 hover:-translate-y-2' : 'opacity-80 cursor-not-allowed' }} transition-all duration-300 overflow-hidden flex flex-col items-center text-center">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 {{ $isTkr ? 'group-hover:opacity-100' : '' }} transition-opacity"></div>
                
                @if(!$isTkr)
                <div class="absolute top-4 right-4 bg-rose-100 text-rose-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                    Khusus TKR
                </div>
                @endif

                <div class="w-24 h-24 {{ $isTkr ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-400' }} rounded-2xl flex items-center justify-center mb-6 shadow-inner relative z-10 {{ $isTkr ? 'group-hover:scale-110' : '' }} transition-transform duration-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2 relative z-10">Sistem Bengkel (SIPAB)</h2>
                <p class="text-slate-500 mb-8 relative z-10 text-sm">Pinjam alat praktek, cek ketersediaan alat, dan lihat riwayat peminjaman bengkel Anda.</p>
                <span class="mt-auto inline-flex items-center justify-center w-full {{ $isTkr ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-300 text-slate-500 cursor-not-allowed' }} text-white font-semibold py-3 rounded-xl relative z-10 transition-colors">
                    {{ $isTkr ? 'Masuk Bengkel →' : 'Akses Terkunci (Non-TKR)' }}
                </span>
            </a>

            <!-- Perpustakaan Card -->
            <a href="{{ route('perpustakaan.siswa.dashboard') }}" 
               class="group relative bg-white rounded-3xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 hover:shadow-2xl hover:shadow-green-500/20 hover:-translate-y-2 transition-all duration-300 overflow-hidden flex flex-col items-center text-center">
                <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="w-24 h-24 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner relative z-10 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2 relative z-10">Perpustakaan (SIPB)</h2>
                <p class="text-slate-500 mb-8 relative z-10 text-sm">Cari buku pelajaran, pinjam buku, dan pantau batas waktu pengembalian buku Anda.</p>
                <span class="mt-auto inline-flex items-center justify-center w-full bg-green-600 text-white font-semibold py-3 rounded-xl relative z-10 group-hover:bg-green-700 transition-colors">
                    Masuk Perpustakaan &rarr;
                </span>
            </a>

        </div>

        <!-- Footer / Logout -->
        <div class="mt-12 text-center">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-rose-400 font-semibold transition-colors inline-flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-white/5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Akun
                </button>
            </form>
        </div>
    </div>

    <!-- Footer Watermark -->
    <footer class="fixed bottom-0 left-0 right-0 z-50">
        <div class="flex items-center justify-between bg-black/40 backdrop-blur-md border-t border-white/10 px-6 sm:px-10 lg:px-16 py-4">
            <!-- Kiri: Logo + Nama Kampus -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl overflow-hidden border border-white/20 flex-shrink-0 bg-white/5 flex items-center justify-center">
                    <img src="{{ asset('images/logo_kampus.jpg') }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                         alt="Logo Universitas Harkat Negeri"
                         class="w-full h-full object-contain">
                    <span style="display:none" class="w-full h-full bg-blue-600 flex items-center justify-center text-white text-[10px] font-extrabold rounded-xl">UHN</span>
                </div>
                <div>
                    <p class="text-white text-xs font-bold leading-tight">Universitas Harkat Negeri</p>
                    <p class="text-slate-400 text-[10px] font-medium mt-0.5">Sistem Peminjaman Alat Bengkel & Buku Perpustakaan</p>
                </div>
            </div>

            <!-- Tengah: Copyright (hanya desktop) -->
            <p class="hidden lg:block text-slate-500 text-[10px] font-medium">&copy; {{ date('Y') }} All rights reserved</p>

            <!-- Kanan: Nama + Badge -->
            <div class="text-right">
                <p class="text-slate-200 text-xs font-bold">Fadlian Yusup</p>
                <span class="inline-flex items-center gap-1 bg-blue-500/20 border border-blue-500/30 text-blue-300 text-[10px] font-semibold px-2.5 py-0.5 rounded-full mt-0.5">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 21H3a12.083 12.083 0 012.84-10.422L12 14z"/></svg>
                    Tugas Akhir 2026
                </span>
            </div>
        </div>
    </footer>
</body>
</html>
