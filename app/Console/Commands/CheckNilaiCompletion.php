<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sekolah\KegiatanAkademik;
use App\Models\Sekolah\Nilai;
use App\Models\Kelas;
use App\Models\User;
use App\Notifications\GuruNilaiReminderNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CheckNilaiCompletion extends Command
{
    protected $signature = 'nilai:check-completion';
    protected $description = 'Cek kegiatan akademik yang mendekati deadline dan kirim notifikasi ke guru yang belum selesai.';

    public function handle()
    {
        $today = Carbon::today();
        
        // 1. Cari Kegiatan yang deadline-nya Hari Ini
        $kegiatans = KegiatanAkademik::whereDate('tanggal_selesai', $today)
                                   ->get();

        foreach ($kegiatans as $kegiatan) {
            // Cache Key untuk mencegah spam hari ini
            $cacheKey = "notif_nilai_deadline_{$kegiatan->id}";
            if (Cache::has($cacheKey)) continue;

            $sekolahId = $kegiatan->sekolah_id;
            
            // 2. Ambil semua jadwal pelajaran yang terkait dengan kegiatan ini
            $jadwalIds = \App\Models\Sekolah\JadwalPelajaran::where('sekolah_id', $sekolahId)
                                                                ->where('tahun_ajaran_id', $kegiatan->tahun_ajaran_id)
                                                                ->pluck('id');

            // 3. Cari guru yang belum 100% selesai
            $gurus = User::role('guru')
                        ->whereHas('sekolahs', fn($q) => $q->where('sekolahs.id', $sekolahId))
                        ->get();

            foreach ($gurus as $guru) {
                // Untuk setiap guru, hitung persentase penyelesaian
                $completion = $this->getCompletionRateForGuru($guru, $kegiatan);
                
                if ($completion < 100) {
                    // Kirim notifikasi jika ada nomor WA
                    if ($guru->telepon) {
                        $guru->notify(new GuruNilaiReminderNotification($kegiatan, $completion));
                        $this->info("Notif Nilai dikirim ke {$guru->name} ({$completion}%)");
                    }
                }
            }
            
            // Tandai sudah diproses untuk hari ini
            Cache::put($cacheKey, true, 86400); 
        }
    }

    // Helper untuk menghitung persentase penyelesaian guru
    private function getCompletionRateForGuru(User $guru, KegiatanAkademik $kegiatan)
    {
        // ASUMSI: Guru hanya perlu input nilai untuk mapel yang dia ajarkan
        $taughtSubjects = \App\Models\Sekolah\JadwalPelajaran::where('guru_user_id', $guru->id)
                                            ->where('tahun_ajaran_id', $kegiatan->tahun_ajaran_id)
                                            ->pluck('mata_pelajaran_id')
                                            ->unique()
                                            ->toArray();

        $kelasIds = \App\Models\Sekolah\JadwalPelajaran::where('guru_user_id', $guru->id)
                                            ->where('tahun_ajaran_id', $kegiatan->tahun_ajaran_id)
                                            ->pluck('kelas_id')
                                            ->unique()
                                            ->toArray();
                                            
        if (empty($taughtSubjects) || empty($kelasIds)) return 100;

        $totalExpected = 0;
        $totalActual = 0;

        foreach ($kelasIds as $kelasId) {
            $totalSantri = Santri::where('kelas_id', $kelasId)->where('status', 'active')->count();
            
            foreach ($taughtSubjects as $mapelId) {
                $totalExpected += $totalSantri; // Total Santri * Total Mapel yang dia ajar
                
                $totalActual += Nilai::where('kelas_id', $kelasId)
                                    ->where('kegiatan_akademik_id', $kegiatan->id)
                                    ->where('mata_pelajaran_id', $mapelId)
                                    ->count();
            }
        }

        return $totalExpected > 0 ? round(($totalActual / $totalExpected) * 100, 1) : 100;
    }
}