@extends('layouts.auth')

@section('max-width', 'max-w-md')

@section('content')
<div class="w-full">
    
    <!-- Login Form Wrapper -->
    <div class="w-full bg-slate-900/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 flex flex-col justify-center shadow-2xl relative overflow-hidden">
        <!-- Subtle glass reflection -->
        <div class="absolute inset-0 bg-gradient-to-tr from-white/5 to-transparent pointer-events-none"></div>
        
        <div class="relative z-10">
            <div class="mb-8 text-center">
            <!-- Mobile Badge (visible only on mobile) -->
            <div class="md:hidden flex flex-wrap gap-2 mb-4">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/30">
                    🔧 Bengkel
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-500/20 text-green-300 border border-green-500/30">
                    📚 Perpustakaan
                </span>
            </div>
            <h3 class="text-2xl font-extrabold text-white tracking-tight">Selamat Datang</h3>
            <p class="text-slate-400 text-sm mt-1 font-medium">Silakan masuk ke akun terpadu Anda.</p>
        </div>

        <!-- Flash Error -->
        @if(session('success'))
        <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-xl text-xs mb-5 font-semibold text-center">
            {{ session('success') }}
        </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login.post') }}" id="form-login" class="space-y-4">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                    Username
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Masukkan username Anda"
                    autocomplete="username"
                    required
                    class="w-full bg-white/10 border @error('username') border-red-500 @else border-white/20 @enderror
                           text-white placeholder-slate-500 rounded-xl px-4 py-3 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                           transition-all duration-200"
                >
                @error('username')
                <p class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                        Password
                    </label>
                    <button type="button" onclick="showLupaPassword()" class="text-xs font-bold text-blue-400 hover:text-blue-300 transition-colors focus:outline-none">
                        Lupa Password?
                    </button>
                </div>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                        class="w-full bg-white/10 border @error('password') border-red-500 @else border-white/20 @enderror
                               text-white placeholder-slate-500 rounded-xl px-4 py-3 pr-12 text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               transition-all duration-200"
                    >
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors">
                        <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                <p class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember"
                    class="w-4 h-4 rounded border-white/20 bg-white/10 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                <label for="remember" class="ml-2 text-xs text-slate-400 font-medium">Ingat saya di perangkat ini</label>
            </div>

            <!-- Submit -->
            <button type="submit" id="btn-login"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-3.5 px-4 rounded-xl
                       transition-all duration-200 text-sm shadow-lg shadow-indigo-600/20
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-transparent">
                Masuk ke Sistem
            </button>
        </form>


        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

function showLupaPassword() {
    Swal.fire({
        title: 'Lupa Password?',
        html: `
            <div class="text-sm text-slate-600 space-y-3">
                <p>Untuk alasan keamanan, fitur ubah password secara mandiri tidak tersedia.</p>
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-left">
                    <p class="font-semibold text-blue-800 mb-1">Cara Reset Password:</p>
                    <ol class="list-decimal list-inside text-blue-700 space-y-1">
                        <li>Silakan lapor/hubungi <strong>Admin/Superadmin</strong> sekolah Anda.</li>
                        <li>Sebutkan <strong>Nama</strong> dan <strong>NIS</strong> Anda.</li>
                        <li>Admin akan mereset password Anda (Default password baru akan disamakan dengan NIS).</li>
                    </ol>
                </div>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Saya Mengerti',
        confirmButtonColor: '#2563eb',
        customClass: {
            confirmButton: 'rounded-xl px-6 py-2.5 font-semibold text-sm',
            popup: 'rounded-2xl'
        }
    });
}
</script>
@endpush
@endsection
