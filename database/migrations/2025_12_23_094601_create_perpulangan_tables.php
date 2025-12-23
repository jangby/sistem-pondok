<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master Acara Perpulangan
        Schema::create('perpulangan_events', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Cth: Libur Semester Ganjil 2025/2026
            $table->date('tanggal_mulai'); // Tanggal santri boleh mulai pulang
            $table->date('tanggal_akhir'); // Tanggal santri harus kembali
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true); // Hanya satu event aktif dalam satu waktu
            $table->timestamps();
        });

        // 2. Tabel Record/Transaksi Santri per Event
        Schema::create('perpulangan_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perpulangan_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            
            // Token unik untuk QR Code (Misal: Gabungan EventID + SantriID + RandomString)
            // Ini yang discan, jadi tiap event kodenya beda.
            $table->string('qr_token')->unique(); 
            
            // Status Alur:
            // 0 = Belum Pulang (Masih di Pondok / Kartu dicetak)
            // 1 = Pulang (Sudah Scan Keluar)
            // 2 = Kembali (Sudah Scan Masuk)
            $table->tinyInteger('status')->default(0); 

            $table->dateTime('waktu_keluar')->nullable(); // Waktu scan di gerbang keluar
            $table->dateTime('waktu_kembali')->nullable(); // Waktu scan di gerbang masuk
            
            // Indikator terlambat otomatis saat scan masuk
            $table->boolean('is_late')->default(false); 
            
            // Petugas yang menjaga gerbang saat scan
            $table->foreignId('petugas_keluar_id')->nullable()->constrained('users');
            $table->foreignId('petugas_masuk_id')->nullable()->constrained('users');
            
            $table->timestamps();

            // Mencegah duplikasi santri dalam satu event yang sama
            $table->unique(['perpulangan_event_id', 'santri_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perpulangan_records');
        Schema::dropIfExists('perpulangan_events');
    }
};