<?php

namespace App\Console\Commands;

use App\Mail\KeterlambatanReminder;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KirimNotifikasiKeterlambatan extends Command
{
    protected $signature = 'notif:keterlambatan';
    protected $description = 'Kirim email peringatan ke siswa yang terlambat mengembalikan alat';

    public function handle(): int
    {
        // Ambil semua peminjaman yang sudah melewati batas kembali dan masih berstatus dipinjam
        $peminjamans = Peminjaman::with(['user', 'barang'])
            ->where('status', 'dipinjam')
            ->whereDate('batas_kembali', '<', today())
            ->get();

        if ($peminjamans->isEmpty()) {
            $this->info('Tidak ada keterlambatan pengembalian saat ini.');
            return self::SUCCESS;
        }

        $count = 0;
        foreach ($peminjamans as $p) {
            $hariTerlambat  = (int) abs(now()->startOfDay()->diffInDays($p->batas_kembali));
            $batasFormatted = $p->batas_kembali->translatedFormat('d F Y');

            if ($p->user->email && filter_var($p->user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($p->user->email)->send(new KeterlambatanReminder(
                        namaSiswa:    $p->user->name,
                        namaBarang:   $p->barang->nama_barang,
                        batasKembali: $batasFormatted,
                        hariTerlambat: $hariTerlambat,
                        jumlah:       $p->jumlah,
                    ));
                    $this->info("🚨 Email keterlambatan terkirim → {$p->user->name} ({$p->user->email}) | {$p->barang->nama_barang} | Terlambat {$hariTerlambat} hari");
                    $count++;
                } catch (\Exception $e) {
                    $this->warn("⚠️  Gagal kirim email ke {$p->user->email}: " . $e->getMessage());
                }
            } else {
                $this->warn("⚠️  {$p->user->name} tidak punya email valid.");
            }
        }

        $this->info("Total email keterlambatan terkirim: {$count}");
        return self::SUCCESS;
    }
}
