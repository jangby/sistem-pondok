<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calon_santris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ppdb_setting_id')->constrained('ppdb_settings');
            
            // --- DATA UTAMA (Sesuai create_santris_table) ---
            $table->string('no_pendaftaran')->unique();
            $table->string('full_name'); // Mengikuti tabel santris (bukan nama_lengkap)
            $table->enum('jenis_kelamin', ['L', 'P']); 
            
            // --- DATA PRIBADI & KESEHATAN (Sesuai add_details_to_santris) ---
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('nik', 16)->nullable(); // Sesuai add_nik_to_santris
            $table->string('no_kk', 16)->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->text('riwayat_penyakit')->nullable();

            // --- DATA ALAMAT RINCI (Sesuai add_detail_emis_to_santris) ---
            $table->text('alamat')->nullable(); // Jalan/Dusun
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('desa')->nullable();      // Kelurahan/Desa
            $table->string('kecamatan')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('kabupaten')->nullable(); // Tambahan pelengkap
            $table->string('provinsi')->nullable();  // Tambahan pelengkap

            // --- DATA PENDIDIKAN SEBELUMNYA ---
            $table->string('sekolah_asal')->nullable();
            $table->string('nisn', 10)->nullable();

            // --- DATA AYAH (Sesuai add_detail_emis_to_santris) ---
            $table->string('nama_ayah');
            $table->string('nik_ayah', 20)->nullable();
            $table->year('thn_lahir_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();
            $table->string('no_hp_ayah')->nullable(); // Untuk kontak

            // --- DATA IBU (Sesuai add_detail_emis_to_santris) ---
            $table->string('nama_ibu');
            $table->string('nik_ibu', 20)->nullable();
            $table->year('thn_lahir_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('penghasilan_ibu')->nullable();
            $table->string('no_hp_ibu')->nullable(); // Untuk kontak

            // --- DATA WALI (Optional) ---
            $table->string('nama_wali')->nullable();
            $table->string('no_hp_wali')->nullable();

            // --- FILE UPLOAD ---
            $table->string('foto_santri')->nullable();
            $table->string('file_kk')->nullable();
            $table->string('file_akta')->nullable();

            // --- STATUS PPDB ---
            $table->enum('status_pembayaran', ['belum_bayar', 'lunas', 'gagal'])->default('belum_bayar');
            $table->decimal('total_sudah_bayar', 15, 2)->default(0);
            $table->enum('status_pendaftaran', ['draft', 'menunggu_verifikasi', 'diterima', 'ditolak'])->default('draft');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon_santris');
    }
};