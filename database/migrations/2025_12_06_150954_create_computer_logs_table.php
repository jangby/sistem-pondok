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
    Schema::create('computer_logs', function (Blueprint $table) {
        $table->id();
        $table->string('pc_name')->unique(); // Nama PC (unik)
        $table->string('password');           // Password Terkini
        $table->string('ip_address')->nullable();
        $table->timestamp('last_seen')->useCurrent(); // Kapan terakhir update
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computer_logs');
    }
};
