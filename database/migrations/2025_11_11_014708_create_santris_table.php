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
    Schema::create('santris', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
        $table->foreignId('orang_tua_id')->constrained('orang_tuas')->cascadeOnDelete();
        $table->string('nis')->unique(); // Nomor Induk Santri
        $table->string('full_name');
        $table->string('class')->nullable(); // Kelas atau asrama
        $table->enum('status', ['active', 'graduated', 'moved'])->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
