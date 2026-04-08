<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absensi_gerbangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal'); // Tanggal absen
            $table->time('absen_pagi')->nullable(); // Jam absen pagi
            $table->time('absen_sore')->nullable(); // Jam absen sore
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_gerbangs');
    }
};