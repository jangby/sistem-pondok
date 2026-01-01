<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_transactions', function (Blueprint $table) {
            // Untuk menyimpan 'bca_va', 'qris', dll
            $table->string('payment_type')->nullable()->after('gross_amount'); 
            // Untuk menyimpan Nomor VA atau Kode Bayar
            $table->string('payment_code')->nullable()->after('payment_type'); 
            // Untuk menyimpan biaya admin
            $table->decimal('biaya_admin', 10, 2)->default(0)->after('gross_amount');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'payment_code', 'biaya_admin']);
        });
    }
};