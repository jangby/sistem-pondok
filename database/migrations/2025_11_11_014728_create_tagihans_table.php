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
    Schema::create('tagihans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
        $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
        $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayarans')->restrictOnDelete();
        $table->string('invoice_number')->unique();
        $table->decimal('nominal_asli', 15, 2);
        $table->decimal('nominal_keringanan', 15, 2)->default(0);
        $table->decimal('nominal_tagihan', 15, 2);
        $table->integer('periode_bulan')->nullable();
        $table->integer('periode_tahun');
        $table->date('due_date'); // Jatuh tempo
        $table->enum('status', ['pending', 'paid', 'overdue', 'partial'])->default('pending');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
