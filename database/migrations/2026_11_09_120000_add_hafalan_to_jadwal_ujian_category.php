<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_ujian_diniyahs', function (Blueprint $table) {
            $table->enum('kategori_tes', ['tulis', 'lisan', 'praktek', 'hafalan'])
                ->default('tulis')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_ujian_diniyahs', function (Blueprint $table) {
            $table->enum('kategori_tes', ['tulis', 'lisan', 'praktek'])
                ->default('tulis')
                ->change();
        });
    }
};
