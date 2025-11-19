<?php
namespace App\Http\Controllers\Sekolah\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\SekolahAbsensiSetting;
use App\Models\Sekolah\SekolahHariLibur;
use App\Models\Sekolah\SekolahLokasiGeofence;
use App\Models\Sekolah\AbsensiGuru;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Notifications\GuruAbsensiNotification;

class AbsensiKehadiranController extends Controller
{
    // === HELPER FUNCTIONS ===
    private function getGuruData()
    {
        $guruUser = Auth::user(); //
        // Ambil sekolah PERTAMA yang ditugaskan ke guru
        $sekolah = $guruUser->sekolahs()->first(); //
        if (!$sekolah) {
            abort(403, 'Akun Anda tidak ditugaskan ke unit sekolah manapun.');
        }
        return compact('guruUser', 'sekolah');
    }

    // Fungsi untuk menghitung jarak antara 2 titik GPS (Haversine Formula)
    private function hitungJarak($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // dalam kilometer
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
        return $distance * 1000; // ubah ke meter
    }
    
    // === CONTROLLER METHODS ===

    /**
     * Tampilkan halaman Absen Masuk/Pulang
     */
    public function index()
    {
        $data = $this->getGuruData();
        
        // Ambil pengaturan absensi untuk sekolah ini
        $settings = SekolahAbsensiSetting::where('sekolah_id', $data['sekolah']->id)->first(); //
        
        // Cek apakah hari ini libur
        $isHariKerja = in_array(
            now()->locale('id_ID')->isoFormat('dddd'), 
            $settings->hari_kerja ?? [] //
        );
        $isHariLibur = SekolahHariLibur::where('sekolah_id', $data['sekolah']->id) //
                        ->whereDate('tanggal', today())
                        ->exists();
        
        // Ambil data absensi hari ini
        $absensiHariIni = AbsensiGuru::where('guru_user_id', $data['guruUser']->id) //
                            ->whereDate('tanggal', today())
                            ->first();
                            
        return view('sekolah.guru.absensi-kehadiran.index', compact(
            'settings', 
            'absensiHariIni',
            'isHariKerja',
            'isHariLibur'
        ));
    }

    private function getPondokId()
    {
        //
        return Auth::user()->pondokStaff->pondok_id; 
    }
    
