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
    Schema::create('payouts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
        $table->foreignId('admin_id_request')->constrained('users')->cascadeOnDelete(); // Admin Pondok yg minta
        $table->foreignId('superadmin_id_approve')->nullable()->constrained('users')->nullOnDelete(); // Super Admin yg setujui

        $table->decimal('total_amount', 15, 2); // Jumlah yg ditarik
        $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');

        $table->text('catatan_admin')->nullable(); // Catatan dari Admin Pondok
        $table->text('catatan_superadmin')->nullable(); // Catatan dari Super Admin (cth: "Sudah ditransfer")

        $table->timestamp('requested_at')->useCurrent(); // Tgl Minta
        $table->timestamp('completed_at')->nullable(); // Tgl Ditransfer
        $table->timestamps();
    });
}
public function down(): void
{
    Schema::dropIfExists('payouts');
}
};
