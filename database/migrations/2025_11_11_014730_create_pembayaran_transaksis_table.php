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
    Schema::create('pembayaran_transaksis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
        $table->foreignId('orang_tua_id')->constrained('orang_tuas')->cascadeOnDelete();
        $table->string('transaction_code')->unique();
        $table->decimal('total_bayar', 15, 2);
        $table->datetime('tanggal_bayar');
        $table->enum('metode_pembayaran', ['manual_transfer', 'virtual_account', 'qris', 'cash']);
        $table->string('bukti_bayar_url')->nullable();
        $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
        $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->text('catatan_verifikasi')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_transaksis');
    }
};
