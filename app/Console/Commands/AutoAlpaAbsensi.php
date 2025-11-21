<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Models
use App\Models\User;
use App\Models\Santri;
use App\Models\Sekolah\SekolahAbsensiSetting;
use App\Models\Sekolah\SekolahHariLibur;
use App\Models\Sekolah\AbsensiGuru;
use App\Models\Sekolah\AbsensiSiswaSekolah;
use App\Models\Sekolah\SekolahIzinGuru;
use App\Models\Perizinan; 

class AutoAlpaAbsensi extends Command
{
    protected $signature = 'absensi:auto-alpa';
    protected $description = 'Otomatis set status ALPA untuk Guru dan Siswa yang tidak absen hingga akhir hari.';

    public function handle()
    {
        $now = Carbon::now();
        $tanggal = $now->format('Y-m-d');
        $hariIni = $now->locale('id_ID')->isoFormat('dddd');

        Log::info("Jalanan Auto Alpa untuk tanggal: {$tanggal}");
        $this->info("Memulai proses Auto Alpa untuk tanggal: {$tanggal}...");

        DB::beginTransaction();
        try {
            // 1. PROSES AUTO ALPA GURU
            $this->processGuru($tanggal, $hariIni);

            // 2. PROSES AUTO ALPA SISWA (SEKOLAH FORMAL)
            $this->processSiswa($tanggal, $hariIni);

            DB::commit();
            $this->info("Proses Auto Alpa selesai.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error Auto Alpa: " . $e->getMessage());
            $this->error("Terjadi kesalahan: " . $e->getMessage());
        }
    }

    /**
     * Logika Auto Alpa untuk Guru
     */
    private function processGuru($tanggal, $hariIni)
    {
        // Ambil guru (tanpa filter status jika kolom tidak ada)
        $gurus = User::role('guru')->get(); 

        $count = 0;
        foreach ($gurus as $guru) {
            // Cek relasi sekolah
            $sekolah = $guru->sekolahs->first(); 
            if (!$sekolah) continue;

            // --- CEK 1: APAKAH HARI INI HARI KERJA & BUKAN LIBUR? ---
            $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first();
            if (!$settings) continue;

            $hariKerja = $settings->hari_kerja ?? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; 
            if (!in_array($hariIni, $hariKerja)) continue;

            $isLibur = SekolahHariLibur::where('sekolah_id', $sekolah->id)
                        ->whereDate('tanggal', $tanggal)
                        ->exists();
            if ($isLibur) continue;

            // --- CEK 2: APAKAH SUDAH ABSEN? ---
            $sudahAbsen = AbsensiGuru::where('guru_user_id', $guru->id)
                            ->whereDate('tanggal', $tanggal)
                            ->exists();
            if ($sudahAbsen) continue;

            // --- CEK 3: APAKAH SEDANG IZIN/CUTI? ---
            // PERBAIKAN: Menggunakan 'guru_user_id' sesuai migrasi Anda
            $sedangIzin = SekolahIzinGuru::where('guru_user_id', $guru->id) 
                            ->where('status', 'approved')
                            ->whereDate('tanggal_mulai', '<=', $tanggal)
                            ->whereDate('tanggal_selesai', '>=', $tanggal)
                            ->exists();
            if ($sedangIzin) continue;

            // --- EKSEKUSI: BUAT RECORD ALPA ---
            AbsensiGuru::create([
                'guru_user_id' => $guru->id,
                'sekolah_id'   => $sekolah->id,
                'tanggal'      => $tanggal,
                'status'       => 'alpa', 
                'jam_masuk'    => null,
                'jam_pulang'   => null,
                'keterangan'   => 'Otomatis oleh sistem (Tidak Absen)',
            ]);
            $count++;
        }

        $this->info("Berhasil memproses {$count} Guru Alpa.");
    }

    /**
     * Logika Auto Alpa untuk Siswa Sekolah
     */
    private function processSiswa($tanggal, $hariIni)
    {
        $siswas = Santri::whereNotNull('kelas_id');
        
        // Cek preventif kolom status
        if (Schema::hasColumn('santris', 'status')) {
            $siswas->where('status', 'aktif');
        }

        $siswas = $siswas->get();

        $count = 0;
        foreach ($siswas as $siswa) {
            $kelas = $siswa->kelas; 
            if (!$kelas) continue;

            $sekolahId = $kelas->sekolah_id; 

            // --- CEK 1: PENGATURAN HARI KERJA/LIBUR ---
            $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolahId)->first();
            if (!$settings) continue;

            $hariKerja = $settings->hari_kerja ?? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; 
            if (!in_array($hariIni, $hariKerja)) continue;

            $isLibur = SekolahHariLibur::where('sekolah_id', $sekolahId)
                        ->whereDate('tanggal', $tanggal)
                        ->exists();
            if ($isLibur) continue;

            // --- CEK 2: SUDAH ABSEN? ---
            $sudahAbsen = AbsensiSiswaSekolah::where('santri_id', $siswa->id)
                            ->whereDate('tanggal', $tanggal)
                            ->exists();
            if ($sudahAbsen) continue;

            // --- CEK 3: SEDANG IZIN/SAKIT? ---
            // Pastikan status izin sesuai dengan data di DB ('disetujui' atau 'approved')
            $sedangIzin = Perizinan::where('santri_id', $siswa->id)
                            ->whereIn('status', ['disetujui', 'approved']) 
                            ->whereDate('tanggal_mulai', '<=', $tanggal)
                            ->whereDate('tanggal_selesai', '>=', $tanggal)
                            ->exists();
            
            if ($sedangIzin) continue;

            // --- EKSEKUSI: BUAT RECORD ALPA SISWA ---
            AbsensiSiswaSekolah::create([
                'sekolah_id'   => $sekolahId,
                'santri_id'    => $siswa->id,
                'tanggal'      => $tanggal,
                'jam_masuk'    => null,
                'jam_pulang'   => null,
                'status_masuk' => 'alpa',
            ]);
            $count++;
        }

        $this->info("Berhasil memproses {$count} Siswa Alpa.");
    }
}