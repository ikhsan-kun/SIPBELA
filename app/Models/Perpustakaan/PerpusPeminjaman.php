<?php

namespace App\Models\Perpustakaan;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PerpusPeminjaman extends Model
{
    protected $table = 'perpus_peminjamans';

    protected $fillable = [
        'user_id',
        'buku_id',
        'jumlah',
        'tanggal_pinjam',
        'batas_kembali',
        'status',
        'catatan',
        'jumlah_perpanjangan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'batas_kembali'  => 'date',
    ];

    // Relasi ke anggota perpustakaan
    public function user()
    {
        return $this->belongsTo(PerpusUser::class, 'user_id');
    }

    // Relasi ke buku
    public function buku()
    {
        return $this->belongsTo(PerpusBuku::class, 'buku_id');
    }

    // Relasi ke pengembalian (1:1)
    public function pengembalian()
    {
        return $this->hasOne(PerpusPengembalian::class, 'peminjaman_id');
    }

    // Helper: apakah sudah melewati batas kembali
    public function isTerlambat(): bool
    {
        return in_array($this->status, ['dipinjam', 'menunggu_konfirmasi']) && now()->startOfDay()->gt($this->batas_kembali);
    }

    // Helper: hitung jumlah hari terlambat (dari sekarang)
    public function hariTerlambatSekarang(): int
    {
        if (! $this->isTerlambat()) {
            return 0;
        }
        return (int) abs(now()->startOfDay()->diffInDays($this->batas_kembali));
    }

    // Helper: apakah bisa diperpanjang
    public function bisaDiperpanjang(): bool
    {
        return $this->status === 'dipinjam' && $this->jumlah_perpanjangan == 0;
    }
}
