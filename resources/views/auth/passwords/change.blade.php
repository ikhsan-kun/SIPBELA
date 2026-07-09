@php
    $from = request('from');
    $layout = $from === 'perpus' ? 'perpustakaan.layouts.app' : 'layouts.app';
    
    // Tentukan URL untuk tombol batal
    if ($from === 'perpus') {
        $cancelUrl = auth()->user()->role === 'admin_perpus' ? route('perpustakaan.admin.dashboard') : route('perpustakaan.siswa.dashboard');
    } else {
        $cancelUrl = auth()->user()->role === 'superadmin' ? route('superadmin.dashboard') : (auth()->user()->role === 'admin_bengkel' ? route('admin.dashboard') : route('siswa.dashboard'));
    }
@endphp
@extends($layout)

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')
@section('page-subtitle', 'Ubah kata sandi Anda untuk keamanan akun')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Form Ganti Password</h3>
            <p class="text-sm text-slate-500 mt-1">Pastikan password baru Anda mudah diingat dan aman.</p>
        </div>

        @php
            $isDefaultEmail = !auth()->user()->email
                || str_ends_with(auth()->user()->email, '@siswa.sch.id');
        @endphp

        @if($isDefaultEmail)
        <div class="mx-6 mt-5 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div>
                <strong>Email Gmail diperlukan!</strong>
                <p class="text-xs text-amber-700 mt-0.5">Email Anda digunakan untuk menerima notifikasi pengingat jatuh tempo dan keterlambatan pengembalian alat bengkel. Harap isi email Gmail yang aktif.</p>
            </div>
        </div>
        @endif
        
        {{-- Tampilkan semua error di satu tempat --}}
        @if($errors->any())
        <div class="mx-6 mt-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l-1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <ul class="space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="mx-6 mt-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        
        <form id="changePasswordForm" action="{{ route('password.update', ['from' => $from]) }}" method="POST" class="p-6 space-y-5" novalidate>
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password"
                    class="w-full px-4 py-2 border {{ $errors->has('current_password') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                    placeholder="Masukkan password saat ini">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-2 border {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                    placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                    placeholder="Ulangi password baru">
                <p id="password_match_msg" class="text-xs mt-1 hidden"></p>
            </div>

            {{-- Field Email: wajib jika masih default --}}
            @if($isDefaultEmail)
            <div class="pt-2 border-t border-slate-100">
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                    Email Gmail Aktif <span class="text-red-500">*</span>
                    <span class="text-xs font-normal text-slate-400 ml-1">(untuk notifikasi sistem)</span>
                </label>
                <input type="email" name="email" id="email"
                    value="{{ old('email') }}"
                    placeholder="contoh: nama@gmail.com"
                    class="w-full px-4 py-2 border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none">
                <p id="email_hint" class="text-xs text-slate-400 mt-1">Email ini hanya digunakan untuk notifikasi sistem, tidak dipublikasikan.</p>
            </div>
            @else
            <div class="pt-2 border-t border-slate-100">
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                    Email Aktif
                    <span class="text-xs font-normal text-slate-400 ml-1">(opsional, perbarui jika perlu)</span>
                </label>
                <input type="email" name="email" id="email"
                    value="{{ old('email', auth()->user()->email) }}"
                    placeholder="contoh: nama@gmail.com"
                    class="w-full px-4 py-2 border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none">
            </div>
            @endif

            <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                <a href="{{ $cancelUrl }}" class="text-sm text-slate-500 hover:text-slate-800 font-medium">Batal</a>
                <button type="submit" id="submitBtn" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const isDefaultEmail = {{ $isDefaultEmail ? 'true' : 'false' }};

// ── Validasi form sebelum submit ──────────────────────────────────────────────
document.getElementById('changePasswordForm').addEventListener('submit', function (e) {
    let valid = true;
    const currentPw  = document.getElementById('current_password');
    const newPw      = document.getElementById('password');
    const confirmPw  = document.getElementById('password_confirmation');
    const emailInput = document.getElementById('email');

    // Reset border merah dulu
    [currentPw, newPw, confirmPw, emailInput].forEach(el => {
        if (el) el.classList.remove('border-red-400', 'bg-red-50');
    });

    // Cek password saat ini
    if (!currentPw.value.trim()) {
        e.preventDefault();
        currentPw.classList.add('border-red-400', 'bg-red-50');
        showToast('⚠️ Password saat ini wajib diisi.', 'warning');
        valid = false;
        return;
    }

    // Cek password baru
    if (!newPw.value.trim()) {
        e.preventDefault();
        newPw.classList.add('border-red-400', 'bg-red-50');
        showToast('⚠️ Password baru wajib diisi.', 'warning');
        valid = false;
        return;
    }

    if (newPw.value.length < 8) {
        e.preventDefault();
        newPw.classList.add('border-red-400', 'bg-red-50');
        showToast('⚠️ Password baru minimal 8 karakter.', 'warning');
        valid = false;
        return;
    }

    // Cek konfirmasi password
    if (newPw.value !== confirmPw.value) {
        e.preventDefault();
        confirmPw.classList.add('border-red-400', 'bg-red-50');
        showToast('⚠️ Konfirmasi password tidak cocok dengan password baru.', 'warning');
        valid = false;
        return;
    }

    // Cek email jika wajib (default email)
    if (isDefaultEmail && emailInput) {
        if (!emailInput.value.trim()) {
            e.preventDefault();
            emailInput.classList.add('border-red-400', 'bg-red-50');
            showToast('⚠️ Email Gmail wajib diisi agar Anda dapat menerima notifikasi.', 'warning');
            emailInput.focus();
            valid = false;
            return;
        }
        // Cek format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value.trim())) {
            e.preventDefault();
            emailInput.classList.add('border-red-400', 'bg-red-50');
            showToast('⚠️ Format email tidak valid. Contoh: nama@gmail.com', 'warning');
            emailInput.focus();
            valid = false;
            return;
        }
    }
});

// ── Live-check: cocokkan konfirmasi password ──────────────────────────────────
const pwInput      = document.getElementById('password');
const confirmInput = document.getElementById('password_confirmation');
const matchMsg     = document.getElementById('password_match_msg');

if (pwInput && confirmInput) {
    confirmInput.addEventListener('input', function () {
        if (!confirmInput.value) {
            matchMsg.classList.add('hidden');
            return;
        }
        matchMsg.classList.remove('hidden');
        if (pwInput.value === confirmInput.value) {
            matchMsg.textContent = '✓ Password cocok';
            matchMsg.className = 'text-xs mt-1 text-emerald-600 font-medium';
        } else {
            matchMsg.textContent = '✗ Password tidak cocok';
            matchMsg.className = 'text-xs mt-1 text-red-500 font-medium';
        }
    });
}

// ── Helper: tampilkan pesan toast dengan SweetAlert ───────────────────────────
function showToast(message, type = 'warning') {
    if (typeof Swal !== 'undefined') {
        const icons = { warning: 'warning', error: 'error', success: 'success' };
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icons[type] || 'warning',
            title: message,
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
        });
    }
}
</script>
@endpush
