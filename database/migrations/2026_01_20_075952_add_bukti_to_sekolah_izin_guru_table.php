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
    Schema::table('sekolah_izin_guru', function (Blueprint $table) {
        // Kolom nullable (opsional) untuk path gambar
        $table->string('bukti_url')->nullable()->after('file_pendukung_url'); 
    });
}

public function down(): void
{
    Schema::table('sekolah_izin_guru', function (Blueprint $table) {
        $table->dropColumn('bukti_url');
    });
}
};
