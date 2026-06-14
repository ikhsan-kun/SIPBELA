<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Sistem Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center py-8 px-4 relative bg-slate-900">
    
    <!-- Full Background Image -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <img src="{{ asset('images/sekolah_nyata.jpg') }}" class="w-full h-full object-cover opacity-60" alt="School Background">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-900/60 to-slate-900/90"></div>
    </div>

    <div class="w-full @yield('max-width', 'max-w-md') relative z-10 my-auto">
        @yield('content')
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
                    <p class="text-slate-400 text-[10px] font-medium mt-0.5">SIPBELA &bull; Sistem Peminjaman Alat Bengkel & Buku Perpustakaan</p>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
