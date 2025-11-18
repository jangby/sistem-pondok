<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update Tabel Santris
        Schema::table('santris', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('golongan_darah', 5)->nullable()->after('tanggal_lahir');
            $table->text('riwayat_penyakit')->nullable()->after('golongan_darah');
        });

        // 2. Update Tabel Orang Tuas
        Schema::table('orang_tuas', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->after('name');
            $table->string('pekerjaan')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn(['tempat_lahir', 'tanggal_lahir', 'golongan_darah', 'riwayat_penyakit']);
        });

        Schema::table('orang_tuas', function (Blueprint $table) {
            $table->dropColumn(['nik', 'pekerjaan']);
        });
    }
};