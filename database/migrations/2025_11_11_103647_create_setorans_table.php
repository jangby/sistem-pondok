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
    Schema::create('setorans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
        $table->foreignId('admin_id_penyetor')->constrained('users')->cascadeOnDelete();
        $table->foreignId('bendahara_id_penerima')->nullable()->constrained('users')->nullOnDelete();
        $table->date('tanggal_setoran');
        $table->decimal('total_setoran', 15, 2);
        $table->text('catatan')->nullable();
        $table->timestamp('dikonfirmasi_pada')->nullable(); // Diisi oleh Bendahara
        $table->timestamps();
    });
}
public function down(): void
{
    Schema::dropIfExists('setorans');
}
};
