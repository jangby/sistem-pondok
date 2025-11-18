<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Lokasi Penyimpanan (Gudang/Lemari/Ruangan)
        Schema::create('inv_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('name'); // Nama Lokasi (Cth: Gudang A, Dapur)
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Data Barang Utama
        Schema::create('inv_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('inv_locations')->cascadeOnDelete();
            
            $table->string('code')->unique(); // ID Barang (Barcode/QR)
            $table->string('name');
            $table->string('unit'); // Satuan (Buah, Set, Lusin)
            $table->decimal('price', 15, 2); // Harga Satuan
            
            // Manajemen Stok
            $table->integer('qty_good');      // Jumlah Bagus (Tersedia)
            $table->integer('qty_damaged');   // Rusak (Dilaporkan)
            $table->integer('qty_repairing'); // Sedang Diperbaiki
            $table->integer('qty_broken');    // Rusak Total (Tidak bisa diperbaiki)
            $table->integer('qty_lost');      // Hilang (Hasil Rekap)
            $table->integer('qty_borrowed');  // Sedang Dipinjam
            
            // Penanggung Jawab (Santri)
            $table->foreignId('pic_santri_id')->nullable()->constrained('santris')->nullOnDelete();
            
            $table->timestamps();
        });

        // 3. Tabel Pencatatan Kerusakan
        Schema::create('inv_damages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('inv_items')->cascadeOnDelete();
            
            $table->integer('qty'); // Jumlah yang rusak
            $table->enum('severity', ['ringan', 'sedang', 'parah']);
            $table->enum('status', ['dilaporkan', 'diperbaiki', 'selesai', 'ganti']);
            
            $table->date('date_reported');
            $table->date('date_resolved')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });

        // 4. Tabel Peminjaman
        Schema::create('inv_borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('inv_items')->cascadeOnDelete();
            
            $table->string('borrower_name'); // Nama Peminjam (Bisa santri/orang luar)
            $table->string('destination')->nullable(); // Lokasi tujuan/kegunaan
            $table->integer('qty');
            
            $table->date('start_date');
            $table->date('end_date');
            $table->date('return_date')->nullable(); // Tanggal dikembalikan
            
            $table->enum('status', ['active', 'returned', 'overdue']);
            
            $table->timestamps();
        });

        // 5. Tabel Rekap / Audit (Stock Opname)
        Schema::create('inv_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('inv_items')->cascadeOnDelete();
            
            $table->integer('expected_qty'); // Jumlah di sistem
            $table->integer('actual_qty');   // Jumlah fisik
            $table->integer('difference');   // Selisih
            
            $table->enum('status', ['pending', 'reconciled']); // Reconciled = sudah disesuaikan stoknya
            $table->date('audit_date');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inv_audits');
        Schema::dropIfExists('inv_borrowings');
        Schema::dropIfExists('inv_damages');
        Schema::dropIfExists('inv_items');
        Schema::dropIfExists('inv_locations');
    }
};