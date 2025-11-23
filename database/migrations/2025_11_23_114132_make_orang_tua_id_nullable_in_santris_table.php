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
    Schema::table('santris', function (Blueprint $table) {
        // Ubah kolom orang_tua_id menjadi nullable (boleh kosong)
        $table->foreignId('orang_tua_id')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('santris', function (Blueprint $table) {
        // Kembalikan ke tidak boleh kosong (jika rollback)
        // Note: Ini akan error jika ada data yang null saat rollback
        $table->foreignId('orang_tua_id')->nullable(false)->change();
    });
}
};
