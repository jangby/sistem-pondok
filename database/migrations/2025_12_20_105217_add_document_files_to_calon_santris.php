<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_santris', function (Blueprint $table) {
            // Menambahkan kolom file baru
            // Kita gunakan 'after' agar rapi, diletakkan setelah file_akta
            
            if (!Schema::hasColumn('calon_santris', 'file_ijazah')) {
                $table->string('file_ijazah')->nullable()->after('file_akta');
            }
            if (!Schema::hasColumn('calon_santris', 'file_skl')) {
                $table->string('file_skl')->nullable()->after('file_ijazah');
            }
            if (!Schema::hasColumn('calon_santris', 'file_ktp_ayah')) {
                $table->string('file_ktp_ayah')->nullable()->after('file_skl');
            }
            if (!Schema::hasColumn('calon_santris', 'file_ktp_ibu')) {
                $table->string('file_ktp_ibu')->nullable()->after('file_ktp_ayah');
            }
            if (!Schema::hasColumn('calon_santris', 'file_kip')) {
                $table->string('file_kip')->nullable()->after('file_ktp_ibu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('calon_santris', function (Blueprint $table) {
            $table->dropColumn(['file_ijazah', 'file_skl', 'file_ktp_ayah', 'file_ktp_ibu', 'file_kip']);
        });
    }
};