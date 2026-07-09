<?php

namespace App\Console\Commands;

use App\Mail\JatuhTempoReminder;
use App\Models\BengkelNotification;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KirimNotifikasiJatuhTempo extends Command
{
    protected $signature = 'notif:jatuh-tempo';
    protected $description = 'Kirim notifikasi & email ke siswa yang alatnya akan jatuh tempo besok (H-1)';

    public function handle(): int
    {
        $besok = Carbon::tomorrow()->toDateString();

        $peminjamans = Peminjaman::with(['user', 'barang'])
            ->where('status', 'dipinjam')
            ->whereDate('batas_kembali', $besok)
            ->get();

        if ($peminjamans->isEmpty()) {
            $this->info('Tidak ada peminjaman yang akan jatuh tempo besok.');
            return self::SUCCESS;
        }

        $count = 0;
        foreach ($peminjamans as $p) {
            // Cek apakah notif H-1 sudah dikirim hari ini
            $alreadySent = BengkelNotification::where('user_id', $p->user_id)
                ->where('type', 'due_soon')
                ->whereDate('created_at', today())
                ->where('data->barang', $p->barang->nama_barang)
                ->exists();

            if ($alreadySent) {
                $this->line("Notif sudah dikirim hari ini: {$p->barang->nama_barang} ({$p->user->name})");
                continue;
            }

            $batasFormatted = $p->batas_kembali->translatedFormat('d F Y');

            // 1. Kirim notifikasi in-app (bell icon)
            BengkelNotification::siswaHampirJatuhTempo(
                $p->user_id,
                $p->barang->nama_barang,
                $batasFormatted
            );

            // 2. Kirim email jika user punya email valid
            if ($p->user->email && filter_var($p->user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($p->user->email)->send(new JatuhTempoReminder(
                        namaSiswa:   $p->user->name,
                        namaBarang:  $p->barang->nama_barang,
                        batasKembali: $batasFormatted,
                        jumlah:      $p->jumlah,
                    ));
                    $this->info("✅ Email terkirim ke: {$p->user->email} ({$p->user->name}) → {$p->barang->nama_barang}");
                } catch (\Exception $e) {
                    $this->warn("⚠️  Gagal kirim email ke {$p->user->email}: " . $e->getMessage());
                }
            } else {
                $this->warn("⚠️  User {$p->user->name} tidak punya email valid, skip kirim email.");
            }

            $count++;
        }

        $this->info("Total notifikasi H-1 terkirim: {$count}");
        return self::SUCCESS;
    }
}
