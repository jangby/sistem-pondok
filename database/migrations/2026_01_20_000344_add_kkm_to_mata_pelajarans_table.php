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
    Schema::table('mata_pelajarans', function (Blueprint $table) {
        // Menambahkan kolom KKM setelah nama_mapel, default 70
        $table->integer('kkm')->default(70)->after('nama_mapel'); 
    });
}

public function down(): void
{
    Schema::table('mata_pelajarans', function (Blueprint $table) {
        $table->dropColumn('kkm');
    });
}
};
