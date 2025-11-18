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
        Schema::create('nilais', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
    $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete(); //
    $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
    $table->foreignId('guru_user_id')->constrained('users')->cascadeOnDelete(); //
    
    // Opsional, jika nilai ini terikat pada ujian tertentu
    $table->foreignId('kegiatan_akademik_id')->nullable()->constrained('kegiatan_akademiks')->nullOnDelete(); 
    
    $table->enum('tipe_nilai', ['Tugas', 'Harian', 'UTS', 'UAS', 'Sikap']);
    $table->decimal('nilai', 5, 2); // Cth: 85.50
    $table->text('keterangan')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
