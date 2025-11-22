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
        Schema::table('jurnal_pendidikans', function (Blueprint $table) {
            // Kategori spesifik: Quran, Hadits, Kitab, dll
            $table->enum('kategori_hafalan', ['quran', 'hadits', 'kitab', 'doa'])
                  ->nullable()
                  ->after('jenis')
                  ->comment('Khusus jika jenis=hafalan');

            // Jenis setoran: Tambah Baru atau Mengulang
            $table->enum('jenis_setoran', ['ziyadah', 'murojaah'])
                  ->nullable()
                  ->after('kategori_hafalan');

            // Detail batasan setoran
            // Kita pakai string agar fleksibel (bisa angka ayat, atau nomor halaman)
            $table->string('start_at')->nullable()->after('materi')->comment('Ayat/Halaman Awal');
            $table->string('end_at')->nullable()->after('start_at')->comment('Ayat/Halaman Akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_pendidikans', function (Blueprint $table) {
            $table->dropColumn([
                'kategori_hafalan', 
                'jenis_setoran', 
                'start_at', 
                'end_at'
            ]);
        });
    }
};