<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            // Kita taruh setelah NISN. 'nullable' PENTING agar data lama tidak error
            $table->string('nik', 16)->nullable()->unique()->after('nis');
            $table->string('no_kk', 16)->nullable()->after('nik');
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn(['nik', 'no_kk']);
        });
    }
};