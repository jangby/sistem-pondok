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
        Schema::create('absensi_siswa_pelajaran', function (Blueprint $table) {
    $table->id();
    $table->foreignId('absensi_pelajaran_id')->constrained('absensi_pelajarans')->cascadeOnDelete();
    $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete(); //
    
    // 'sakit' dan 'izin' akan diisi otomatis dari integrasi
    $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('alpa');
    $table->time('jam_hadir')->nullable(); // Diisi saat scan kartu
    $table->text('keterangan')->nullable();
    $table->timestamps();

    $table->unique(['absensi_pelajaran_id', 'santri_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_siswa_pelajaran');
    }
};
