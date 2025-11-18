<?php
namespace App\Http\Controllers\Sekolah\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah\SekolahIzinGuru;
use App\Models\User;
use App\Notifications\GuruIzinRequestNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    // Simpan pengajuan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe_izin' => 'required|in:sakit,izin',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan_guru' => 'required|string|max:1000',
        ]);
        
        $data = $this->getGuruData();
        $pondokId = Auth::user()->pondokStaff->pondok_id; //
        
        $validated['sekolah_id'] = $data['sekolah']->id;
        $validated['guru_user_id'] = $data['guruUser']->id;
        $validated['status'] = 'pending';
        
        $izin = SekolahIzinGuru::create($validated);

        // KIRIM NOTIFIKASI KE SUPER ADMIN SEKOLAH (PERBAIKAN)
        try {
            // Cari super admin sekolah di pondok ini
            $superAdmin = User::role('super-admin-sekolah') //
                            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId)) //
                            ->first();
            
            if ($superAdmin && $superAdmin->telepon) { //
                $superAdmin->notify((new GuruIzinRequestNotification($izin))->delay(now()->addSeconds(5)));
            }
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA Notif Izin ke Super Admin: ' . $e->getMessage());
        }

        return redirect()->route('sekolah.guru.izin.index')
                         ->with('success', 'Pengajuan ' . $validated['tipe_izin'] . ' berhasil dikirim.');
    }
}