<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- IMPORT DB

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan 'canceled' ke daftar ENUM
        DB::statement("ALTER TABLE pembayaran_transaksis CHANGE COLUMN status_verifikasi status_verifikasi ENUM('pending', 'verified', 'rejected', 'canceled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Kembalikan ke kondisi semula
        DB::statement("ALTER TABLE pembayaran_transaksis CHANGE COLUMN status_verifikasi status_verifikasi ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'");
    }
};