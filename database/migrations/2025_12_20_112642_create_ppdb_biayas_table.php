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
    Schema::create('ppdb_biayas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ppdb_setting_id')->constrained()->cascadeOnDelete(); // Terhubung ke Gelombang
        $table->string('jenjang'); // Contoh: SMP, SMA, TAKHOSUS
        $table->string('nama_biaya'); // Contoh: Uang Gedung, Seragam
        $table->decimal('nominal', 15, 2);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('ppdb_biayas');
}
};
