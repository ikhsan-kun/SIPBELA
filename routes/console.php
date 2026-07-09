<?php

use App\Console\Commands\KirimNotifikasiJatuhTempo;
use App\Console\Commands\KirimNotifikasiKeterlambatan;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Kirim notifikasi H-1 batas kembali setiap hari jam 07:00 pagi
Schedule::command('notif:jatuh-tempo')->dailyAt('07:00');

// Kirim email peringatan keterlambatan setiap hari jam 08:00 pagi
Schedule::command('notif:keterlambatan')->dailyAt('08:00');

