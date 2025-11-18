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
    Schema::create('pondok_staff', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        // Role akan kita ambil dari Spatie, tapi kita catat di sini
        // $table->string('role'); 
        $table->timestamps();
        $table->unique(['pondok_id', 'user_id']); // 1 user hanya bisa 1x di 1 pondok
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pondok_staff');
    }
};
