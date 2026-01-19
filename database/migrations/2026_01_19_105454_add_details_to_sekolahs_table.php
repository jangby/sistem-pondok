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
    Schema::table('sekolahs', function (Blueprint $table) {
        $table->text('alamat')->nullable()->after('kepala_sekolah');
        $table->string('email')->nullable()->after('alamat');
        $table->string('no_telp', 20)->nullable()->after('email');
    });
}

public function down(): void
{
    Schema::table('sekolahs', function (Blueprint $table) {
        $table->dropColumn(['alamat', 'email', 'no_telp']);
    });
}
};
