<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BengkelNotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjaman;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Siswa\PeminjamanController as SiswaPeminjaman;
use App\Http\Controllers\Siswa\MateriController as SiswaMateri;
use Illuminate\Support\Facades\Route;

// ─── Redirect root ────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'superadmin') return redirect()->route('superadmin.dashboard');
        if ($role === 'admin_bengkel') return redirect()->route('admin.dashboard');
        if ($role === 'admin_perpus') return redirect()->route('perpustakaan.admin.dashboard');
        return redirect()->route('portal');
    }
    return redirect()->route('login');
});

// ─── Portal Siswa (Pilihan Bengkel / Perpus) ──────────────────────────────────
Route::get('/portal', function () {
    return view('portal');
})->middleware(['auth', 'role:siswa'])->name('portal');

// ─── Superadmin Routes ────────────────────────────────────────────────────────
Route::prefix('superadmin')
    ->middleware(['auth', 'role:superadmin'])
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('dashboard');
        
        // Master Data NIS Terverifikasi
        Route::get('/siswa', [\App\Http\Controllers\Superadmin\MasterSiswaController::class, 'index'])->name('siswa.index');
        Route::post('/siswa/store', [\App\Http\Controllers\Superadmin\MasterSiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{id}/edit', [\App\Http\Controllers\Superadmin\MasterSiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{id}', [\App\Http\Controllers\Superadmin\MasterSiswaController::class, 'update'])->name('siswa.update');
        Route::post('/siswa/import', [\App\Http\Controllers\Superadmin\MasterSiswaController::class, 'importCsv'])->name('siswa.import');
        Route::delete('/siswa/{id}', [\App\Http\Controllers\Superadmin\MasterSiswaController::class, 'destroy'])->name('siswa.destroy');

        // Akun Siswa (Registered Student Accounts)
        Route::resource('users', \App\Http\Controllers\Superadmin\SiswaUserController::class)->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);
        Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Superadmin\SiswaUserController::class, 'resetPassword'])->name('users.reset_password');

        // Akun Admin Management
        Route::resource('admins', \App\Http\Controllers\Superadmin\AdminController::class)->names([
            'index' => 'admins.index',
            'create' => 'admins.create',
            'store' => 'admins.store',
            'edit' => 'admins.edit',
            'update' => 'admins.update',
            'destroy' => 'admins.destroy',
        ]);
        Route::post('/admins/{user}/change-password', [\App\Http\Controllers\Superadmin\AdminController::class, 'changePassword'])->name('admins.change_password');

        // API Cek NIS Cepat
        Route::get('/api/check-nis/{nis}', [\App\Http\Controllers\Superadmin\DashboardController::class, 'checkNis'])->name('check_nis.api');

        // API Statistik Peminjaman Real-time (polling dashboard)
        Route::get('/api/statistik-realtime', [\App\Http\Controllers\Superadmin\DashboardController::class, 'statistikRealtime'])->name('statistik.realtime');
    });

// ─── Auth Routes (Guest Only) ─────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registrasi Mandiri Siswa Bengkel
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/password/change', [\App\Http\Controllers\PasswordController::class, 'edit'])->name('password.change');
    Route::put('/password/update', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');
    Route::post('/password/dismiss-prompt', [\App\Http\Controllers\PasswordController::class, 'dismissPrompt'])->name('password.dismiss_prompt');
});

// ─── Admin Bengkel Routes ─────────────────────────────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'role:admin_bengkel,superadmin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Bab 5 Laporan Tugas Akhir
        Route::get('/bab5', [AdminDashboard::class, 'bab5'])->name('bab5');

        // Manajemen Barang (CRUD Resource)
        Route::post('barangs/{barang}/reset-maintenance', [BarangController::class, 'resetMaintenance'])->name('barangs.reset-maintenance');
        Route::resource('barangs', BarangController::class);

        // Manajemen Peminjaman & Pengembalian
        Route::get('/peminjaman', [AdminPeminjaman::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/{id}/setujui', [AdminPeminjaman::class, 'setujuiPinjam'])->name('peminjaman.setujui');
        Route::post('/peminjaman/{id}/tolak', [AdminPeminjaman::class, 'tolakPinjam'])->name('peminjaman.tolak');
        Route::post('/peminjaman/{id}/kembali', [AdminPeminjaman::class, 'prosesKembali'])->name('peminjaman.kembali');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

        // Manajemen User (Siswa) - Read-only untuk Admin Bengkel
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // Materi Pembelajaran
        Route::resource('materis', MateriController::class)->only(['index', 'create', 'store', 'destroy']);
    });

