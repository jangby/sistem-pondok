<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Sekolah\SekolahAbsensiSetting;
use App\Models\Sekolah\AbsensiGuru;
use App\Models\Sekolah\JadwalPelajaran;
use App\Models\Sekolah\AbsensiPelajaran;
use App\Models\Sekolah\SekolahHariLibur;
use App\Notifications\GuruAbsensiWarningNotification;
use App\Notifications\GuruAbsensiReminderNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckGuruAbsensi extends Command
{
    protected $signature = 'absensi:check-guru-notifications';
    protected $description = 'Cek guru yang belum absen masuk (hampir telat) atau lupa absen pulang';

    public function handle()
    {
        $now = Carbon::now();
        $hariIni = $now->locale('id_ID')->isoFormat('dddd');
        $tanggalIni = $now->format('Y-m-d');

        // Ambil semua guru yang aktif dan punya nomor HP
        $gurus = User::role('guru')->whereNotNull('telepon')->with(['guru', 'sekolahs'])->get();

        foreach ($gurus as $guru) {
            // Ambil sekolah pertama (asumsi aturan ikut sekolah pertama)
            $sekolah = $guru->sekolahs->first();
            if (!$sekolah) continue;

            // Cek Hari Libur / Bukan Hari Kerja
            $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first();
            $isHariKerja = in_array($hariIni, $settings->hari_kerja ?? []);
            $isHariLibur = SekolahHariLibur::where('sekolah_id', $sekolah->id)->whereDate('tanggal', $tanggalIni)->exists();

            if (!$isHariKerja || $isHariLibur) continue;

            // --- LOGIKA 1: WARNING ABSEN MASUK SEKOLAH ---
            $this->checkAbsenMasukSekolah($guru, $settings, $now, $tanggalIni);

            // --- LOGIKA 2: WARNING ABSEN MENGAJAR ---
            $this->checkAbsenMengajar($guru, $sekolah->id, $now, $hariIni, $tanggalIni);

            // --- LOGIKA 3: REMINDER PULANG ---
            $this->checkAbsenPulang($guru, $settings, $now, $hariIni, $tanggalIni);
        }
    }

    /**
     * Cek Hampir Telat Masuk Sekolah (5 menit sebelum batas)
     */
    private function checkAbsenMasukSekolah($guru, $settings, $now, $tanggal)
    {
        // Tentukan Batas Telat (Flexi atau Full-time)
        $batasTelat = $settings->batas_telat;
        if ($guru->guru && $guru->guru->tipe_jam_kerja == 'flexi') {
            // Cari jadwal pertama hari ini
            $jadwalPertama = JadwalPelajaran::where('guru_user_id', $guru->id)
                ->where('hari', $now->locale('id_ID')->isoFormat('dddd'))
                ->orderBy('jam_mulai')
                ->first();
            
            if ($jadwalPertama) {
                $batasTelat = $jadwalPertama->jam_mulai;
            } else {
                return; // Flexi gak ada jadwal = gak wajib absen
            }
        }

        // Hitung waktu peringatan (5 menit sebelum batas)
        $waktuWarning = Carbon::parse($batasTelat)->subMinutes(5);
        
        // Jika waktu sekarang >= waktu warning DAN < batas telat
        if ($now->greaterThanOrEqualTo($waktuWarning) && $now->lessThan($batasTelat)) {
            
            // Cek apakah sudah absen
            $sudahAbsen = AbsensiGuru::where('guru_user_id', $guru->id)
                ->whereDate('tanggal', $tanggal)
                ->whereNotNull('jam_masuk')
                ->exists();

            if (!$sudahAbsen) {
                // Cek Cache agar tidak spam (kirim 1x saja hari ini)
                $cacheKey = "notif_masuk_sekolah_{$guru->id}_{$tanggal}";
                if (!Cache::has($cacheKey)) {
                    $guru->notify(new GuruAbsensiWarningNotification('sekolah', $batasTelat));
                    Cache::put($cacheKey, true, 86400); // Cache 24 jam
                    Log::info("Notif Warning Masuk dikirim ke {$guru->name}");
                }
            }
        }
    }

    private function checkAbsenMengajar($guru, $sekolahId, $now, $hari, $tanggal)
    {
        $jadwals = JadwalPelajaran::where('guru_user_id', $guru->id)
            ->where('sekolah_id', $sekolahId)
            ->where('hari', $hari)
            ->with('mataPelajaran', 'kelas')
            ->get();

        foreach ($jadwals as $jadwal) {
            $jamMulai = Carbon::parse($tanggal . ' ' . $jadwal->jam_mulai);
            $jamSelesai = Carbon::parse($tanggal . ' ' . $jadwal->jam_selesai);
            
            // RANGE WAKTU PENGECEKAN:
            // Mulai: 5 menit sebelum kelas
            // Sampai: Jam selesai kelas (Agar kalau telat banget tetap diingatkan)
            $startWindow = $jamMulai->copy()->subMinutes(5); 
            $endWindow = $jamSelesai; 

            // Logging untuk debug (Cek laravel.log nanti)
            // Log::info("Cek Jadwal ID: {$jadwal->id} | Jam: {$jadwal->jam_mulai} | Now: {$now->format('H:i')}");

            if ($now->between($startWindow, $endWindow)) {
                
                $sudahAbsen = AbsensiPelajaran::where('jadwal_pelajaran_id', $jadwal->id)
                    ->whereDate('tanggal', $tanggal)
                    ->exists();

                if (!$sudahAbsen) {
                    // Cache Key unik per jadwal & tanggal
                    $cacheKey = "notif_masuk_ajar_{$guru->id}_{$jadwal->id}_{$tanggal}";
                    
                    if (!Cache::has($cacheKey)) {
                        $batasTelat = $jamMulai->copy()->addMinutes(15);
                        $statusPesan = $now->gt($batasTelat) ? "TERLAMBAT (Lewat 15 Menit)" : "PERINGATAN";

                        $detail = "{$jadwal->mataPelajaran->nama_mapel} ({$jadwal->kelas->nama_kelas})\n";
                        $detail .= "Jam: {$jadwal->jam_mulai}\n";
                        $detail .= "Status: *{$statusPesan}*";

                        $guru->notify(new GuruAbsensiWarningNotification('pelajaran', $detail));
                        
                        // Simpan di cache
                        Cache::put($cacheKey, true, 3600); 
                        
                        Log::info("WA Terkirim ke {$guru->name} | Status: {$statusPesan}");
                    } else {
                        // Log::info("Skip: Cache exists for {$guru->name}");
                    }
                } else {
                    // Log::info("Skip: Sudah absen");
                }
            }
        }
    }

    /**
     * Cek Lupa Pulang (> 60 menit dari jadwal pulang)
     */
    private function checkAbsenPulang($guru, $settings, $now, $hari, $tanggal)
    {
        // Cek dulu: Apakah dia SUDAH masuk tapi BELUM pulang?
        $absensi = AbsensiGuru::where('guru_user_id', $guru->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        // Jika tidak ada record absen masuk, atau sudah absen pulang -> Skip
        if (!$absensi || !$absensi->jam_masuk || $absensi->jam_pulang) {
            return;
        }

        // Tentukan Jam Pulang
        $jamPulang = $settings->jam_pulang_awal; // Default Full-time
        
        if ($guru->guru && $guru->guru->tipe_jam_kerja == 'flexi') {
            $jadwalTerakhir = JadwalPelajaran::where('guru_user_id', $guru->id)
                ->where('hari', $hari)
                ->orderByDesc('jam_selesai')
                ->first();
            
            if ($jadwalTerakhir) {
                $jamPulang = $jadwalTerakhir->jam_selesai;
            } else {
                return; // Flexi tanpa jadwal
            }
        }

        // Batas Trigger: Jam Pulang + 60 menit
        $triggerTime = Carbon::parse($jamPulang)->addMinutes(60);

        if ($now->greaterThan($triggerTime)) {
            $cacheKey = "notif_lupa_pulang_{$guru->id}_{$tanggal}";
            if (!Cache::has($cacheKey)) {
                $guru->notify(new GuruAbsensiReminderNotification($jamPulang));
                Cache::put($cacheKey, true, 86400);
                Log::info("Notif Lupa Pulang dikirim ke {$guru->name}");
            }
        }
    }
}