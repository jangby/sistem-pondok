<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_ujian_diniyahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_ujian_diniyah_id')->constrained('jadwal_ujian_diniyahs')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->enum('status', ['H', 'I', 'S', 'A'])->default('A'); // Hadir, Izin, Sakit, Alpha
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_ujian_diniyahs');
    }
};