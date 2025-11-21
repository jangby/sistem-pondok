<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_mengajars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_diniyah_id')->constrained('jadwal_diniyahs')->cascadeOnDelete();
            $table->foreignId('ustadz_id')->constrained('ustadzs')->cascadeOnDelete();
            $table->date('tanggal');
            
            $table->string('materi'); // Contoh: "Bab Wudhu", "Kitab Gundul Hal 5"
            $table->text('catatan')->nullable(); // Catatan khusus kejadian di kelas
            $table->string('foto_kegiatan')->nullable(); // Opsional: Bukti foto
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_mengajars');
    }
};