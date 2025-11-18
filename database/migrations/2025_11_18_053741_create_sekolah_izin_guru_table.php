<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolah_izin_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolahs')->cascadeOnDelete();
            $table->foreignId('guru_user_id')->constrained('users')->cascadeOnDelete(); // Guru yg izin
            
            $table->enum('tipe_izin', ['sakit', 'izin']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('keterangan_guru');
            $table->string('file_pendukung_url')->nullable(); // Cth: Surat Dokter
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('peninjau_user_id')->nullable()->constrained('users')->nullOnDelete(); // Admin yg meninjau
            $table->timestamp('ditinjau_pada')->nullable();
            $table->text('keterangan_admin')->nullable(); // Alasan ditolak/diterima
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolah_izin_guru');
    }
};