<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uuj_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // Admin UJ yang request
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('tujuan_transfer'); // Bank/E-wallet tujuan
            $table->string('bukti_transfer')->nullable(); // Dari Super Admin
            $table->string('catatan_admin')->nullable(); // Dari Super Admin
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uuj_payouts');
    }
};