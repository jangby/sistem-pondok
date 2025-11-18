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
        Schema::create('kegiatan_akademiks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
    $table->foreignId('sekolah_id')->constrained('sekolahs')->cascadeOnDelete();
    $table->string('nama_kegiatan'); // Cth: "Ujian Tengah Semester Ganjil"
    $table->enum('tipe', ['UTS', 'UAS', 'Harian', 'Lainnya']);
    $table->date('tanggal_mulai');
    $table->date('tanggal_selesai');
    $table->text('keterangan')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_akademiks');
    }
};
