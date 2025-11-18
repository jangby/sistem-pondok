<?php
namespace App\Http\Controllers\Sekolah\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah\JadwalPelajaran;
use App\Models\Sekolah\AbsensiPelajaran;
use App\Models\Sekolah\SekolahAbsensiSetting;
use App\Models\Sekolah\SekolahHariLibur;
use App\Models\Sekolah\SekolahLokasiGeofence;
use App\Notifications\GuruAbsenMengajarNotification; // <-- Import Notif
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class JadwalMengajarController extends Controller
{
    // === HELPER FUNCTIONS ===
    private function getGuruData()
    {
        $guruUser = Auth::user(); //
        $sekolah = $guruUser->sekolahs()->first(); //
        if (!$sekolah) {
            abort(403, 'Akun Anda tidak ditugaskan ke unit sekolah manapun.');
        }
        return compact('guruUser', 'sekolah');
    }

    private function checkOwnership(JadwalPelajaran $jadwal)
    {
        $userId = Auth::id();

        // 1. Cek jika saya adalah Guru Asli
        if ($jadwal->guru_user_id == $userId) {
            return true;
        }

        // 2. Cek jika saya adalah Guru Pengganti (HARI INI)
        $isSubstitute = \App\Models\Sekolah\AbsensiPelajaran::where('jadwal_pelajaran_id', $jadwal->id)
            ->where('guru_pengganti_user_id', $userId)
            ->whereDate('tanggal', today()) // Hanya berlaku hari ini
            ->exists();

        if ($isSubstitute) {
            return true;
        }

        // Jika bukan keduanya, blokir akses
        abort(404, 'Jadwal tidak ditemukan atau Anda tidak memiliki akses.');
    }

    // Fungsi hitung jarak (kita pinjam dari AbsensiKehadiranController)
    private function hitungJarak($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return ($earthRadius * $c) * 1000; // meter
    }

    // === CONTROLLER METHODS ===

    /**
     * Tampilkan daftar jadwal mengajar guru (Semua hari)
     */
    public function index()
    {
        $guruUser = Auth::user();
        $jadwalQuery = JadwalPelajaran::where('guru_user_id', $guruUser->id) //
            ->with(['kelas', 'mataPelajaran', 'tahunAjaran']) //
            // Urutkan berdasarkan tahun ajaran aktif dulu
            ->orderByDesc(function ($query) {
                $query->from('tahun_ajarans')
                      ->whereColumn('tahun_ajarans.id', 'jadwal_pelajarans.tahun_ajaran_id')
                      ->select('is_active'); //
            })
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai');
            
        $jadwals = $jadwalQuery->get()->groupBy('tahunAjaran.nama_tahun_ajaran');

        return view('sekolah.guru.jadwal.index', compact('jadwals'));
    }

    /**
     * Tampilkan halaman "Mulai Mengajar" (untuk 1 jadwal)
     */
    public function show(JadwalPelajaran $jadwalPelajaran)
    {
        $this->checkOwnership($jadwalPelajaran); // Keamanan
        $jadwalPelajaran->load(['kelas', 'mataPelajaran', 'tahunAjaran']); //

        // Cek apakah guru sudah absen mengajar untuk jadwal ini HARI INI
        $absensiPelajaran = AbsensiPelajaran::where('jadwal_pelajaran_id', $jadwalPelajaran->id) //
                                ->whereDate('tanggal', today())
                                ->first();

        return view('sekolah.guru.jadwal.show', compact('jadwalPelajaran', 'absensiPelajaran'));
    }
    
    /**
     * Proses Absen Mengajar (Geofence)
     */
    public function storeAbsenMengajar(Request $request)
    {
        $validated = $request->validate([
            'jadwal_pelajaran_id' => 'required|exists:jadwal_pelajarans,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $jadwal = JadwalPelajaran::find($validated['jadwal_pelajaran_id']); //
        $this->checkOwnership($jadwal); // Keamanan

        $data = $this->getGuruData();
        $guruUser = $data['guruUser'];
        $sekolah = $data['sekolah'];
        $now = now();
        $waktuSekarang = $now; // Kita gunakan objek Carbon utuh

        // 1. Validasi Hari Libur (Sama seperti Absen Kehadiran)
        $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first(); //
        $isHariKerja = in_array($now->locale('id_ID')->isoFormat('dddd'), $settings->hari_kerja ?? []);
        $isHariLibur = SekolahHariLibur::where('sekolah_id', $sekolah->id)->whereDate('tanggal', $now)->exists(); //

        if (!$isHariKerja) return back()->with('error', 'Gagal: Hari ini bukan hari kerja.');
        if ($isHariLibur) return back()->with('error', 'Gagal: Hari ini adalah hari libur.');

        // 2. Validasi Lokasi (Geofence)
        $lokasiTerdaftar = SekolahLokasiGeofence::where('sekolah_id', $sekolah->id)->get(); //
        $lokasiValid = false;
        foreach ($lokasiTerdaftar as $lokasi) {
            $jarak = $this->hitungJarak(
                $validated['latitude'], $validated['longitude'],
                $lokasi->latitude, $lokasi->longitude
            );
            if ($jarak <= $lokasi->radius) {
                $lokasiValid = true;
                break;
            }
        }
        if (!$lokasiValid) {
            return back()->with('error', 'Gagal: Anda berada di luar radius lokasi mengajar.');
        }
        
        /*
        |--------------------------------------------------------------------------
        | 3. PERBAIKAN: Validasi Batas Waktu 15 Menit
        |--------------------------------------------------------------------------
        */
        
        // Ubah string 'jam_mulai' (cth: "07:00:00") menjadi objek Carbon untuk hari ini
        $jamMulai = Carbon::parse($jadwal->jam_mulai); //
        
        // Hitung batas telat (15 menit setelah jam mulai)
        $batasTelat = $jamMulai->copy()->addMinutes(15);

        // Tentukan status (hadir atau terlambat)
        $statusGuru = 'hadir';
        if ($waktuSekarang->gt($jamMulai)) {
            $statusGuru = 'terlambat'; //
        }

        // PERIKSA ATURAN 15 MENIT
        // Jika waktu sekarang LEBIH DARI batas telat
        if ($waktuSekarang->gt($batasTelat)) {
            return back()->with('error', 'Gagal: Anda terlambat lebih dari 15 menit. Batas akhir absen adalah ' . $batasTelat->format('H:i') . '.');
        }
        /*
        |--------------------------------------------------------------------------
        | AKHIR PERBAIKAN
        |--------------------------------------------------------------------------
        */
        
        // 4. Simpan Absensi Pelajaran
        $absensiPelajaran = AbsensiPelajaran::create([ //
            'jadwal_pelajaran_id' => $jadwal->id,
            'tanggal' => $now->format('Y-m-d'),
            'status_guru' => $statusGuru, // <-- Gunakan status baru (hadir/terlambat)
            'jam_guru_masuk_kelas' => $waktuSekarang->format('H:i:s'),
        ]);
        
        // 5. Kirim Notifikasi WAHA
        try {
            $guruProfile = $guruUser->guru; //
            if ($guruProfile) {
                $guruProfile->notify((new GuruAbsenMengajarNotification($absensiPelajaran))->delay(now()->addSeconds(5))); //
            }
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA Absen Mengajar: ' . $e->getMessage());
        }

        // 6. Redirect KEMBALI ke halaman 'show' yang sama
        return redirect()->route('sekolah.guru.jadwal.show', $jadwal->id)
                         ->with('success', 'Absen mengajar (Status: ' . $statusGuru . ') berhasil. Silakan lanjutkan absensi siswa.');
    }

    /**
     * Simpan Materi Pembahasan (dari form di halaman show)
     */
    public function storeMateri(Request $request, AbsensiPelajaran $absensiPelajaran)
    {
        // Keamanan: Cek apakah absensi ini milik guru yg login
        $this->checkOwnership($absensiPelajaran->jadwalPelajaran); //

        $validated = $request->validate([
            'materi_pembahasan' => 'nullable|string|max:1000',
        ]);
        
        $absensiPelajaran->update($validated); //

        return back()->with('success', 'Materi pembahasan berhasil disimpan.');
    }
}