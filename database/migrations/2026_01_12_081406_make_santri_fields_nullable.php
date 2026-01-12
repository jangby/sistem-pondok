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
        Schema::table('santris', function (Blueprint $table) {
            // 1. Ubah NIK & No KK agar boleh kosong (NULL)
            // Kita gunakan ->change() karena kolomnya sudah ada
            $table->string('nik')->nullable()->change();
            $table->string('no_kk')->nullable()->change();

            // 2. Buat Kolom NISN (Karena error menunjukkan kolom ini belum ada)
            // Kita cek dulu biar aman
            if (!Schema::hasColumn('santris', 'nisn')) {
                $table->string('nisn')->nullable()->after('nis');
            } else {
                $table->string('nisn')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            // Kembalikan seperti semula (opsional, sesuaikan kebutuhan)
            $table->string('nik')->nullable(false)->change();
            $table->string('no_kk')->nullable(false)->change();
            
            // Hapus nisn jika rollback
            if (Schema::hasColumn('santris', 'nisn')) {
                $table->dropColumn('nisn');
            }
        });
    }
};