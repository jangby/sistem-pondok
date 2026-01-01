<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Kita gunakan raw statement karena mengubah ENUM di Laravel kadang bermasalah dengan Doctrine
        DB::statement("ALTER TABLE calon_santris MODIFY COLUMN status_pembayaran ENUM('belum_bayar', 'sebagian', 'lunas') DEFAULT 'belum_bayar'");
    }

    public function down(): void
    {
        // Kembalikan ke semula jika di-rollback (opsional)
        DB::statement("ALTER TABLE calon_santris MODIFY COLUMN status_pembayaran ENUM('belum_bayar', 'lunas') DEFAULT 'belum_bayar'");
    }
};