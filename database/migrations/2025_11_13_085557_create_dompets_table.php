<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dompets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            
            // --- PERMINTAAN ANDA ---
            $table->string('barcode_token')->unique()->nullable(); // Kode unik untuk di kartu
            
            $table->decimal('saldo', 15, 2)->default(0);
            $table->string('pin')->nullable(); // PIN 6 digit, akan kita hash
            $table->decimal('daily_spending_limit', 15, 2)->nullable(); // Limit harian
            $table->enum('status', ['active', 'blocked'])->default('active'); // Untuk blokir kartu
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dompets');
    }
};