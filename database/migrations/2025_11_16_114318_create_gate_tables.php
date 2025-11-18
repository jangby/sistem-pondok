<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pengaturan Gerbang (Waktu & No WA Satpam)
        Schema::create('gate_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->integer('max_minutes_out')->default(15); // Batas waktu keluar (menit)
            $table->string('satpam_wa_number')->nullable(); // No WA Satpam
            $table->boolean('auto_notify')->default(true); // Fitur aktif/mati
            $table->timestamps();
        });

        // 2. Log Aktivitas Gerbang (Siapa yang sedang diluar)
        Schema::create('gate_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            
            $table->dateTime('out_time'); // Jam Keluar
            $table->dateTime('in_time')->nullable(); // Jam Masuk (Null = Sedang Diluar)
            
            $table->boolean('is_late')->default(false); // Apakah terlambat?
            $table->boolean('notified')->default(false); // Apakah satpam sudah di-WA?
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gate_logs');
        Schema::dropIfExists('gate_settings');
    }
};