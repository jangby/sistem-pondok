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
        // Tabel ini untuk data *profil* guru. 
        // Akun login-nya tetap di tabel `users`
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            // Relasi 1-ke-1 dengan tabel 'users'
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('nip')->nullable()->unique(); // NIP/Nomor Induk Pegawai
            $table->string('telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};