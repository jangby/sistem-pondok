<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah\SekolahIzinGuru;
use App\Models\Sekolah\AbsensiGuru;
use App\Notifications\GuruIzinResultNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class PersetujuanIzinController extends Controller
{
    // --- PERBAIKAN LOGIKA: Gunakan Pondok ID ---
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id; //
    }

    private function checkOwnership(SekolahIzinGuru $izin)
    {
        // Cek apakah izin ini berasal dari pondok yang sama dengan super admin
        if ($izin->sekolah->pondok_id != $this->getPondokId()) { //
            abort(404);
        }
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        $status = $request->input('status', 'pending');
        
        $izins = SekolahIzinGuru::where('status', $status)
            // Ambil semua izin yang sekolahnya ada di pondok si super admin
            ->whereHas('sekolah', fn($q) => $q->where('pondok_id', $pondokId)) //
            ->with('guru', 'sekolah') // Tampilkan juga nama sekolahnya
            ->latest()
            ->paginate(10)
            ->withQueryString();
            
        // Ganti view agar sesuai (meskipun file-nya masih di folder 'admin')
        return view('sekolah.superadmin.persetujuan-izin.index', compact('izins', 'status'));
    }

    public function approve(Request $request, SekolahIzinGuru $sekolahIzinGuru)
    {
        $this->checkOwnership($sekolahIzinGuru);
        $request->validate(['keterangan_admin' => 'nullable|string']);

        DB::transaction(function () use ($request, $sekolahIzinGuru) {
            $sekolahIzinGuru->update([
                'status' => 'approved',
                'peninjau_user_id' => Auth::id(),
                'ditinjau_pada' => now(),
                'keterangan_admin' => $request->keterangan_admin ?? 'Pengajuan Anda telah disetujui.',
            ]);

            // INTEGRASI OTOMATIS ke 'absensi_gurus'
            $tanggal = Carbon::parse($sekolahIzinGuru->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($sekolahIzinGuru->tanggal_selesai);
            
            while ($tanggal->lte($tanggalSelesai)) {
                AbsensiGuru::updateOrCreate( //
                    [
                        // Gunakan sekolah_id dari data izin (cth: MTS)
                        'sekolah_id' => $sekolahIzinGuru->sekolah_id, 
                        'guru_user_id' => $sekolahIzinGuru->guru_user_id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                    ],
                    [
                        'status' => $sekolahIzinGuru->tipe_izin,
                        'keterangan' => $sekolahIzinGuru->keterangan_guru,
                    ]
                );
                // TODO: Jika guru mengajar di BANYAK sekolah, kita harus
                // meng-update absensi_gurus untuk SEMUA sekolah tempat dia mengajar.
                // Untuk saat ini, kita update sekolah tempat dia mengajukan.
                
                $tanggal->addDay();
            }
        });

        // Kirim Notifikasi WA ke Guru
        try {
            $guruProfile = $sekolahIzinGuru->guru->guru; //
            
            if ($guruProfile) { // <-- PERBAIKAN: Tambahkan pengecekan ini
                $guruProfile->notify((new GuruIzinResultNotification($sekolahIzinGuru))->delay(now()->addSeconds(5))); //
            } else {
                Log::warning('Gagal kirim WA: Profil Guru tidak ditemukan for user_id: ' . $sekolahIzinGuru->guru_user_id);
            }

        } catch (\Exception $e) {
            Log::error('Gagal kirim WA Hasil Izin ke Guru: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, SekolahIzinGuru $sekolahIzinGuru)
    {
        $this->checkOwnership($sekolahIzinGuru);
        $request->validate(['keterangan_admin' => 'required|string|max:255']);

        $sekolahIzinGuru->update([
            'status' => 'rejected',
            'peninjau_user_id' => Auth::id(),
            'ditinjau_pada' => now(),
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        // Kirim Notifikasi WA ke Guru
        // Kirim Notifikasi WA ke Guru
        try {
            $guruProfile = $sekolahIzinGuru->guru->guru; //
            
            if ($guruProfile) { // <-- PERBAIKAN: Tambahkan pengecekan ini
                $guruProfile->notify((new GuruIzinResultNotification($sekolahIzinGuru))->delay(now()->addSeconds(5))); //
            } else {
                Log::warning('Gagal kirim WA: Profil Guru tidak ditemukan for user_id: ' . $sekolahIzinGuru->guru_user_id);
            }
            
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA Hasil Izin ke Guru: ' . $e->getMessage());
        }

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}