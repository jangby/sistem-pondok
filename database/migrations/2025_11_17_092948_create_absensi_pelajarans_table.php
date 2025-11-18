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
        Schema::create('absensi_pelajarans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('jadwal_pelajaran_id')->constrained('jadwal_pelajarans')->cascadeOnDelete();
    $table->date('tanggal');
    
    // Status Kehadiran Guru di Kelas
    $table->enum('status_guru', ['hadir', 'terlambat', 'alpa', 'sakit', 'izin'])->default('alpa');
    $table->time('jam_guru_masuk_kelas')->nullable(); // Verifikasi Geofence
    $table->text('materi_pembahasan')->nullable(); // Diisi oleh guru
    
    $table->timestamps();
    
    $table->unique(['jadwal_pelajaran_id', 'tanggal']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_pelajarans');
    }
};
