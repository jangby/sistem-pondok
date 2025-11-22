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
    Schema::table('santris', function (Blueprint $table) {
        // Alamat
        $table->text('alamat')->nullable();
        $table->string('rt', 5)->nullable();
        $table->string('rw', 5)->nullable();
        $table->string('desa')->nullable();
        $table->string('kecamatan')->nullable();
        $table->string('kode_pos', 10)->nullable();

        // Data Ayah
        $table->string('nama_ayah')->nullable();
        $table->year('thn_lahir_ayah')->nullable();
        $table->string('pendidikan_ayah')->nullable();
        $table->string('pekerjaan_ayah')->nullable();
        $table->string('penghasilan_ayah')->nullable();
        $table->string('nik_ayah', 20)->nullable();

        // Data Ibu
        $table->string('nama_ibu')->nullable();
        $table->year('thn_lahir_ibu')->nullable();
        $table->string('pendidikan_ibu')->nullable();
        $table->string('pekerjaan_ibu')->nullable();
        $table->string('penghasilan_ibu')->nullable();
        $table->string('nik_ibu', 20)->nullable();
    });
}

public function down(): void
{
    Schema::table('santris', function (Blueprint $table) {
        $table->dropColumn([
            'alamat', 'rt', 'rw', 'desa', 'kecamatan', 'kode_pos',
            'nama_ayah', 'thn_lahir_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'nik_ayah',
            'nama_ibu', 'thn_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'nik_ibu'
        ]);
    });
}
};
