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
    Schema::table('calon_santris', function (Blueprint $table) {
        // Kolom untuk menyimpan total nominal yang sudah dibayar (cicilan)
        $table->decimal('total_sudah_bayar', 15, 2)->default(0)->after('status_pembayaran');
    });
}

public function down(): void
{
    Schema::table('calon_santris', function (Blueprint $table) {
        $table->dropColumn('total_sudah_bayar');
    });
}
};
