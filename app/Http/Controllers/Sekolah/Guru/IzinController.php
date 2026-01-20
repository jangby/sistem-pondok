<?php
namespace App\Http\Controllers\Sekolah\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah\SekolahIzinGuru;
use App\Models\User;
use App\Notifications\GuruIzinRequestNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    private function getGuruData()
    {
        $guruUser = Auth::user();
        $sekolah = $guruUser->sekolahs()->first();
        if (!$sekolah) abort(403, 'Akun Anda tidak ditugaskan.');
        return compact('guruUser', 'sekolah');
    }

    // Tampilkan daftar izin yang pernah diajukan
    public function index()
    {
        $data = $this->getGuruData();
        $izins = SekolahIzinGuru::where('guru_user_id', $data['guruUser']->id)
            ->with('peninjau')
            ->latest()
            ->paginate(10);
            
        return view('sekolah.guru.izin.index', compact('izins'));
    }

    // Tampilkan form pengajuan
    public function create()
    {
        return view('sekolah.guru.izin.create');
    }

    public function store(Request $request)
{
    // 1. Validasi (Gabungan aturan Anda + Aturan Gambar)
    $validated = $request->validate([
        'tipe_izin' => 'required|in:sakit,izin',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'keterangan_guru' => 'required|string|max:1000',
        // Tambahan validasi gambar (Opsional, Max 5MB)
        'bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', 
    ]);
    
    $data = $this->getGuruData();
    $pondokId = Auth::user()->pondokStaff->pondok_id;
    
    // 2. Siapkan Data Dasar
    $validated['sekolah_id'] = $data['sekolah']->id;
    $validated['guru_user_id'] = $data['guruUser']->id;
    $validated['status'] = 'pending';

    // 3. LOGIKA BARU: Upload & Kompres Gambar
    // (Disisipkan sebelum Create)
    if ($request->hasFile('bukti')) {
        $file = $request->file('bukti');
        $filename = time() . '_' . uniqid() . '.jpg';
        $path = 'uploads/izin-guru/' . $filename;

        // Panggil fungsi kompresi (lihat fungsi di bawah)
        $this->compressAndSaveImage($file, $path);

        // Simpan path ke array validated (pastikan kolom di DB namanya 'bukti_url')
        $validated['bukti_url'] = $path; 
    }
    
    // Hapus key 'bukti' dari array agar tidak error saat create (karena tidak ada kolom 'bukti' di DB)
    unset($validated['bukti']); 

    // 4. Simpan ke Database
    $izin = SekolahIzinGuru::create($validated);

    // 5. Logika Notifikasi (TETAP SAMA SEPERTI KODE ANDA)
    try {
        $superAdmin = User::role('super-admin-sekolah')
                        ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
                        ->first();
        
        if ($superAdmin && $superAdmin->telepon) {
            $superAdmin->notify((new GuruIzinRequestNotification($izin))->delay(now()->addSeconds(5)));
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Gagal Kirim WA Izin: ' . $e->getMessage());
    }

    return redirect()->route('sekolah.guru.izin.index')
                     ->with('success', 'Pengajuan ' . $validated['tipe_izin'] . ' berhasil dikirim.');
}

// --- JANGAN LUPA COPY FUNCTION INI KE DALAM CLASS CONTROLLER ---
private function compressAndSaveImage($file, $path)
{
    $info = getimagesize($file);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg': $image = imagecreatefromjpeg($file); break;
        case 'image/png': 
            $image = imagecreatefrompng($file);
            $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagealphablending($bg, true);
            imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            $image = $bg;
            break;
        default: 
            Storage::disk('public')->put($path, file_get_contents($file));
            return;
    }

    if (imagesx($image) > 1000) {
        $image = imagescale($image, 1000);
    }

    ob_start();
    imagejpeg($image, null, 60); 
    $imageData = ob_get_clean();

    Storage::disk('public')->put($path, $imageData);
    imagedestroy($image);
}
}