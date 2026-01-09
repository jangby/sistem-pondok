<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_distribusi_danas', function (Blueprint $table) {
            $table->id();
            
            // Agar tahu ini uang dari gelombang tahun berapa
            $table->foreignId('ppdb_setting_id')->constrained('ppdb_settings')->cascadeOnDelete();
            
            // Siapa petugas yang melakukan input (Audit trail)
            $table->foreignId('user_id')->constrained('users'); 

            // Pos Anggaran
            $table->enum('kategori', [
                'yayasan', 
                'pondok', 
                'usaha', 
                'panitia'
            ]);

            // Jenis Transaksi: 
            // 'setoran' = Uang diserahkan ke pihak lain (Yayasan/Pondok)
            // 'belanja' = Uang dipakai beli barang (Panitia)
            $table->enum('jenis', ['setoran', 'belanja']); 

            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable(); // Misal: "Setoran Tahap 1" atau "Beli Kain Seragam"
            $table->string('bukti_foto')->nullable(); // Path file foto nota/bukti transfer

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_distribusi_danas');
    }
};