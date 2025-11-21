<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai_pesantrens', function (Blueprint $table) {
            // Tambahkan kolom nilai_kehadiran setelah nilai_praktek
            $table->decimal('nilai_kehadiran', 5, 2)->default(0)->after('nilai_praktek');
        });
    }

    public function down(): void
    {
        Schema::table('nilai_pesantrens', function (Blueprint $table) {
            $table->dropColumn('nilai_kehadiran');
        });
    }
};