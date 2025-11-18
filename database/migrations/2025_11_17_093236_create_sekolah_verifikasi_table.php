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
        Schema::create('sekolah_wifi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sekolah_id')->constrained('sekolahs')->cascadeOnDelete();
    $table->string('nama_wifi_ssid'); // Nama WiFi (SSID)
    $table->string('bssid')->nullable(); // MAC Address (opsional, lebih aman)
    $table->timestamps();
});

Schema::create('sekolah_lokasi_geofence', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sekolah_id')->constrained('sekolahs')->cascadeOnDelete();
    $table->string('nama_lokasi'); // Cth: "Gedung A (Kelas 7-8)"
    $table->decimal('latitude', 10, 7);
    $table->decimal('longitude', 11, 7);
    $table->integer('radius'); // Dalam meter
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah_verifikasi');
    }
};
