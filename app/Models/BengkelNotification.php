<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BengkelNotification extends Model
{
    protected $table = 'bengkel_notifications';

    protected $fillable = [
        'user_id',
        'target_role',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    // ── Static helpers to create notifications easily ──────────────────────

    /**
     * Notify all Admin Bengkel when a siswa submits a borrow request.
     */
    public static function adminBorrowRequest(User $siswa, Peminjaman $peminjaman, string $barangNames): void
    {
        self::create([
            'user_id'     => null,
            'target_role' => 'admin_bengkel',
            'type'        => 'request_submitted',
            'title'       => '📋 Pengajuan Peminjaman Baru',
            'message'     => "{$siswa->name} mengajukan peminjaman: {$barangNames}",
            'data'        => [
                'peminjaman_id' => $peminjaman->id,
                'user_name'     => $siswa->name,
                'barang'        => $barangNames,
            ],
        ]);
    }

    /**
     * Notify a specific siswa that their request was approved.
     */
    public static function siswaRequestApproved(int $userId, string $barangName, int $jumlah): void
    {
        self::create([
            'user_id'     => $userId,
            'target_role' => 'siswa',
            'type'        => 'request_approved',
            'title'       => '✅ Peminjaman Disetujui',
            'message'     => "Peminjaman {$jumlah} unit \"{$barangName}\" telah disetujui oleh admin. Silakan ambil alatnya.",
            'data'        => ['barang' => $barangName, 'jumlah' => $jumlah],
        ]);
    }

    /**
     * Notify a specific siswa that their request was rejected.
     */
    public static function siswaRequestRejected(int $userId, string $barangName): void
    {
        self::create([
            'user_id'     => $userId,
            'target_role' => 'siswa',
            'type'        => 'request_rejected',
            'title'       => '❌ Peminjaman Ditolak',
            'message'     => "Maaf, pengajuan peminjaman \"{$barangName}\" ditolak oleh admin.",
            'data'        => ['barang' => $barangName],
        ]);
    }

    /**
     * Notify all Admin Bengkel when a siswa submits a return request.
     */
    public static function adminReturnRequest(User $siswa, Peminjaman $peminjaman, string $barangName): void
    {
        self::create([
            'user_id'     => null,
            'target_role' => 'admin_bengkel',
            'type'        => 'return_submitted',
            'title'       => '🔄 Pengembalian Menunggu Konfirmasi',
            'message'     => "{$siswa->name} mengajukan pengembalian: \"{$barangName}\"",
            'data'        => [
                'peminjaman_id' => $peminjaman->id,
                'user_name'     => $siswa->name,
                'barang'        => $barangName,
            ],
        ]);
    }

    /**
     * Notify a specific siswa that their return was confirmed.
     */
    public static function siswaReturnConfirmed(int $userId, string $barangName, int $jumlah): void
    {
        self::create([
            'user_id'     => $userId,
            'target_role' => 'siswa',
            'type'        => 'return_confirmed',
            'title'       => '✅ Pengembalian Dikonfirmasi',
            'message'     => "Pengembalian {$jumlah} unit \"{$barangName}\" telah dikonfirmasi. Terima kasih!",
            'data'        => ['barang' => $barangName, 'jumlah' => $jumlah],
        ]);
    }

    /**
     * Notify siswa that their borrow request is waiting for admin approval.
     */
    public static function siswaPeminjamanMenunggu(int $userId, string $barangNames): void
    {
        self::create([
            'user_id'     => $userId,
            'target_role' => 'siswa',
            'type'        => 'request_submitted',
            'title'       => '⏳ Peminjaman Menunggu Konfirmasi',
            'message'     => "Pengajuan peminjaman: {$barangNames} sedang menunggu persetujuan admin. Harap tunggu konfirmasi.",
            'data'        => ['barang' => $barangNames],
        ]);
    }

    /**
     * Notify siswa H-1 before batas_kembali (reminder to return tomorrow).
     */
    public static function siswaHampirJatuhTempo(int $userId, string $barangName, string $batasKembali): void
    {
        self::create([
            'user_id'     => $userId,
            'target_role' => 'siswa',
            'type'        => 'due_soon',
            'title'       => '⚠️ Pengingat: Batas Kembali Besok!',
            'message'     => "Alat \"{$barangName}\" harus dikembalikan besok ({$batasKembali}). Segera kembalikan ke admin bengkel.",
            'data'        => ['barang' => $barangName, 'batas_kembali' => $batasKembali],
        ]);
    }
}
