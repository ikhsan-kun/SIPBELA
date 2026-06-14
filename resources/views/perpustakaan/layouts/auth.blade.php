<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Login') — SIPB Perpustakaan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="description" content="Sistem Informasi Perpustakaan Sekolah" />
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
        .auth-bg {
            background: linear-gradient(135deg, #14532d 0%, #166534 30%, #15803d 60%, #16a34a 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .floating-shapes::before, .floating-shapes::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }
        .floating-shapes::before {
            width: 400px; height: 400px;
            background: white;
            top: -100px; right: -100px;
        }
        .floating-shapes::after {
            width: 300px; height: 300px;
            background: white;
            bottom: -80px; left: -80px;
        }
    </style>
</head>
<body class="auth-bg floating-shapes relative flex flex-col items-center justify-center min-h-screen py-8 px-4">

    <!-- Book decorative elements -->
    <div class="fixed top-10 left-10 opacity-10 pointer-events-none">
        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>
    </div>
    <div class="fixed bottom-10 right-10 opacity-10 pointer-events-none">
        <svg class="w-32 h-32 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
    </div>

    <div class="w-full max-w-md relative z-10 my-auto">
        @yield('content')
    </div>

    <!-- Footer Watermark -->
    <footer class="fixed bottom-0 left-0 right-0 z-50">
        <div class="flex items-center justify-between bg-black/20 backdrop-blur-md border-t border-white/10 px-6 sm:px-10 lg:px-16 py-4">
            <!-- Kiri: Logo + Nama Kampus -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl overflow-hidden border border-white/20 flex-shrink-0 bg-white/10 flex items-center justify-center">
                    <img src="{{ asset('images/logo_kampus.jpg') }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                         alt="Logo Universitas Harkat Negeri"
                         class="w-full h-full object-contain">
                    <span style="display:none" class="w-full h-full bg-green-600 flex items-center justify-center text-white text-[10px] font-extrabold rounded-xl">UHN</span>
                </div>
                <div>
                    <p class="text-white text-xs font-bold leading-tight">Universitas Harkat Negeri</p>
                    <p class="text-green-300 text-[10px] font-medium mt-0.5">SIPB &bull; Sistem Informasi Perpustakaan</p>
                </div>
            </div>

            <!-- Tengah: Copyright (hanya desktop) -->
            <p class="hidden lg:block text-green-700 text-[10px] font-medium">&copy; {{ date('Y') }} All rights reserved</p>

            <!-- Kanan: Nama + Badge -->
            <div class="text-right">
                <p class="text-green-100 text-xs font-bold">Fadlian Yusup</p>
                <span class="inline-flex items-center gap-1 bg-green-500/20 border border-green-400/30 text-green-300 text-[10px] font-semibold px-2.5 py-0.5 rounded-full mt-0.5">
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
