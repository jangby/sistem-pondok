<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom nilai_hafalan di tabel nilai
        Schema::table('nilai_pesantrens', function (Blueprint $table) {
            $table->decimal('nilai_hafalan', 5, 2)->nullable()->after('nilai_praktek');
        });

        // 2. Tambah penanda apakah mapel punya ujian hafalan
        Schema::table('mapel_diniyahs', function (Blueprint $table) {
            $table->boolean('uji_hafalan')->default(false)->after('uji_praktek');
        });

        // 3. Ubah ENUM kategori_tes di jadwal_ujian_diniyahs
        // Kita gunakan Raw Statement karena mengubah ENUM kadang bermasalah di Doctrine
        DB::statement("ALTER TABLE jadwal_ujian_diniyahs MODIFY COLUMN kategori_tes ENUM('tulis', 'lisan', 'praktek', 'hafalan') DEFAULT 'tulis'");
    }

    public function down(): void
    {
        Schema::table('nilai_pesantrens', function (Blueprint $table) {
            $table->dropColumn('nilai_hafalan');
        });

        Schema::table('mapel_diniyahs', function (Blueprint $table) {
            $table->dropColumn('uji_hafalan');
        });

        DB::statement("ALTER TABLE jadwal_ujian_diniyahs MODIFY COLUMN kategori_tes ENUM('tulis', 'lisan', 'praktek') DEFAULT 'tulis'");
    }
};