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
        // Tambah kolom jenjang setelah no_pendaftaran
        if (!Schema::hasColumn('calon_santris', 'jenjang')) {
            $table->string('jenjang')->after('no_pendaftaran'); 
        }
    });
}

public function down(): void
{
    Schema::table('calon_santris', function (Blueprint $table) {
        $table->dropColumn('jenjang');
    });
}
};
