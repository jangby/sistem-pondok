<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_biayas', function (Blueprint $table) {
            // Kita tambahkan kolom enum baru setelah kolom nominal
            $table->enum('kategori', [
                'yayasan',      // Pendaftaran, Bangunan, Taaruf
                'pondok',       // Makan, SPP, Syariah
                'usaha',        // Kitab, Laundry, Koperasi
                'panitia'       // Seragam, Atribut, Kegiatan
            ])->default('yayasan')->after('nominal');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_biayas', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};