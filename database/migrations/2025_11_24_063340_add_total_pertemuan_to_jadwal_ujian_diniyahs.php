<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_ujian_diniyahs', function (Blueprint $table) {
            // Menyimpan total tatap muka yang disepakati ustadz
            $table->integer('total_pertemuan')->default(0)->after('kategori_tes');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_ujian_diniyahs', function (Blueprint $table) {
            $table->dropColumn('total_pertemuan');
        });
    }
};