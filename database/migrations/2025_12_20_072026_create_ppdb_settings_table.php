<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran'); // Contoh: 2025/2026
            $table->string('nama_gelombang'); // Contoh: Gelombang 1
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->decimal('biaya_pendaftaran', 15, 2)->default(0);
            $table->text('deskripsi')->nullable(); // Info tambahan untuk pendaftar
            $table->string('brosur')->nullable(); // Upload file brosur
            $table->boolean('is_active')->default(false); // Hanya satu yang boleh aktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_settings');
    }
};