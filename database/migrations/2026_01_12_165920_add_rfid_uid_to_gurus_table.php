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
    Schema::table('gurus', function (Blueprint $table) {
        // Menyimpan UID Kartu (biasanya hex string)
        // Dibuat unique agar 1 kartu tidak bisa dipakai 2 guru
        $table->string('rfid_uid')->nullable()->unique()->after('nip');
    });
}

public function down(): void
{
    Schema::table('gurus', function (Blueprint $table) {
        $table->dropColumn('rfid_uid');
    });
}
};
