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
        // 1. Tabel Buku
        Schema::create('perpus_bukus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->onDelete('cascade');
            
            $table->string('judul');
            $table->string('penulis')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('tahun_terbit', 4)->nullable();
            $table->string('isbn')->nullable();
            
            // Barcode/QR unik per buku fisik untuk scan
            $table->string('kode_buku')->unique()->comment('Barcode unik per buku'); 
            
            $table->integer('stok_total')->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->string('lokasi_rak')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('foto_cover')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Agar data buku tidak langsung hilang permanen jika dihapus
        });

        // 2. Tabel Setting Perpustakaan (Denda & Aturan)
        Schema::create('perpus_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->onDelete('cascade');
            
            $table->decimal('denda_per_hari', 10, 2)->default(1000);
            $table->integer('batas_hari_pinjam')->default(7); // Default 1 minggu
            $table->decimal('denda_rusak_ringan', 10, 2)->default(0); // Biaya default (bisa di-override saat transaksi)
            $table->decimal('denda_rusak_berat', 10, 2)->default(0);
            
            $table->timestamps();
        });

        // 3. Tabel Kunjungan (Buku Tamu Digital)
        Schema::create('perpus_kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            
            // Petugas yang men-scan (opsional, bisa null jika pakai kiosk mandiri)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); 
            
            $table->dateTime('waktu_berkunjung');
            $table->string('keperluan')->default('Membaca'); // Membaca, Pinjam Buku, Internet, dll
            
            $table->timestamps();
        });

        // 4. Tabel Transaksi Peminjaman & Pengembalian
        Schema::create('perpus_peminjamans', function (Blueprint $table) {
            $table->id();
            $table->uuid('kode_transaksi')->unique(); // ID unik transaksi misal: LIB-20231124-001
            
            $table->foreignId('pondok_id')->constrained('pondoks')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('perpus_bukus')->onDelete('cascade');
            
            // Petugas
            $table->foreignId('petugas_pinjam')->nullable()->constrained('users');
            $table->foreignId('petugas_kembali')->nullable()->constrained('users');

            // Tanggal
            $table->date('tgl_pinjam');
            $table->date('tgl_wajib_kembali');
            $table->date('tgl_kembali_real')->nullable(); // Diisi saat buku dikembalikan

            // Status & Kondisi
            // Status: 'dipinjam', 'kembali', 'hilang'
            $table->string('status')->default('dipinjam'); 
            
            // Kondisi saat kembali: 'baik', 'rusak_ringan', 'rusak_berat'
            $table->string('kondisi_kembali')->nullable(); 

            // Denda & Biaya
            $table->decimal('denda_keterlambatan', 12, 2)->default(0);
            $table->decimal('denda_kerusakan', 12, 2)->default(0);
            $table->decimal('biaya_ganti_buku', 12, 2)->default(0); // Jika hilang
            
            $table->text('catatan')->nullable(); // Catatan kerusakan dll

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perpus_peminjamans');
        Schema::dropIfExists('perpus_kunjungans');
        Schema::dropIfExists('perpus_settings');
        Schema::dropIfExists('perpus_bukus');
    }
};