    /**
     * Proses Absensi (Masuk/Pulang) via Geofence (GPS) + Kode Harian
     */
    public function store(Request $request) 
    {
        $validated = $request->validate([
            'tipe_absen' => 'required|in:masuk,pulang',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'kode_absen' => 'required|numeric|digits:6',
        ]);

        $data = $this->getGuruData();
        $guruUser = $data['guruUser'];
        $sekolah = $data['sekolah'];
        $now = now();
        $waktuSekarang = $now->format('H:i:s');

        // 1. Ambil semua konfigurasi
        $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first();
        $lokasiTerdaftar = SekolahLokasiGeofence::where('sekolah_id', $sekolah->id)->get();
        $isHariKerja = in_array($now->locale('id_ID')->isoFormat('dddd'), $settings->hari_kerja ?? []);
        $isHariLibur = SekolahHariLibur::where('sekolah_id', $sekolah->id)->whereDate('tanggal', $now)->exists();

        // 2. Validasi Hari & Jam
        if (!$isHariKerja) return back()->with('error', 'Gagal: Hari ini bukan hari kerja.');
        if ($isHariLibur) return back()->with('error', 'Gagal: Hari ini adalah hari libur.');
        
        // 3. VALIDASI KODE HARIAN (Anti-Fake GPS)
        $pondokId = $this->getPondokId(); // Ambil ID Pondok
        $kodeValid = Cache::get('totp_pondok_' . $pondokId);
        if (!$kodeValid || $validated['kode_absen'] != $kodeValid) {
            return back()->with('error', 'Gagal: Kode Absensi Harian salah.');
        }

        // 4. Validasi Lokasi (Geofence)
        $lokasiValid = false;
        $namaLokasiValid = 'Luar Area';
        foreach ($lokasiTerdaftar as $lokasi) {
            $jarak = $this->hitungJarak(
                $validated['latitude'], $validated['longitude'],
                $lokasi->latitude, $lokasi->longitude
            );
            
            if ($jarak <= $lokasi->radius) {
                $lokasiValid = true;
                $namaLokasiValid = $lokasi->nama_lokasi;
                break;
            }
        }
        
        if (!$lokasiValid) {
            return back()->with('error', 'Gagal: Anda berada di luar radius absensi sekolah.');
        }

        // Default: Gunakan Global Settings (Full-time)
        $targetMasukAwal = $settings->jam_masuk;
        $targetBatasTelat = $settings->batas_telat;
        $targetPulangAwal = $settings->jam_pulang_awal;
        $targetPulangAkhir = $settings->jam_pulang_akhir;

        // Override jika Tipe Guru adalah FLEXI
        if ($guruProfile && $guruProfile->tipe_jam_kerja == 'flexi') {
            // Cari jadwal mengajar guru HARI INI
            $jadwals = \App\Models\Sekolah\JadwalPelajaran::where('guru_user_id', $guruUser->id)
                        ->where('hari', $namaHariIni)
                        ->orderBy('jam_mulai') // Urutkan dari pagi
                        ->get();

            if ($jadwals->isEmpty()) {
                // Kasus: Guru Flexi tapi TIDAK ADA jadwal hari ini
                return back()->with('error', 'Anda berstatus Flexi dan tidak memiliki jadwal mengajar hari ini, jadi tidak perlu absen.');
            }

            $jadwalPertama = $jadwals->first();
            $jadwalTerakhir = $jadwals->last();

            // Aturan Flexi:
            // Masuk: Boleh absen 60 menit sebelum jam pertama dimulai
            $targetMasukAwal = \Carbon\Carbon::parse($jadwalPertama->jam_mulai)->subMinutes(60)->format('H:i:s');
            
            // Telat: Jika lewat dari jam mulai pelajaran pertama
            $targetBatasTelat = $jadwalPertama->jam_mulai;

            // Pulang: Boleh pulang segera setelah jam terakhir selesai
            $targetPulangAwal = $jadwalTerakhir->jam_selesai;
            
            // Pulang Akhir: Sampai jam 23:59 (bebas)
            $targetPulangAkhir = '23:59:00';
        }

        // === PROSES SIMPAN ABSENSI ===
        
        $absensiHariIni = AbsensiGuru::firstOrNew(
            ['guru_user_id' => $guruUser->id, 'tanggal' => $now->format('Y-m-d')],
            ['sekolah_id' => $sekolah->id]
        );
        
        $pesanSukses = '';
        
        if ($validated['tipe_absen'] == 'masuk') {
            if ($absensiHariIni->jam_masuk) return back()->with('error', 'Anda sudah melakukan absen masuk.');
            
            // Validasi Jam Dinamis
            if ($waktuSekarang < $targetMasukAwal) {
                 return back()->with('error', 'Belum waktunya absen masuk. (Jadwal Anda mulai: ' . ($guruProfile->tipe_jam_kerja == 'flexi' ? $targetBatasTelat : $targetMasukAwal) . ')');
            }
            if ($waktuSekarang > $targetBatasTelat) {
                 return back()->with('error', 'Anda terlambat. Batas waktu adalah ' . $targetBatasTelat);
            }
            
            $absensiHariIni->jam_masuk = $waktuSekarang;
            $absensiHariIni->status = 'hadir';
            $absensiHariIni->verifikasi_masuk = "GPS+KODE: " . $namaLokasiValid;
            $pesanSukses = 'Absen Masuk berhasil (' . ($guruProfile->tipe_jam_kerja == 'flexi' ? 'Flexi' : 'Full-Time') . ')';

        } elseif ($validated['tipe_absen'] == 'pulang') {
            if (!$absensiHariIni->jam_masuk) return back()->with('error', 'Anda belum melakukan absen masuk.');
            if ($absensiHariIni->jam_pulang) return back()->with('error', 'Anda sudah melakukan absen pulang.');
            
            // Validasi Jam Dinamis
            if ($waktuSekarang < $targetPulangAwal) {
                return back()->with('error', 'Belum waktunya pulang. (Waktu pulang Anda: ' . $targetPulangAwal . ')');
            }
            if ($waktuSekarang > $targetPulangAkhir) {
                return back()->with('error', 'Melewati batas waktu sistem.');
            }

            $absensiHariIni->jam_pulang = $waktuSekarang;
            $absensiHariIni->verifikasi_pulang = "GPS+KODE: " . $namaLokasiValid;
            $pesanSukses = 'Absen Pulang berhasil.';
        }

        $absensiHariIni->save();

        // Kirim Notifikasi WA (Sama seperti sebelumnya)
        try {
            if ($guruProfile) {
                $guruProfile->notify((new \App\Notifications\GuruAbsensiNotification($absensiHariIni, $pesanSukses))->delay(now()->addSeconds(5)));
            }
        } catch (\Exception $e) {}

        return redirect()->route('sekolah.guru.absensi.kehadiran.index')
                         ->with('success', $pesanSukses);
    }
}