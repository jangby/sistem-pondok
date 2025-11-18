<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Kesehatan (UKS)
        Schema::create('kesehatan_santris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            
            $table->string('keluhan'); // Cth: Demam, Sakit Gigi
            $table->text('tindakan')->nullable(); // Cth: Diberi obat, Istirahat
            $table->enum('status', ['sakit_ringan', 'dirawat_di_asrama', 'rujuk_rs', 'sembuh'])->default('sakit_ringan');
            $table->date('tanggal_sakit');
            $table->date('tanggal_sembuh')->nullable();
            
            $table->timestamps();
        });

        // 2. Tabel Perizinan (Pulang/Keluar)
        Schema::create('perizinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            
            $table->enum('jenis_izin', ['pulang', 'keluar_sebentar', 'sakit_pulang']);
            $table->text('alasan');
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_selesai_rencana'); // Kapan harus balik
            $table->dateTime('tgl_kembali_realisasi')->nullable(); // Kapan beneran balik
            
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'kembali', 'terlambat'])->default('pending');
            
            // User (Pengurus/Admin) yang menyetujui
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });

        // 3. Tabel Absensi Harian
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            
            $table->enum('kategori', ['asrama', 'jamaah_subuh', 'jamaah_maghrib', 'jamaah_isya', 'sekolah', 'kegiatan_khusus']);
            $table->string('nama_kegiatan')->nullable(); // Opsional jika kategori 'kegiatan_khusus'
            
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa', 'terlambat']);
            $table->date('tanggal');
            $table->time('waktu_catat')->nullable(); // Jam berapa diabsen
            
            $table->foreignId('pencatat_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
        Schema::dropIfExists('perizinans');
        Schema::dropIfExists('kesehatan_santris');
    }
};