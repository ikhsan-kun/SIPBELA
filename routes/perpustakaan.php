<?php

use App\Http\Controllers\Perpustakaan\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Perpustakaan\Admin\BukuController as AdminBuku;
use App\Http\Controllers\Perpustakaan\Admin\UserController as AdminUser;
use App\Http\Controllers\Perpustakaan\Admin\PeminjamanController as AdminPeminjaman;
use App\Http\Controllers\Perpustakaan\Admin\PengembalianController;
use App\Http\Controllers\Perpustakaan\Admin\LaporanController;
use App\Http\Controllers\Perpustakaan\Admin\JurusanController;
use App\Http\Controllers\Perpustakaan\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Perpustakaan\Siswa\BukuController as SiswaBuku;
use App\Http\Controllers\Perpustakaan\Siswa\PeminjamanController as SiswaPeminjaman;
use Illuminate\Support\Facades\Route;

// ─── Modul Perpustakaan (SSO) ─────────────────────────────────────────────────
// Semua route di bawah prefix /perpustakaan, berbagi sesi dengan sistem bengkel.
// Login dilakukan melalui halaman login utama (/login), bukan login terpisah.
Route::prefix('perpustakaan')->name('perpustakaan.')->group(function () {

    // ── Redirect root perpustakaan ──
    Route::get('/', function () {
        return redirect('/');
    });

    // ── Admin Perpustakaan ────────────────────────────────────────────────────
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['role.perpus:admin'])
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

            // Manajemen Buku (CRUD)
            Route::resource('buku', AdminBuku::class)->parameters(['buku' => 'buku']);

            // Manajemen Anggota (User Siswa) - Read-only untuk Admin Perpustakaan
            Route::get('users', [AdminUser::class, 'index'])->name('users.index');

            // Peminjaman — lihat semua transaksi
            Route::get('/peminjaman', [AdminPeminjaman::class, 'index'])->name('peminjaman.index');

            // Pengembalian — proses kembali + denda
            Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
            Route::post('/pengembalian/{id}/proses', [PengembalianController::class, 'proses'])->name('pengembalian.proses');

            // Laporan
            Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
            Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

            // Manajemen Jurusan
            Route::resource('jurusan', JurusanController::class)->except(['show', 'create', 'edit']);
        });

    // ── Siswa Perpustakaan ────────────────────────────────────────────────────
    Route::prefix('siswa')
        ->name('siswa.')
        ->middleware(['role.perpus:siswa'])
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');

            // Katalog Buku
            Route::get('/buku', [SiswaBuku::class, 'index'])->name('buku.index');

            // Peminjaman
            Route::get('/peminjaman/buat', [SiswaPeminjaman::class, 'create'])->name('peminjaman.create');
            Route::post('/peminjaman', [SiswaPeminjaman::class, 'store'])->name('peminjaman.store');

            // Riwayat Peminjaman
            Route::get('/riwayat', [SiswaPeminjaman::class, 'riwayat'])->name('riwayat');

            // Kembalikan Buku (dari riwayat)
            Route::post('/peminjaman/{id}/kembali', [SiswaPeminjaman::class, 'prosesKembali'])->name('peminjaman.kembali');

            // Perpanjang Peminjaman Buku
            Route::post('/peminjaman/{id}/perpanjang', [SiswaPeminjaman::class, 'perpanjang'])->name('peminjaman.perpanjang');

            // Keranjang Buku
            Route::post('/keranjang/tambah', [SiswaDashboard::class, 'addToCart'])->name('keranjang.tambah');
            Route::post('/keranjang/update/{bukuId}', [SiswaDashboard::class, 'updateCart'])->name('keranjang.update');
            Route::delete('/keranjang/{bukuId}', [SiswaDashboard::class, 'removeFromCart'])->name('keranjang.hapus');
            Route::get('/keranjang/data', [SiswaDashboard::class, 'getCart'])->name('keranjang.data');

            // API Real-time Data
            Route::get('/api/data', [SiswaDashboard::class, 'apiData'])->name('api.data');
        });
});
