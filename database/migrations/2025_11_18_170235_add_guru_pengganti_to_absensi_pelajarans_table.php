<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_pelajarans', function (Blueprint $table) {
            // Kolom untuk mencatat ID guru pengganti (jika ada)
            $table->foreignId('guru_pengganti_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
                  
            // Kolom status tambahan untuk menandakan ini kelas substitusi
            $table->boolean('is_substitute')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('absensi_pelajarans', function (Blueprint $table) {
            $table->dropForeign(['guru_pengganti_user_id']);
            $table->dropColumn(['guru_pengganti_user_id', 'is_substitute']);
        });
    }
};