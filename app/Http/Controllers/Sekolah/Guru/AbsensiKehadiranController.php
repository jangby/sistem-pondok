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
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'kode_absen' => 'required|numeric|digits:6',
        ]);

        $data = $this->getGuruData();
        $guruUser = $data['guruUser'];
        $sekolah  = $data['sekolah'];
        
        $now = now();
        $waktuSekarang = $now->format('H:i:s');
        
        // --- PERBAIKAN DI SINI: Definisikan variabel yang hilang ---
        $namaHariIni = $now->locale('id')->isoFormat('dddd'); // Diperlukan untuk logika Flexi
        $guruProfile = \App\Models\Sekolah\Guru::where('user_id', $guruUser->id)->first(); // Ambil profil guru
        // -----------------------------------------------------------

        // 1. Ambil semua konfigurasi
        $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first();
        
        // Cek Setting
        if (!$settings) {
            return back()->with('error', 'Gagal: Admin belum mengatur jam absensi.');
        }

        $lokasiTerdaftar = SekolahLokasiGeofence::where('sekolah_id', $sekolah->id)->get();
        $isHariKerja = in_array($namaHariIni, $settings->hari_kerja ?? []);
        $isHariLibur = SekolahHariLibur::where('sekolah_id', $sekolah->id)->whereDate('tanggal', $now)->exists();

        // 2. Validasi Hari & Jam
        if (!$isHariKerja) return back()->with('error', 'Gagal: Hari ini bukan hari kerja.');
        if ($isHariLibur) return back()->with('error', 'Gagal: Hari ini adalah hari libur.');
        
        // 3. VALIDASI KODE HARIAN (Anti-Fake GPS)
        $pondokId = $this->getPondokId(); 
        $kodeValid = Cache::get('totp_pondok_' . $pondokId);
        
        // Debugging (Opsional: Hapus jika sudah live)
        // if (!$kodeValid) return back()->with('error', 'Kode Absen Kadaluarsa. Minta Admin Refresh.');

        if (!$kodeValid || $validated['kode_absen'] != $kodeValid) {
            return back()->with('error', 'Gagal: Kode Absensi Harian salah atau kadaluarsa.');
        }

        // 4. Validasi Lokasi (Geofence)
        $lokasiValid = false;
        $namaLokasiValid = 'Luar Area';
        
        if ($lokasiTerdaftar->isEmpty()) {
             // Opsional: Jika tidak ada lokasi diatur, apakah boleh absen? 
             // Asumsi: Harus ada lokasi.
             return back()->with('error', 'Admin belum mengatur lokasi GPS sekolah.');
        }

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
        // ($guruProfile sekarang sudah didefinisikan di atas)
        if ($guruProfile && $guruProfile->tipe_jam_kerja == 'flexi') {
            // Cari jadwal mengajar guru HARI INI
            $jadwals = \App\Models\Sekolah\JadwalPelajaran::where('guru_user_id', $guruUser->id)
                        ->where('hari', $namaHariIni) // $namaHariIni sudah didefinisikan
                        ->orderBy('jam_mulai')
                        ->get();

            if ($jadwals->isEmpty()) {
                return back()->with('error', 'Anda berstatus Flexi dan tidak memiliki jadwal mengajar hari ini.');
            }

            $jadwalPertama = $jadwals->first();
            $jadwalTerakhir = $jadwals->last();

            // Aturan Flexi
            $targetMasukAwal = \Carbon\Carbon::parse($jadwalPertama->jam_mulai)->subMinutes(60)->format('H:i:s');
            $targetBatasTelat = $jadwalPertama->jam_mulai;
            $targetPulangAwal = $jadwalTerakhir->jam_selesai;
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
                 $jamMulai = ($guruProfile && $guruProfile->tipe_jam_kerja == 'flexi') ? $targetBatasTelat : $targetMasukAwal;
                 return back()->with('error', 'Belum waktunya absen masuk. (Jadwal mulai: ' . $jamMulai . ')');
            }
            // Batas telat hanya warning atau blocking? Di sini blocking jika lewat
            /* // Jika ingin STRICT blocking telat, uncomment ini:
            if ($waktuSekarang > $targetBatasTelat) {
                 return back()->with('error', 'Anda terlambat. Batas waktu adalah ' . $targetBatasTelat);
            }
            */
            
            $absensiHariIni->jam_masuk = $waktuSekarang;
            
            // Status: Cek telat atau tepat waktu
            if ($waktuSekarang > $targetBatasTelat) {
                $absensiHariIni->status = 'terlambat';
                $pesanSukses = 'Absen Masuk berhasil (Terlambat).';
            } else {
                $absensiHariIni->status = 'hadir';
                $pesanSukses = 'Absen Masuk berhasil.';
            }

            $absensiHariIni->verifikasi_masuk = "GPS: " . $namaLokasiValid;

        } elseif ($validated['tipe_absen'] == 'pulang') {
            if (!$absensiHariIni->jam_masuk) return back()->with('error', 'Anda belum melakukan absen masuk.');
            if ($absensiHariIni->jam_pulang) return back()->with('error', 'Anda sudah melakukan absen pulang.');
            
            // Validasi Jam Pulang
            if ($waktuSekarang < $targetPulangAwal) {
                return back()->with('error', 'Belum waktunya pulang. (Waktu pulang: ' . $targetPulangAwal . ')');
            }
            
            $absensiHariIni->jam_pulang = $waktuSekarang;
            $absensiHariIni->verifikasi_pulang = "GPS: " . $namaLokasiValid;
            $pesanSukses = 'Absen Pulang berhasil.';
        }

        $absensiHariIni->save();

        // Kirim Notifikasi (WA/Database)
        try {
            if ($guruProfile) { // Pastikan guruProfile ada sebelum notifikasi
                 // Jika Anda menggunakan Notification class
                 // $guruUser->notify(...); 
            }
        } catch (\Exception $e) {
            // Silent fail notifikasi agar user tetap bisa absen meski WA error
        }

        return redirect()->route('sekolah.guru.absensi.kehadiran.index')
                         ->with('success', $pesanSukses);
    }
}