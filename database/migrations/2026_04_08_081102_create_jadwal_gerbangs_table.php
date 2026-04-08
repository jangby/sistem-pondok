<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal_gerbangs', function (Blueprint $table) {
            $table->id();
            // Menyambungkan jadwal ini dengan user (petugas)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('hari'); // Isinya nanti: Senin, Selasa, Rabu, dst
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_gerbangs');
    }
};