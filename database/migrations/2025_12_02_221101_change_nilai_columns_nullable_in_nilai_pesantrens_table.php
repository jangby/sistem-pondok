<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nilai_pesantrens', function (Blueprint $table) {
            // Ubah kolom menjadi nullable agar bisa dibedakan antara "Belum Diisi" (NULL) dan "Nilai 0"
            // Kita hapus default(0)
            $table->decimal('nilai_tulis', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_lisan', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_praktek', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_kehadiran', 5, 2)->nullable()->default(null)->change();
            $table->decimal('nilai_akhir', 5, 2)->nullable()->default(null)->change();
        });

        // OPSIONAL: Konversi data lama yang bernilai '0.00' menjadi NULL
        // Agar progress bar langsung diperbaiki untuk data yang sudah ada.
        // Hapus bagian ini jika Anda yakin ada santri yang BENAR-BENAR mendapat nilai 0 asli.
        DB::statement("UPDATE nilai_pesantrens SET nilai_tulis = NULL WHERE nilai_tulis = 0");
        DB::statement("UPDATE nilai_pesantrens SET nilai_lisan = NULL WHERE nilai_lisan = 0");
        DB::statement("UPDATE nilai_pesantrens SET nilai_praktek = NULL WHERE nilai_praktek = 0");
        DB::statement("UPDATE nilai_pesantrens SET nilai_kehadiran = NULL WHERE nilai_kehadiran = 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_pesantrens', function (Blueprint $table) {
            // Kembalikan ke keadaan semula jika di-rollback
            // PERHATIAN: Data NULL akan diubah jadi 0
            DB::statement("UPDATE nilai_pesantrens SET nilai_tulis = 0 WHERE nilai_tulis IS NULL");
            DB::statement("UPDATE nilai_pesantrens SET nilai_lisan = 0 WHERE nilai_lisan IS NULL");
            DB::statement("UPDATE nilai_pesantrens SET nilai_praktek = 0 WHERE nilai_praktek IS NULL");
            
            $table->decimal('nilai_tulis', 5, 2)->default(0)->change();
            $table->decimal('nilai_lisan', 5, 2)->default(0)->change();
            $table->decimal('nilai_praktek', 5, 2)->default(0)->change();
            $table->decimal('nilai_kehadiran', 5, 2)->default(0)->change();
            $table->decimal('nilai_akhir', 5, 2)->default(0)->change();
        });
    }
};