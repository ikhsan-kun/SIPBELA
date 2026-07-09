<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bengkel_notifications', function (Blueprint $table) {
            $table->id();
            // Nullable: null = broadcast to all admins, filled = specific siswa
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            // Target role: 'siswa', 'admin_bengkel', 'superadmin'
            $table->string('target_role');
            // Type of event
            $table->string('type'); // request_submitted, request_approved, request_rejected, return_submitted, return_confirmed
            $table->string('title');
            $table->text('message');
            // Extra data as JSON (peminjaman_id, barang_name, user_name, etc.)
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bengkel_notifications');
    }
};
