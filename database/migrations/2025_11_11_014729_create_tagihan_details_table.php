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
    Schema::create('tagihan_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tagihan_id')->constrained('tagihans')->cascadeOnDelete();
        $table->string('nama_item');
        $table->integer('prioritas');
        $table->decimal('nominal_item', 15, 2);
        $table->decimal('sisa_tagihan_item', 15, 2);
        $table->enum('status_item', ['pending', 'lunas'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_details');
    }
};
