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
    Schema::create('pondok_settings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
        $table->string('nama_resmi')->nullable();
        $table->text('alamat')->nullable();
        $table->string('telepon')->nullable();
        $table->string('logo_url')->nullable();
        // tambahkan kolom lain (email, website, dll)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pondok_settings');
    }
};
