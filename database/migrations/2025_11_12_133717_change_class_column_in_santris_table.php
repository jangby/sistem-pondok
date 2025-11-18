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
    Schema::table('santris', function (Blueprint $table) {
        // Hapus kolom 'class' yang lama (string)
        $table->dropColumn('class');
        
        // Tambahkan kolom 'kelas_id' yang baru (foreign key)
        $table->foreignId('kelas_id')->nullable()->after('jenis_kelamin')
              ->constrained('kelas')->onDelete('set null');
    });
}
public function down(): void
{
    Schema::table('santris', function (Blueprint $table) {
        $table->dropForeign(['kelas_id']);
        $table->dropColumn('kelas_id');
        $table->string('class')->nullable();
    });
}
};
