<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Hapus pin_absen dari tabel users
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'pin_absen')) {
                $table->dropColumn('pin_absen');
            }
        });

        // 2. Tambahkan pin_absen ke tabel santris
        Schema::table('santris', function (Blueprint $table) {
            $table->string('pin_absen', 6)->nullable()->after('full_name');
        });

        // 3. Hapus tabel jadwal dan absensi yang lama
        Schema::dropIfExists('absensi_gerbangs');
        Schema::dropIfExists('jadwal_gerbangs');

        // 4. Buat ulang tabel jadwal_gerbangs dengan santri_id
        Schema::create('jadwal_gerbangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->string('hari');
            $table->timestamps();
        });

        // 5. Buat ulang tabel absensi_gerbangs dengan santri_id
        Schema::create('absensi_gerbangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('absen_pagi')->nullable();
            $table->time('absen_sore')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // ... (kosongkan saja untuk down)
    }
};