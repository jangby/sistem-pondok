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
        Schema::create('absensi_gurus', function (Blueprint $table) {
    $table->id();
    $table->foreignId('guru_user_id')->constrained('users')->cascadeOnDelete(); //
    $table->foreignId('sekolah_id')->constrained('sekolahs')->cascadeOnDelete();
    $table->date('tanggal');
    $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('alpa');
    $table->time('jam_masuk')->nullable();
    $table->time('jam_pulang')->nullable();
    $table->string('verifikasi_masuk')->nullable(); // Cth: "WiFi: NAMA_WIFI"
    $table->string('verifikasi_pulang')->nullable();
    $table->text('keterangan')->nullable();
    $table->timestamps();

    $table->unique(['guru_user_id', 'tanggal']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_gurus');
    }
};
