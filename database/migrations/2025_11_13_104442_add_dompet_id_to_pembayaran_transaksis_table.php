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
        // Kolom ini akan diisi jika transaksinya adalah TOP-UP DOMPET
        $table->foreignId('dompet_id')->nullable()->after('tagihan_id')
              ->constrained('dompets')->onDelete('set null');

        // Kita juga buat tagihan_id nullable, karena transaksi bisa
        // berupa Top-Up (tagihan_id=null) atau Bayar Tagihan (dompet_id=null)
        $table->foreignId('tagihan_id')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('pembayaran_transaksis', function (Blueprint $table) {
        $table->dropForeign(['dompet_id']);
        $table->dropColumn('dompet_id');
        $table->foreignId('tagihan_id')->nullable(false)->change(); // Kembalikan
    });
}
};
