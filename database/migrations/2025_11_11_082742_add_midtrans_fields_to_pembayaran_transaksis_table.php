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
        // Ganti 'transaction_code' jadi 'order_id_pondok' agar tidak bingung
        $table->renameColumn('transaction_code', 'order_id_pondok');

        // Kolom untuk Midtrans
        $table->string('midtrans_order_id')->nullable()->after('orang_tua_id');
        $table->string('payment_gateway')->nullable()->after('metode_pembayaran');

        // Ubah beberapa kolom agar bisa null (karena saat request dibuat, data ini belum ada)
        $table->datetime('tanggal_bayar')->nullable()->change();
        $table->enum('metode_pembayaran', ['manual_transfer', 'virtual_account', 'qris', 'cash', 'gopay', 'lainnya'])
              ->default('cash')->change();
    });
}

// (Tambahkan juga fungsi down() untuk rollback)
public function down(): void
{
    Schema::table('pembayaran_transaksis', function (Blueprint $table) {
        $table->renameColumn('order_id_pondok', 'transaction_code');
        $table->dropColumn(['midtrans_order_id', 'payment_gateway']);
        $table->datetime('tanggal_bayar')->nullable(false)->change();
        // (Rollback enum sedikit rumit, bisa di-skip jika hanya development)
    });
}
};
