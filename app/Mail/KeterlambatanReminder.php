<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KeterlambatanReminder extends Mailable
{
    use Queueable, SerializesModels;

    public string $namaSiswa;
    public string $namaBarang;
    public string $batasKembali;
    public int $hariTerlambat;
    public int $jumlah;

    public function __construct(string $namaSiswa, string $namaBarang, string $batasKembali, int $hariTerlambat, int $jumlah)
    {
        $this->namaSiswa     = $namaSiswa;
        $this->namaBarang    = $namaBarang;
        $this->batasKembali  = $batasKembali;
        $this->hariTerlambat = $hariTerlambat;
        $this->jumlah        = $jumlah;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚨 PENTING: Keterlambatan Pengembalian Alat – SIPBELA SMK Ma\'arif Talang',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.peminjaman.keterlambatan',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
