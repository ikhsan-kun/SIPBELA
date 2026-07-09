<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JatuhTempoReminder extends Mailable
{
    use Queueable, SerializesModels;

    public string $namaSiswa;
    public string $namaBarang;
    public string $batasKembali;
    public int $jumlah;

    public function __construct(string $namaSiswa, string $namaBarang, string $batasKembali, int $jumlah)
    {
        $this->namaSiswa   = $namaSiswa;
        $this->namaBarang  = $namaBarang;
        $this->batasKembali = $batasKembali;
        $this->jumlah      = $jumlah;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Pengingat: Batas Pengembalian Alat Besok – SIPBELA SMK Ma\'arif Talang',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.peminjaman.jatuh-tempo',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
