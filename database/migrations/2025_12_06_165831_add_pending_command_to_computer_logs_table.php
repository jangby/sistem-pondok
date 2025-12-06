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
        Schema::table('computer_logs', function (Blueprint $table) {
            // Kita tambahkan kolom 'pending_command' yang boleh kosong (nullable)
            // Kita letakkan setelah kolom 'ip_address' agar rapi
            $table->string('pending_command')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('computer_logs', function (Blueprint $table) {
            // Ini untuk jaga-jaga kalau mau rollback (hapus kolomnya)
            $table->dropColumn('pending_command');
        });
    }
};