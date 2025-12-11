<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('santris', function (Blueprint $table) {
        // Kita simpan sebagai TEXT atau JSON karena datanya berupa array panjang
        $table->text('face_embedding')->nullable()->after('nis'); 
    });
}

public function down()
{
    Schema::table('santris', function (Blueprint $table) {
        $table->dropColumn('face_embedding');
    });
}
};
