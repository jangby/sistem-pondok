<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\SekolahWifi;
use App\Models\Sekolah\SekolahLokasiGeofence;
use App\Models\Sekolah\SekolahAbsensiSetting; // <-- Model BARU
use App\Models\Sekolah\SekolahHariLibur; // <-- Model BARU
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class KonfigurasiController extends Controller
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
        //
        return Auth::user()->pondokStaff->pondok_id; 
    }

    private function checkOwnershipWifi(SekolahWifi $sekolahWifi)
    {
        if ($sekolahWifi->sekolah_id != $this->getSekolah()->id) {
            abort(404);
        }
    }

    private function checkOwnershipGeofence(SekolahLokasiGeofence $sekolahLokasiGeofence)
    {
        if ($sekolahLokasiGeofence->sekolah_id != $this->getSekolah()->id) {
            abort(404);
        }
    }
    
    // --- HELPER BARU ---
    private function checkOwnershipHariLibur(SekolahHariLibur $sekolahHariLibur)
    {
        if ($sekolahHariLibur->sekolah_id != $this->getSekolah()->id) {
            abort(404);
        }
    }

    /**
     * Tampilkan halaman utama konfigurasi (4 Modul)
     */
    public function index()
    {
        $sekolah = $this->getSekolah();
        
        // Ambil data untuk 4 modul
        $absensiSettings = SekolahAbsensiSetting::firstOrCreate(
            ['sekolah_id' => $sekolah->id]
        );
        
        $hariLiburList = SekolahHariLibur::where('sekolah_id', $sekolah->id)
                            ->whereDate('tanggal', '>=', now()->startOfYear())
                            ->orderBy('tanggal', 'asc')
                            ->get();
        
        $wifiList = SekolahWifi::where('sekolah_id', $sekolah->id)->latest()->get(); //
        $geofenceList = SekolahLokasiGeofence::where('sekolah_id', $sekolah->id)->latest()->get(); //

        return view('sekolah.admin.konfigurasi.index', compact(
            'absensiSettings',
            'hariLiburList',
            'wifiList', 
            'geofenceList'
        ));
    }

    /**
     * Simpan Pengaturan Jam & Hari Kerja
     */
    public function storeSettings(Request $request)
    {
        $sekolah = $this->getSekolah();

        $validated = $request->validate([
            'jam_masuk' => 'required|date_format:H:i',
            'batas_telat' => 'required|date_format:H:i|after:jam_masuk',
            'jam_pulang_awal' => 'required|date_format:H:i|after:batas_telat',
            'jam_pulang_akhir' => 'required|date_format:H:i|after:jam_pulang_awal',
            'hari_kerja' => 'required|array|min:1', // Minimal 1 hari kerja
            'hari_kerja.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        ]);
        
        // Gunakan updateOrCreate untuk menyimpan 1 baris pengaturan
        SekolahAbsensiSetting::updateOrCreate(
            ['sekolah_id' => $sekolah->id], // Kunci pencarian
            $validated // Data yang di-update/dibuat
        );

        return redirect()->route('sekolah.admin.konfigurasi.index')
                         ->with('success', 'Pengaturan Jam & Hari Kerja berhasil disimpan.');
    }

    /**
     * Simpan data Hari Libur baru
     */
    public function storeHariLibur(Request $request)
    {
        $sekolah = $this->getSekolah();

        $validated = $request->validate([
            'tanggal' => [
                'required', 'date',
                Rule::unique('sekolah_hari_libur')->where(fn ($q) => $q->where('sekolah_id', $sekolah->id))
            ],
            'keterangan' => 'required|string|max:255',
        ], [
            'tanggal.unique' => 'Tanggal libur ini sudah pernah ditambahkan.'
        ]);
        
        $validated['sekolah_id'] = $sekolah->id;

        SekolahHariLibur::create($validated);

        return redirect()->route('sekolah.admin.konfigurasi.index')
                         ->with('success', 'Hari Libur berhasil ditambahkan.');
    }

    /**
     * Hapus data Hari Libur
     */
    public function destroyHariLibur(SekolahHariLibur $sekolahHariLibur)
    {
        $this->checkOwnershipHariLibur($sekolahHariLibur); // Keamanan
        $sekolahHariLibur->delete();
        return redirect()->route('sekolah.admin.konfigurasi.index')
                         ->with('success', 'Hari Libur berhasil dihapus.');
    }


    // --- FUNGSI LAMA (WiFi & Geofence) ---
    // (Tidak perlu diubah, biarkan seperti ini)

    public function storeWifi(Request $request)
    {
        $sekolah = $this->getSekolah();
        $validated = $request->validate([
            'nama_wifi_ssid' => 'required|string|max:255',
            'bssid' => [
                'nullable', 'string', 'mac_address',
                Rule::unique('sekolah_wifi')->where(fn ($q) => $q->where('sekolah_id', $sekolah->id)),
            ],
        ]);
        $validated['sekolah_id'] = $sekolah->id;
        SekolahWifi::create($validated); //
        return redirect()->route('sekolah.admin.konfigurasi.index')
                         ->with('success', 'Jaringan WiFi berhasil ditambahkan.');
    }

    public function destroyWifi(SekolahWifi $sekolahWifi)
    {
        $this->checkOwnershipWifi($sekolahWifi);
        $sekolahWifi->delete(); //
        return redirect()->route('sekolah.admin.konfigurasi.index')
                         ->with('success', 'Jaringan WiFi berhasil dihapus.');
    }

    public function storeGeofence(Request $request)
    {
        $sekolah = $this->getSekolah();
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:5|max:1000',
        ]);
        $validated['sekolah_id'] = $sekolah->id;
        SekolahLokasiGeofence::create($validated); //
        return redirect()->route('sekolah.admin.konfigurasi.index')
                         ->with('success', 'Lokasi Geofence berhasil ditambahkan.');
    }

    public function destroyGeofence(SekolahLokasiGeofence $sekolahLokasiGeofence)
    {
        $this->checkOwnershipGeofence($sekolahLokasiGeofence);
        $sekolahLokasiGeofence->delete(); //
        return redirect()->route('sekolah.admin.konfigurasi.index')
                         ->with('success', 'Lokasi Geofence berhasil dihapus.');
    }

    /**
     * Tampilkan halaman Kios Kode (Full Screen)
     */
    public function showKiosKode()
    {
        // View ini akan memanggil layout-nya sendiri via @extends
        return view('sekolah.admin.konfigurasi.kios-kode');
    }

    /**
     * [API] Ambil kode baru dan simpan di Cache
     */
    public function getNewKodeAbsen()
    {
        $pondokId = $this->getPondokId();
        
        // Buat 6 digit kode acak
        $kode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Simpan di Cache selama 70 detik (buffer 10 detik)
        // Kita gunakan cache driver 'database' Anda
        Cache::put('totp_pondok_' . $pondokId, $kode, 70);
        
        return response()->json(['kode' => $kode]);
    }
}