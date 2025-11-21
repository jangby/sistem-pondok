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
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    private function checkOwnership(SekolahIzinGuru $izin)
    {
        if ($izin->sekolah->pondok_id != $this->getPondokId()) {
            abort(404);
        }
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // Filter Status (Default: pending)
        $status = $request->input('status', 'pending');
        
        // Query Dasar
        $query = SekolahIzinGuru::query()
            ->whereHas('sekolah', fn($q) => $q->where('pondok_id', $pondokId))
            ->with(['guru', 'sekolah']); // Eager Load

        // Filter Status
        $query->where('status', $status);

        // Fitur Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('guru', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('sekolah', function($q2) use ($search) {
                    $q2->where('nama_sekolah', 'like', "%{$search}%");
                })
                ->orWhere('tipe_izin', 'like', "%{$search}%");
            });
        }

        $izins = $query->latest()->paginate(10)->withQueryString();
            
        // Hitung Badge Counter (Optional, untuk Tab)
        $countPending = SekolahIzinGuru::whereHas('sekolah', fn($q) => $q->where('pondok_id', $pondokId))->where('status', 'pending')->count();
        
        return view('sekolah.superadmin.persetujuan-izin.index', compact('izins', 'status', 'countPending'));
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
                'keterangan_admin' => $request->keterangan_admin ?? 'Pengajuan disetujui.',
            ]);

            // Generate Absensi
            $tanggal = Carbon::parse($sekolahIzinGuru->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($sekolahIzinGuru->tanggal_selesai);
            
            while ($tanggal->lte($tanggalSelesai)) {
                AbsensiGuru::updateOrCreate(
                    [
                        'sekolah_id' => $sekolahIzinGuru->sekolah_id, 
                        'guru_user_id' => $sekolahIzinGuru->guru_user_id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                    ],
                    [
                        'status' => $sekolahIzinGuru->tipe_izin,
                        'keterangan' => $sekolahIzinGuru->keterangan_guru,
                        'jam_masuk' => null, // Reset jam jika sebelumnya ada
                        'jam_pulang' => null,
                    ]
                );
                $tanggal->addDay();
            }
        });

        // Notifikasi (Skip error handling detail agar controller bersih)
        try {
            if ($sekolahIzinGuru->guru->guru) {
                $sekolahIzinGuru->guru->guru->notify((new GuruIzinResultNotification($sekolahIzinGuru))->delay(now()->addSeconds(5)));
            }
        } catch (\Exception $e) {
            Log::error('WA Error: ' . $e->getMessage());
        }
        
        return redirect()->route('sekolah.superadmin.persetujuan-izin.index', ['status' => 'pending'])
                         ->with('success', 'Izin berhasil disetujui.');
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

        try {
            if ($sekolahIzinGuru->guru->guru) {
                $sekolahIzinGuru->guru->guru->notify((new GuruIzinResultNotification($sekolahIzinGuru))->delay(now()->addSeconds(5)));
            }
        } catch (\Exception $e) {
            Log::error('WA Error: ' . $e->getMessage());
        }

        return redirect()->route('sekolah.superadmin.persetujuan-izin.index', ['status' => 'pending'])
                         ->with('success', 'Pengajuan ditolak.');
    }
}