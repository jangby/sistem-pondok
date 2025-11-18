<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Pengaturan Waktu Absen (Pagi/Malam)
        Schema::create('absensi_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('jenis'); // 'asrama_pagi', 'asrama_malam'
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });

        // 2. Tabel Hari Libur
        Schema::create('libur_pondoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('keterangan')->nullable(); // Misal: "Libur Maulid"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libur_pondoks');
        Schema::dropIfExists('absensi_settings');
    }
};