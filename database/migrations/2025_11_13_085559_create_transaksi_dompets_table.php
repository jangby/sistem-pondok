<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_dompets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dompet_id')->constrained('dompets')->cascadeOnDelete();
            
            // Relasi opsional
            $table->foreignId('warung_id')->nullable()->constrained('warungs')->nullOnDelete();
            $table->foreignId('user_id_pencatat')->nullable()->constrained('users')->nullOnDelete(); // Admin/Ortu
            
            $table->enum('tipe', ['topup_manual', 'topup_midtrans', 'jajan', 'tarik_tunai']);
            $table->decimal('nominal', 15, 2); // Selalu positif
            
            // Untuk audit/pelacakan
            $table->decimal('saldo_sebelum', 15, 2);
            $table->decimal('saldo_setelah', 15, 2);
            
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_dompets');
    }
};