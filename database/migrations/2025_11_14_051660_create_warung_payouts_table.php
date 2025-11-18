<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warung_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warung_id')->constrained('warungs')->cascadeOnDelete();
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('keterangan')->nullable(); // Rekening tujuan / Catatan Warung
            
            // Field baru untuk Admin
            $table->string('bukti_transfer')->nullable(); // Path foto nota/bukti tf
            $table->string('catatan_admin')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warung_payouts');
    }
};