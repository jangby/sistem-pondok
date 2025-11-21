<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_ujian_diniyahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('mustawa_id')->constrained('mustawas')->cascadeOnDelete();
            $table->foreignId('mapel_diniyah_id')->constrained('mapel_diniyahs')->cascadeOnDelete();
            $table->foreignId('pengawas_id')->constrained('ustadzs')->cascadeOnDelete(); // Bisa beda dgn guru harian
            
            // Identitas Ujian
            $table->enum('jenis_ujian', ['uts', 'uas']); // UTS / UAS
            $table->enum('semester', ['ganjil', 'genap']);
            $table->string('tahun_ajaran', 20); // Contoh: 2024/2025
            
            // Detail Waktu & Teknis
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('kategori_tes', ['tulis', 'lisan', 'praktek'])->default('tulis');
            
            $table->string('ruangan')->nullable(); // Opsional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_ujian_diniyahs');
    }
};