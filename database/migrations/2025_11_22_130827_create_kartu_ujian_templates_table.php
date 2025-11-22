<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_ujian_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('nama_template'); // Contoh: "Kartu Ujian UAS Ganjil"
            $table->longText('konten_html')->nullable();
            
            // Setting Kertas (Biasanya A5 atau setengah A4 untuk kartu)
            $table->string('ukuran_kertas')->default('A5'); 
            $table->string('orientasi')->default('portrait');
            
            // Margin
            $table->integer('margin_top')->default(5);
            $table->integer('margin_bottom')->default(5);
            $table->integer('margin_left')->default(10);
            $table->integer('margin_right')->default(10);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_ujian_templates');
    }
};