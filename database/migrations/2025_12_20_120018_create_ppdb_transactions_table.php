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
    Schema::create('ppdb_transactions', function (Blueprint $table) {
        $table->id();
        $table->string('order_id')->unique(); // Order ID unik dari Midtrans
        $table->foreignId('calon_santri_id')->constrained()->cascadeOnDelete();
        $table->decimal('gross_amount', 15, 2); // Nominal yang dibayar
        $table->string('status')->default('pending'); // pending, success, failed
        $table->string('snap_token')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('ppdb_transactions');
}
};
