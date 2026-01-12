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
use App\Models\Sekolah\Sekolah;

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
        // 1. Validasi Input
        $timeFields = ['jam_masuk', 'batas_telat', 'jam_pulang_awal', 'jam_pulang_akhir'];
        foreach ($timeFields as $field) {
            if ($request->filled($field)) {
                $request->merge([$field => substr($request->input($field), 0, 5)]);
            }
        }

        $validated = $request->validate([
            'jam_masuk'        => 'required|date_format:H:i',
            'batas_telat'      => 'required|date_format:H:i|after:jam_masuk',
            'jam_pulang_awal'  => 'required|date_format:H:i',
            'jam_pulang_akhir' => 'required|date_format:H:i',
            'hari_kerja'       => 'required|array|min:1',
        ]);

        // 2. Ambil ID Pondok
        $pondokId = $this->getPondokId();

        // 3. Ambil SEMUA Sekolah di Pondok ini
        $allSekolah = Sekolah::where('pondok_id', $pondokId)->get();

        // 4. Loop & Simpan ke Semua Sekolah
        foreach ($allSekolah as $unitSekolah) {
            SekolahAbsensiSetting::updateOrCreate(
                ['sekolah_id' => $unitSekolah->id], // Cari berdasarkan ID Sekolah masing-masing
                $validated // Data jam & hari kerja yang sama
            );
        }

        return redirect()->route('sekolah.admin.konfigurasi.index')
            ->with('success', 'Pengaturan Absensi berhasil disimpan untuk SEMUA Unit Sekolah.');
    }

    /**
     * SIMPAN HARI LIBUR (BERLAKU UNTUK SEMUA UNIT)
     */
    public function storeHariLibur(Request $request)
    {
        $currentSekolah = $this->getSekolah();

        $validated = $request->validate([
            'tanggal' => [
                'required', 'date',
                // Validasi unique hanya dicek di sekolah yang sedang aktif agar tidak error form
                Rule::unique('sekolah_hari_libur')->where(fn ($q) => $q->where('sekolah_id', $currentSekolah->id))
            ],
            'keterangan' => 'required|string|max:255',
        ], [
            'tanggal.unique' => 'Tanggal libur ini sudah ada.'
        ]);

        // 1. Ambil ID Pondok
        $pondokId = $this->getPondokId();

        // 2. Ambil SEMUA Sekolah
        $allSekolah = Sekolah::where('pondok_id', $pondokId)->get();

        // 3. Loop & Create Hari Libur untuk Semua Sekolah
        foreach ($allSekolah as $unitSekolah) {
            // Cek dulu biar tidak duplikat (firstOrCreate)
            SekolahHariLibur::firstOrCreate(
                [
                    'sekolah_id' => $unitSekolah->id,
                    'tanggal'    => $validated['tanggal']
                ],
                [
                    'keterangan' => $validated['keterangan']
                ]
            );
        }

        return redirect()->route('sekolah.admin.konfigurasi.index')
            ->with('success', 'Hari Libur berhasil ditambahkan ke SEMUA Unit Sekolah.');
    }

    /**
     * HAPUS HARI LIBUR (HAPUS DI SEMUA UNIT JUGA)
     */
    public function destroyHariLibur(SekolahHariLibur $sekolahHariLibur)
    {
        $this->checkOwnershipHariLibur($sekolahHariLibur);
        
        $tanggal = $sekolahHariLibur->tanggal;
        $pondokId = $this->getPondokId();

        // Cari semua sekolah di pondok
        $sekolahIds = Sekolah::where('pondok_id', $pondokId)->pluck('id');

        // Hapus hari libur dengan tanggal yang sama di semua sekolah milik pondok ini
        SekolahHariLibur::whereIn('sekolah_id', $sekolahIds)
            ->where('tanggal', $tanggal)
            ->delete();

        return redirect()->route('sekolah.admin.konfigurasi.index')
            ->with('success', 'Hari Libur berhasil dihapus dari semua unit.');
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