// ─── Siswa Routes ─────────────────────────────────────────────────────────────
Route::prefix('siswa')
    ->middleware(['auth', 'role:siswa', 'tkr', 'require.email'])
    ->name('siswa.')
    ->group(function () {

        // Dashboard / Katalog Barang
        Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');

        // Peminjaman
        Route::get('/peminjaman/buat', [SiswaPeminjaman::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [SiswaPeminjaman::class, 'store'])->name('peminjaman.store');

        // Riwayat Peminjaman
        Route::get('/riwayat', [SiswaPeminjaman::class, 'riwayat'])->name('riwayat');
        Route::post('/peminjaman/{id}/kembali', [SiswaPeminjaman::class, 'prosesKembali'])->name('peminjaman.kembali');

        // Materi Pembelajaran
        Route::get('/materi', [SiswaMateri::class, 'index'])->name('materi');

        // Keranjang Peminjaman
        Route::post('/keranjang/tambah', [SiswaDashboard::class, 'addToCart'])->name('keranjang.tambah');
        Route::post('/keranjang/update/{barangId}', [SiswaDashboard::class, 'updateCart'])->name('keranjang.update');
        Route::delete('/keranjang/{barangId}', [SiswaDashboard::class, 'removeFromCart'])->name('keranjang.hapus');
        Route::get('/keranjang/data', [SiswaDashboard::class, 'getCart'])->name('keranjang.data');

        // API Real-time Data
        Route::get('/api/data', [SiswaDashboard::class, 'apiData'])->name('api.data');

        // Notifikasi Bengkel (Siswa) — tanpa require.email agar bell tetap bisa load
        Route::get('/notifikasi', [BengkelNotificationController::class, 'fetch'])->name('notifikasi.fetch');
        Route::post('/notifikasi/baca', [BengkelNotificationController::class, 'markAllRead'])->name('notifikasi.baca');
        Route::get('/notifikasi/unread', [BengkelNotificationController::class, 'unreadCount'])->name('notifikasi.unread');
    });

// ─── Notifikasi Admin Bengkel ─────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/notifikasi', [BengkelNotificationController::class, 'fetch'])->name('notifikasi.fetch');
    Route::post('/notifikasi/baca', [BengkelNotificationController::class, 'markAllRead'])->name('notifikasi.baca');
    Route::get('/notifikasi/unread', [BengkelNotificationController::class, 'unreadCount'])->name('notifikasi.unread');
});

// ─── Email Preview (hanya untuk local dev) ────────────────────────────────────
if (app()->environment('local')) {
    Route::get('/email-preview/jatuh-tempo', function () {
        return new \App\Mail\JatuhTempoReminder(
            namaSiswa:    'Reza Pahlevi',
            namaBarang:   'Kunci Ring Set',
            batasKembali: now()->addDay()->translatedFormat('d F Y'),
            jumlah:       2,
        );
    })->name('email.preview.jatuh-tempo');

    Route::get('/email-preview/keterlambatan', function () {
        return new \App\Mail\KeterlambatanReminder(
            namaSiswa:    'Reza Pahlevi',
            namaBarang:   'Feler Gauge',
            batasKembali: now()->subDays(3)->translatedFormat('d F Y'),
            hariTerlambat: 3,
            jumlah:       1,
        );
    })->name('email.preview.keterlambatan');

    // Route untuk mencoba MENGIRIM email secara langsung
    Route::get('/test-send-email', function () {
        try {
            // Kita coba kirim ke email yang sama dengan pengirim untuk tes
            $testEmail = env('MAIL_USERNAME'); 
            
            \Illuminate\Support\Facades\Mail::to($testEmail)->send(new \App\Mail\JatuhTempoReminder(
                namaSiswa:    'Siswa Test',
                namaBarang:   'Obeng Plus',
                batasKembali: now()->addDay()->translatedFormat('d F Y'),
                jumlah:       1,
            ));
            
            return "Email berhasil dikirim ke: " . $testEmail . "! Silakan cek inbox Anda.";
        } catch (\Exception $e) {
            return "Gagal mengirim email. Error: " . $e->getMessage();
        }
    })->name('email.test.send');
}
