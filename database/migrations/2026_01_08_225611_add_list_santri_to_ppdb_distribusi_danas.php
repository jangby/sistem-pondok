<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_distribusi_danas', function (Blueprint $table) {
            // Kolom ini akan menyimpan ID santri dalam bentuk [1, 5, 10, ...]
            $table->json('list_santri_id')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_distribusi_danas', function (Blueprint $table) {
            $table->dropColumn('list_santri_id');
        });
    }
};