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
    Schema::table('pembayaran_transaksis', function (Blueprint $table) {
        // Tambahkan relasi ke tagihan
        $table->foreignId('tagihan_id')
              ->nullable()
              ->after('orang_tua_id')
              ->constrained('tagihans')
              ->onDelete('set null'); // Jika tagihan dihapus, transaksi tetap ada
    });
}
public function down(): void
{
    Schema::table('pembayaran_transaksis', function (Blueprint $table) {
        $table->dropForeign(['tagihan_id']);
        $table->dropColumn('tagihan_id');
    });
}
};
