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
    Schema::create('alokasi_pembayarans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pembayaran_transaksi_id')->constrained('pembayaran_transaksis')->cascadeOnDelete();
        $table->foreignId('tagihan_detail_id')->constrained('tagihan_details')->cascadeOnDelete();
        $table->decimal('nominal_alokasi', 15, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alokasi_pembayarans');
    }
};
