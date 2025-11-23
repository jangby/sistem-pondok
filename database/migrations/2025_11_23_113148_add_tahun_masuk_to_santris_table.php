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
    Schema::table('santris', function (Blueprint $table) {
        // Menambahkan kolom tahun_masuk setelah kolom NIS
        $table->year('tahun_masuk')->nullable()->after('nis'); 
    });
}

public function down(): void
{
    Schema::table('santris', function (Blueprint $table) {
        $table->dropColumn('tahun_masuk');
    });
}
};
