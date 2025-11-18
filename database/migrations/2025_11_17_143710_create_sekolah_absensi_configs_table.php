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
        // Tabel ini menyimpan pengaturan jam & hari kerja (hanya 1 baris per sekolah)
        Schema::create('sekolah_absensi_settings', function (Blueprint $table) {
            $table->id();
            // Relasi 1-ke-1 dengan sekolah
            $table->foreignId('sekolah_id')->constrained('sekolahs')->cascadeOnDelete();
            
            // Pengaturan Jam
            $table->time('jam_masuk')->default('06:30:00');
            $table->time('batas_telat')->default('07:00:00');
            $table->time('jam_pulang_awal')->default('14:00:00');
            $table->time('jam_pulang_akhir')->default('17:00:00');
            
            // Pengaturan Hari Kerja (disimpan sebagai JSON)
            // Cth: ["Senin", "Selasa", "Rabu", "Kamis", "Sabtu", "Minggu"]
            $table->json('hari_kerja')->nullable(); 
            
            $table->timestamps();
        });
        
        // Tabel ini menyimpan daftar hari libur (banyak baris per sekolah)
        Schema::create('sekolah_hari_libur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolahs')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('keterangan');
            $table->timestamps();
            
            $table->unique(['sekolah_id', 'tanggal']); // Tidak boleh ada tanggal libur ganda
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah_hari_libur');
        Schema::dropIfExists('sekolah_absensi_settings');
    }
};