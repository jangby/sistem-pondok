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
    Schema::table('gurus', function (Blueprint $table) {
        // 'full_time' = Ikut aturan sekolah
        // 'flexi' = Ikut jadwal mengajar
        $table->enum('tipe_jam_kerja', ['full_time', 'flexi'])->default('full_time')->after('alamat');
    });
}

public function down(): void
{
    Schema::table('gurus', function (Blueprint $table) {
        $table->dropColumn('tipe_jam_kerja');
    });
}
};
