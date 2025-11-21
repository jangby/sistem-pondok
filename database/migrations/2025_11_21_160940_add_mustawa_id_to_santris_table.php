<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->foreignId('mustawa_id')->nullable()->after('kelas_id')
                  ->constrained('mustawas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropForeign(['mustawa_id']);
            $table->dropColumn('mustawa_id');
        });
    }
};