<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->integer('jumlah_dipakai')->default(0)->after('kondisi');
            $table->integer('batas_pemakaian')->default(50)->after('jumlah_dipakai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['jumlah_dipakai', 'batas_pemakaian']);
        });
    }
};
