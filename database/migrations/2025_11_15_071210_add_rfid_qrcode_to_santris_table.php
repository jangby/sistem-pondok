<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            // Tambahkan setelah NIS
            $table->string('rfid_uid', 50)->nullable()->unique()->after('nis'); // ID Kartu Fisik
            $table->string('qrcode_token', 64)->nullable()->unique()->after('rfid_uid'); // Kode untuk QR
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn(['rfid_uid', 'qrcode_token']);
        });
    }
};