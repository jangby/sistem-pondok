<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Asrama
        Schema::create('asramas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('nama_asrama'); // Cth: Al-Farabi 1
            $table->string('komplek');     // Cth: Komplek A
            $table->string('ketua_asrama'); // Nama Ketua
            $table->integer('kapasitas');   // Cth: 20
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']); // Khusus Putra/Putri
            $table->timestamps();
        });

        // 2. Update Santri (Link ke Asrama)
        Schema::table('santris', function (Blueprint $table) {
            $table->foreignId('asrama_id')->nullable()->after('pondok_id')
                  ->constrained('asramas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropForeign(['asrama_id']);
            $table->dropColumn('asrama_id');
        });
        Schema::dropIfExists('asramas');
    }
};