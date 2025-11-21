<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Ustadz (Profil Pengajar Diniyah)
        // Terhubung ke tabel users untuk login
        Schema::create('ustadzs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('nip')->nullable(); // Nomor Induk Pengajar (Opsional)
            $table->string('nama_lengkap');
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('spesialisasi')->nullable(); // Misal: Fiqh, Nahwu, Tahfidz
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Mustawa (Kelas Diniyah)
        // Tingkatan kelas pondok: 1-6, Takhosus, dll.
        Schema::create('mustawas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('nama'); // Contoh: "Mustawa 1 A", "Takhosus Fiqh"
            $table->integer('tingkat'); // 1, 2, 3... untuk sorting
            $table->string('gender')->nullable(); // putera/puteri (opsional jika kelas dipisah)
            $table->foreignId('wali_ustadz_id')->nullable()->constrained('ustadzs')->nullOnDelete();
            $table->string('tahun_ajaran')->nullable(); // Misal: "2024/2025"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Tabel Mapel Diniyah (Kitab)
        Schema::create('mapel_diniyahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('kode_mapel')->nullable();
            $table->string('nama_kitab'); // Contoh: "Safinatun Naja"
            $table->string('nama_mapel'); // Contoh: "Fiqh Dasar"
            $table->integer('kkm')->default(60); // Kriteria Ketuntasan Minimal
            // Opsi metode ujian untuk mapel ini
            $table->boolean('uji_tulis')->default(true);
            $table->boolean('uji_lisan')->default(true);
            $table->boolean('uji_praktek')->default(false); // Tambahan sesuai request
            $table->timestamps();
        });

        // 4. Tabel Jadwal Diniyah
        Schema::create('jadwal_diniyahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->foreignId('mustawa_id')->constrained('mustawas')->cascadeOnDelete();
            $table->foreignId('mapel_diniyah_id')->constrained('mapel_diniyahs')->cascadeOnDelete();
            $table->foreignId('ustadz_id')->constrained('ustadzs')->cascadeOnDelete();
            
            $table->string('hari'); // Senin, Selasa, dll
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });

        // 5. Tabel Absensi Diniyah
        // Terintegrasi dengan RFID/QR
        Schema::create('absensi_diniyahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_diniyah_id')->constrained('jadwal_diniyahs')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            
            // Status: H=Hadir, I=Izin, S=Sakit, A=Alpha
            $table->enum('status', ['H', 'I', 'S', 'A']); 
            $table->enum('metode', ['manual', 'rfid', 'qr', 'auto_system'])->default('manual');
            
            $table->dateTime('waktu_scan')->nullable(); // Waktu tap kartu/scan
            $table->date('tanggal'); // Untuk mempermudah query per hari
            $table->text('keterangan')->nullable(); // Catatan tambahan (misal alasan izin)
            $table->timestamps();
        });

        // 6. Tabel Jurnal Pendidikan (Setoran & Progres)
        // Digunakan Ustadz input hafalan/pemahaman harian
        Schema::create('jurnal_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->foreignId('ustadz_id')->constrained('ustadzs')->cascadeOnDelete();
            $table->foreignId('mapel_diniyah_id')->nullable()->constrained('mapel_diniyahs')->nullOnDelete();
            
            // Jenis Jurnal
            $table->enum('jenis', ['hafalan', 'pemahaman', 'praktik', 'baca_kitab']);
            
            $table->string('materi'); // Contoh: "Juz 30, An-Naba 1-10" atau "Bab Wudhu"
            $table->integer('nilai')->nullable(); // Nilai harian 0-100
            $table->string('predikat')->nullable(); // A, B, C, Mumtaz, Jayyid
            $table->text('catatan')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });

        // 7. Tabel Program Unggulan (Ekskul Pesantren)
        // Seperti Tahfidz Khusus / Bahasa Arab yang lintas kelas
        Schema::create('program_unggulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pondok_id')->constrained('pondoks')->cascadeOnDelete();
            $table->string('nama_program'); // Contoh: "Tahfidz Intensif", "Klub Bahasa Arab"
            $table->text('deskripsi')->nullable();
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('ustadzs')->nullOnDelete();
            $table->timestamps();
        });

        // Tabel Pivot Anggota Program Unggulan
        Schema::create('program_unggulan_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_unggulan_id')->constrained('program_unggulans')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->date('joined_at');
            $table->timestamps();
        });

        // 8. Tabel Nilai Ujian Pesantren (Rapor)
        Schema::create('nilai_pesantrens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->foreignId('mustawa_id')->constrained('mustawas')->cascadeOnDelete(); // Menyimpan history kelas saat nilai diambil
            $table->foreignId('mapel_diniyah_id')->constrained('mapel_diniyahs')->cascadeOnDelete();
            
            $table->enum('jenis_ujian', ['uts', 'uas']); // Tengah / Akhir Semester
            $table->enum('semester', ['ganjil', 'genap']);
            $table->string('tahun_ajaran');
            
            $table->decimal('nilai_tulis', 5, 2)->default(0);
            $table->decimal('nilai_lisan', 5, 2)->default(0);
            $table->decimal('nilai_praktek', 5, 2)->default(0);
            
            // Nilai Akhir (Kalkulasi dari bobot tulis + lisan + praktek)
            $table->decimal('nilai_akhir', 5, 2)->default(0); 
            $table->text('catatan_ustadz')->nullable();
            
            $table->timestamps();
        });
        
        // 9. Tabel Rekap Sikap & Kehadiran (Otomatisasi)
        Schema::create('rekap_sikap_pesantrens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->foreignId('mustawa_id')->constrained('mustawas')->cascadeOnDelete();
            $table->string('periode'); // Misal: "2024/2025 Ganjil"
            
            $table->integer('sakit_count')->default(0);
            $table->integer('izin_count')->default(0);
            $table->integer('alpha_count')->default(0);
            $table->decimal('persentase_kehadiran', 5, 2)->default(0);
            
            $table->integer('skor_kedisiplinan')->default(100); // Ambil dari modul kedisiplinan/poin
            $table->string('nilai_sikap'); // Sangat Baik, Baik, Cukup
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_sikap_pesantrens');
        Schema::dropIfExists('nilai_pesantrens');
        Schema::dropIfExists('program_unggulan_members');
        Schema::dropIfExists('program_unggulans');
        Schema::dropIfExists('jurnal_pendidikans');
        Schema::dropIfExists('absensi_diniyahs');
        Schema::dropIfExists('jadwal_diniyahs');
        Schema::dropIfExists('mapel_diniyahs');
        Schema::dropIfExists('mustawas');
        Schema::dropIfExists('ustadzs');
    }
};