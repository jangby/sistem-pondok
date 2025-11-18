<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PondokSetting; // Pastikan Model ini sudah Anda buat
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log
// Import library Image
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SettingController extends Controller
{
    private function getPondokId() {
        return Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Tampilkan halaman pengaturan.
     */
    public function index()
    {
        // Ambil data setting, atau buat baru jika belum ada
        $setting = PondokSetting::firstOrCreate(
            ['pondok_id' => $this->getPondokId()]
        );
        return view('adminpondok.settings.index', compact('setting'));
    }

    /**
     * Simpan pengaturan.
     */
    public function store(Request $request)
    {
        $setting = PondokSetting::firstOrCreate(
            ['pondok_id' => $this->getPondokId()]
        );
        
        $validated = $request->validate([
            'nama_resmi' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:1024' // Maks 1MB
        ]);
        
        // Logika Upload Logo (SAMA SEPERTI BUKTI TRANSFER)
        if ($request->hasFile('logo')) {
            try {
                // 1. Hapus logo lama jika ada
                if ($setting->logo_url && file_exists(public_path($setting->logo_url))) {
                    unlink(public_path($setting->logo_url));
                }

                $file = $request->file('logo');
                
                // 2. Buat nama file unik
                $filename = 'logo-' . $this->getPondokId() . '-' . time() . '.jpg'; // Paksa .jpg
                
                // 3. Tentukan folder tujuan (di DALAM folder 'public')
                $publicPath = 'uploads/logos/';
                $destinationPath = public_path($publicPath); // C:\laragon\www\proyek\public\uploads\logos

                // 4. Buat folder jika belum ada
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // 5. Load gambar, kompres (resize ke lebar 300px), dan simpan
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->resize(width: 300); // Logo kita buat lebih kecil
                $image->toJpg(85)->save($destinationPath . $filename); // Simpan

                // 6. Path untuk database adalah path relatif di dalam 'public'
                $validated['logo_url'] = $publicPath . $filename; 

            } catch (\Exception $e) {
                Log::error('Gagal upload logo: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal mengupload logo: ' . $e->getMessage());
            }
        }
        
        // Update data (termasuk 'logo_url' jika ada)
        $setting->update($validated);
        
        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}