<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Matikan Strict Mode sementara agar MySQL tidak rewel
        \Illuminate\Support\Facades\DB::statement("SET SESSION sql_mode = ''");

        // 2. Ubah kolom 'status' menjadi VARCHAR (Teks biasa)
        // Ini akan membuang batasan ENUM sehingga data 'terlambat' atau error lainnya bisa ditangani
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE absensi_gurus MODIFY COLUMN status VARCHAR(50) NULL");

        // 3. Bersihkan Data (Sekarang aman karena kolomnya sudah jadi Teks)
        // Ubah yang kosong, NULL, atau tidak dikenal menjadi 'alfa'
        \Illuminate\Support\Facades\DB::update("UPDATE absensi_gurus SET status = 'alfa' WHERE status IS NULL OR status = ''");
        
        // Opsional: Jika ada teks 'terlambat' yang tersangkut, biarkan saja karena akan masuk ke ENUM baru nanti.
        // Tapi jika ada teks aneh lain (misal: 'x', 'y'), ubah ke alfa:
        \Illuminate\Support\Facades\DB::update("UPDATE absensi_gurus SET status = 'alfa' WHERE status NOT IN ('hadir', 'sakit', 'izin', 'alfa', 'terlambat')");

        // 4. Kembalikan ke ENUM dengan definisi BARU (+terlambat)
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE absensi_gurus MODIFY COLUMN status ENUM('hadir', 'sakit', 'izin', 'alfa', 'terlambat') NOT NULL DEFAULT 'alfa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {
            //
        });
    }
};
