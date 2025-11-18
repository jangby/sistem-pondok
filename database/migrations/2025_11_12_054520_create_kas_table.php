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
    Schema::create('kas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Siapa yg mencatat
        $table->foreignId('setoran_id')->nullable()->constrained('setorans')->nullOnDelete(); // Asal pemasukan

        $table->enum('tipe', ['pemasukan', 'pengeluaran']);
        $table->string('deskripsi');
        $table->decimal('nominal', 15, 2); // Nominal selalu positif
        $table->date('tanggal_transaksi');
        $table->timestamps();
    });
}
public function down(): void
{
    Schema::dropIfExists('kas');
}
};
