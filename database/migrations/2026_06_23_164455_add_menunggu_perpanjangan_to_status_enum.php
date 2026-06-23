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
        DB::statement("ALTER TABLE perpus_peminjamans MODIFY COLUMN status ENUM('dipinjam', 'menunggu_konfirmasi', 'dikembalikan', 'menunggu_perpanjangan') NOT NULL DEFAULT 'dipinjam'");
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
