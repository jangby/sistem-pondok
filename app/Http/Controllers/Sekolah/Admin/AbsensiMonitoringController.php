<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\AbsensiGuru;
use App\Models\Sekolah\AbsensiSiswaPelajaran;
use App\Models\Sekolah\JadwalPelajaran; // <-- Import
use App\Models\Sekolah\AbsensiPelajaran; // <-- Import
use App\Models\Sekolah\SekolahAbsensiSetting; // <-- Import
use App\Models\Sekolah\SekolahHariLibur; // <-- Import
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Carbon; // <-- Import

class AbsensiMonitoringController extends Controller
{
    // === HELPER FUNCTIONS ===
    private function getSekolah()
    {
        $adminUser = Auth::user();
        $sekolah = $adminUser->sekolahs()->first(); //
        if (!$sekolah) {
            abort(403, 'Akun Anda tidak ditugaskan ke unit sekolah manapun.');
        }
        return $sekolah;
    }
    
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id; //
    }

    /**
     * Tampilkan Dashboard Kontrol Absensi (Pengganti rekapGuru)
     */
    public function rekapGuru(Request $request)
    {
        $sekolah = $this->getSekolah();
        $pondokId = $this->getPondokId();
        $today = Carbon::today();
        
        // 1. Dapatkan Pengaturan Absensi
        $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first(); //
        if (!$settings) {
            // Jika admin belum setting, kirim error
            return view('sekolah.admin.monitoring.rekap-guru')
                ->with('error', 'Pengaturan absensi (jam kerja, hari kerja) belum diatur. Silakan atur di menu Konfigurasi.');
        }

        // 2. Cek Hari Kerja & Hari Libur
        $namaHariIni = $today->locale('id_ID')->isoFormat('dddd');
        $isHariKerja = in_array($namaHariIni, $settings->hari_kerja ?? []); //
        $isHariLibur = SekolahHariLibur::where('sekolah_id', $sekolah->id)->whereDate('tanggal', $today)->exists(); //

        // Jika bukan hari kerja atau hari libur, tampilkan halaman libur
        if (!$isHariKerja || $isHariLibur) {
            return view('sekolah.admin.monitoring.rekap-guru', [
                'isHariLibur' => true,
                'isHariKerja' => $isHariKerja,
                'namaHariIni' => $namaHariIni,
            ]);
        }
        
        // 3. Ambil Data Guru
        // Ambil semua guru yang ditugaskan ke sekolah ini
        $allGurus = $sekolah->users()
                           ->whereHas('roles', fn($q) => $q->where('name', 'guru'))
                           ->orderBy('name')
                           ->get(); //
                           
        // Ambil absensi guru HARI INI
        $absensiGuruHariIni = AbsensiGuru::where('sekolah_id', $sekolah->id) //
                                ->whereDate('tanggal', $today)
                                ->get()
                                ->keyBy('guru_user_id'); // Jadikan user_id sebagai key

        // 4. Hitung KPI Guru & Pisahkan Daftar
        $kpi_hadir = 0;
        $kpi_terlambat = 0;
        $kpi_sakit_izin = 0;
        $daftarHadir = [];
        $daftarBelumHadir = [];

        foreach ($allGurus as $guru) {
            if ($absensi = $absensiGuruHariIni->get($guru->id)) {
                // Jika ada di log absensi
                if ($absensi->status == 'hadir') {
                    if ($absensi->jam_masuk > $settings->batas_telat) {
                        $kpi_terlambat++;
                    } else {
                        $kpi_hadir++;
                    }
                    $daftarHadir[] = $absensi;
                } else {
                    // Status 'sakit' atau 'izin'
                    $kpi_sakit_izin++;
                    $daftarBelumHadir[] = $absensi; // Masukkan ke "Belum Hadir" dengan status Sakit/Izin
                }
            } else {
                // Jika TIDAK ADA di log absensi
                $daftarBelumHadir[] = $guru; // Ini adalah guru (objek User)
            }
        }
        
        $kpi_alpa = count($daftarBelumHadir) - $kpi_sakit_izin;

        // 5. Hitung KPI Jam Pelajaran Kosong
        $jadwalHariIniCount = JadwalPelajaran::where('sekolah_id', $sekolah->id) //
                                ->where('hari', $namaHariIni)
                                ->count();
        
        $pelajaranTerisiCount = AbsensiPelajaran::whereDate('tanggal', $today) //
                                ->whereHas('jadwalPelajaran', fn($q) => $q->where('sekolah_id', $sekolah->id))
                                ->count();

        $kpi_jam_kosong = $jadwalHariIniCount - $pelajaranTerisiCount;

        return view('sekolah.admin.monitoring.rekap-guru', compact(
            'isHariLibur', 'isHariKerja', 'settings',
            'kpi_hadir', 'kpi_terlambat', 'kpi_sakit_izin', 'kpi_alpa', 'kpi_jam_kosong',
            'daftarHadir', 'daftarBelumHadir'
        ));
    }
    
    /**
     * Tampilkan Dashboard Monitoring Siswa (Pengganti tabel biasa)
     */
    public function rekapSiswa(Request $request)
    {
        $sekolah = $this->getSekolah();
        $pondokId = $this->getPondokId();
        $today = \Illuminate\Support\Carbon::today();

        // 1. Ambil Pengaturan & Hari Libur
        $settings = \App\Models\Sekolah\SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first();
        $isHariLibur = \App\Models\Sekolah\SekolahHariLibur::where('sekolah_id', $sekolah->id)->whereDate('tanggal', $today)->exists();
        $namaHariIni = $today->locale('id_ID')->isoFormat('dddd');
        $isHariKerja = in_array($namaHariIni, $settings->hari_kerja ?? []);

        if (!$isHariKerja || $isHariLibur) {
            // Tampilkan view mode libur (sama seperti guru)
            return view('sekolah.admin.monitoring.rekap-siswa', [
                'isHariLibur' => true, 'isHariKerja' => $isHariKerja, 'namaHariIni' => $namaHariIni,
                'kpi_hadir' => 0, 'kpi_terlambat' => 0, 'kpi_sakit_izin' => 0, 'kpi_alpa' => 0, 'kpi_total' => 0
            ]);
        }

        // 2. Ambil Semua Santri Aktif di Sekolah Ini (via Kelas)
        $allSantris = \App\Models\Santri::where('pondok_id', $pondokId)
            ->where('status', 'active')
            ->whereHas('kelas', fn($q) => $q->where('tingkat', $sekolah->tingkat)) // Filter tingkat sekolah (MTS/MA)
            ->with('kelas')
            ->orderBy('full_name')
            ->get();

        // 3. Ambil Data Absensi IoT Hari Ini
        $absensiHariIni = \App\Models\Sekolah\AbsensiSiswaSekolah::where('sekolah_id', $sekolah->id)
            ->whereDate('tanggal', $today)
            ->get()
            ->keyBy('santri_id');

        // 4. Ambil Data Sakit/Izin Hari Ini
        $sakitIds = \App\Models\KesehatanSantri::where('status', '!=', 'sembuh')
            ->whereDate('tanggal_sakit', '<=', $today)
            ->where(fn($q) => $q->whereDate('tanggal_sembuh', '>=', $today)->orWhereNull('tanggal_sembuh'))
            ->pluck('santri_id')->toArray();

        $izinIds = \App\Models\Perizinan::where('status', 'disetujui')
            ->whereDate('tgl_mulai', '<=', $today)
            ->whereDate('tgl_selesai_rencana', '>=', $today)
            ->pluck('santri_id')->toArray();

        // 5. Hitung KPI & Pisahkan Daftar
        $kpi_hadir = 0;
        $kpi_terlambat = 0;
        $kpi_sakit_izin = 0;
        
        $daftarHadir = [];
        $daftarBelumHadir = [];

        foreach ($allSantris as $santri) {
            $status = 'alpa'; // Default
            $dataAbsen = $absensiHariIni->get($santri->id);

            if ($dataAbsen) {
                // SUDAH SCAN
                $status = 'hadir';
                if ($dataAbsen->status_masuk == 'terlambat') {
                    $kpi_terlambat++;
                    $status = 'terlambat';
                } else {
                    $kpi_hadir++;
                }
                // Masukkan ke list hadir
                $santri->data_absen = $dataAbsen;
                $santri->status_hari_ini = $status;
                $daftarHadir[] = $santri;
            } else {
                // BELUM SCAN
                if (in_array($santri->id, $sakitIds)) {
                    $status = 'sakit';
                    $kpi_sakit_izin++;
                } elseif (in_array($santri->id, $izinIds)) {
                    $status = 'izin';
                    $kpi_sakit_izin++;
                } else {
                    $status = 'alpa'; // Belum hadir tanpa keterangan
                }
                
                $santri->status_hari_ini = $status;
                $daftarBelumHadir[] = $santri;
            }
        }

        $kpi_alpa = count($daftarBelumHadir) - $kpi_sakit_izin;
        $kpi_total = $allSantris->count();

        return view('sekolah.admin.monitoring.rekap-siswa', compact(
            'isHariLibur', 'isHariKerja', 'settings',
            'kpi_hadir', 'kpi_terlambat', 'kpi_sakit_izin', 'kpi_alpa', 'kpi_total',
            'daftarHadir', 'daftarBelumHadir'
        ));
    }
}