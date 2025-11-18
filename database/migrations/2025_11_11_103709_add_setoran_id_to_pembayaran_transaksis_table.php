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
    Schema::table('pembayaran_transaksis', function (Blueprint $table) {
        $table->foreignId('setoran_id')->nullable()->after('tagihan_id')
              ->constrained('setorans')->onDelete('set null');
    });
}
public function down(): void
{
    Schema::table('pembayaran_transaksis', function (Blueprint $table) {
        $table->dropForeign(['setoran_id']);
        $table->dropColumn('setoran_id');
    });
}
};
