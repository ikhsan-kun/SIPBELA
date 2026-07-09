<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok',
        'kondisi',
        'jumlah_dipakai',
        'batas_pemakaian',
        'deskripsi',
    ];

    // Helper: Cek apakah butuh diservis/dikalibrasi
    public function butuhMaintenance(): bool
    {
        return $this->batas_pemakaian > 0 && $this->jumlah_dipakai >= $this->batas_pemakaian;
    }


    // Relasi: satu barang bisa dipinjam berkali-kali
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Helper: peminjaman yang sedang aktif
    public function peminjamanAktif()
    {
        return $this->peminjamans()->whereIn('status', ['dipinjam', 'menunggu_konfirmasi']);
    }

    // Scope: barang yang tersedia (stok > 0 & kondisi baik & belum batas servis)
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0)
                     ->where('kondisi', 'baik')
                     ->where(function($q) {
                         $q->where('batas_pemakaian', 0)
                           ->orWhereColumn('jumlah_dipakai', '<', 'batas_pemakaian');
                     });
    }
}
