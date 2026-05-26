<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buku_tamus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tamu');
            $table->string('instansi_asal')->nullable(); // Asal daerah/instansi
            $table->string('bertemu_dengan'); // Ingin bertemu siapa
            $table->text('keperluan');
            $table->string('no_hp')->nullable();
            $table->timestamp('jam_masuk')->useCurrent();
            $table->timestamp('jam_keluar')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buku_tamus');
    }
};