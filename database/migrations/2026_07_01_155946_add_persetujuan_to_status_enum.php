<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('dipinjam', 'menunggu_persetujuan', 'menunggu_konfirmasi', 'dikembalikan', 'ditolak') NOT NULL DEFAULT 'menunggu_persetujuan'");
        
        DB::statement("ALTER TABLE perpus_peminjamans MODIFY COLUMN status ENUM('dipinjam', 'menunggu_persetujuan', 'menunggu_konfirmasi', 'dikembalikan', 'menunggu_perpanjangan', 'ditolak') NOT NULL DEFAULT 'menunggu_persetujuan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_enum', function (Blueprint $table) {
            //
        });
    }
};
