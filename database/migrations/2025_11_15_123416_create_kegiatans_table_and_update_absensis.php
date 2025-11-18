<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Definisi Kegiatan
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('nama_kegiatan');
            
            // Jadwal
            $table->enum('frekuensi', ['harian', 'mingguan', 'bulanan', 'sekali_habis']);
            $table->json('detail_waktu')->nullable(); // Simpan: ["Senin", "Kamis"] atau ["Tanggal 5"]
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            
            // Peserta
            $table->enum('tipe_peserta', ['semua', 'kelas', 'khusus']);
            $table->json('detail_peserta')->nullable(); // Simpan ID Kelas [1, 2] atau ID Santri [10, 15]
            
            $table->timestamps();
        });

        // 2. Tambah kolom kegiatan_id di tabel absensi
        Schema::table('absensis', function (Blueprint $table) {
            $table->foreignId('kegiatan_id')->nullable()->after('nama_kegiatan')
                  ->constrained('kegiatans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeign(['kegiatan_id']);
            $table->dropColumn('kegiatan_id');
        });
        Schema::dropIfExists('kegiatans');
    }
};