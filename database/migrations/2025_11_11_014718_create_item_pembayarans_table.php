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
    Schema::create('item_pembayarans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
        $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayarans')->cascadeOnDelete();
        $table->string('nama_item');
        $table->decimal('nominal', 15, 2);
        $table->integer('prioritas'); // Prioritas 1, 2, 3...
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pembayarans');
    }
};
