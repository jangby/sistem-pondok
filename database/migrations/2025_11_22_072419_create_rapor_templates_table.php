<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapor_templates', function (Blueprint $table) {
            $table->id();
            // Menghubungkan template ke pondok tertentu (Multi-tenant)
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            
            $table->string('nama_template'); // Contoh: "Rapor UTS Semester Ganjil"
            $table->longText('konten_html')->nullable(); // Menyimpan desain (Codingan HTML dari Editor)
            
            // Konfigurasi Kertas
            $table->string('ukuran_kertas')->default('A4'); // A4, F4, Legal, A5
            $table->string('orientasi')->default('portrait'); // portrait atau landscape
            
            // Opsional: Margin (dalam mm) agar pas saat dicetak
            $table->integer('margin_top')->default(10);
            $table->integer('margin_bottom')->default(10);
            $table->integer('margin_left')->default(10);
            $table->integer('margin_right')->default(10);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapor_templates');
    }
};