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
        // 1. Tabel Buku (Top Level)
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->onDelete('cascade'); // Relasi ke Pondok
            $table->string('title'); // Judul Buku
            $table->string('author_name')->nullable(); // Nama Penulis/Penyusun
            $table->text('description')->nullable(); // Deskripsi singkat
            $table->string('cover_image')->nullable(); // Path gambar sampul
            
            // Konten Statis Buku
            $table->longText('preface')->nullable(); // Kata Pengantar
            $table->longText('closing')->nullable(); // Kata Penutup / Khotimah
            
            // Status: draft (masih diedit), published (siap cetak/baca)
            $table->enum('status', ['draft', 'published'])->default('draft');
            
            $table->timestamps();
        });

        // 2. Tabel Bab (Middle Level) - Pengelompokan Doa
        Schema::create('book_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->string('title'); // Contoh: "Bab I: Thaharah", "Bab II: Sholat"
            $table->integer('sequence')->default(0); // Untuk urutan Bab (1, 2, 3...)
            $table->timestamps();
        });

        // 3. Tabel Item Doa (Content Level) - Isi Doanya
        Schema::create('book_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('book_chapters')->onDelete('cascade');
            
            $table->string('title'); // Judul Doa, misal: "Doa Masuk Masjid"
            
            // Konten Utama
            $table->longText('arabic_content')->nullable(); // Teks Arab
            $table->longText('translation_content')->nullable(); // Terjemahan / Latin
            
            // Tipe konten: 'doa' (format standar), 'text' (hanya teks bebas/penjelasan)
            $table->enum('type', ['doa', 'text'])->default('doa'); 
            
            $table->integer('sequence')->default(0); // Urutan doa di dalam Bab
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_items');
        Schema::dropIfExists('book_chapters');
        Schema::dropIfExists('books');
    }
};