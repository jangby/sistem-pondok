<?php

namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah\AbsensiPelajaran;
use App\Models\Sekolah\JadwalPelajaran;
use App\Models\User;
use App\Notifications\GuruPenggantiAssignedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuruPenggantiController extends Controller
{
    // Helper
    private function getSekolah()
    {
        $adminUser = Auth::user();
        $sekolah = $adminUser->sekolahs()->first();
        if (!$sekolah) abort(403);
        return $sekolah;
    }
    
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Tampilkan daftar pelajaran kosong hari ini yang butuh pengganti
     */
    public function index()
    {
        $sekolah = $this->getSekolah();
        $today = now()->format('Y-m-d');
        $namaHari = now()->locale('id_ID')->isoFormat('dddd');

        // 1. Cari Jadwal Hari Ini
        $jadwals = JadwalPelajaran::where('sekolah_id', $sekolah->id)
            ->where('hari', $namaHari)
            // Filter jadwal yang gurunya TIDAK HADIR di absensi_gurus (Sakit/Izin/Alpa)
            // ATAU jadwal yang sudah tercatat di absensi_pelajarans tapi status_guru != hadir
            ->with(['guru', 'kelas', 'mataPelajaran'])
            ->get();

        // Filter manual: Mana yang butuh pengganti?
        $kelasKosong = [];
        
        foreach ($jadwals as $jadwal) {
            // Cek apakah sudah ada record absensi pelajaran
            $absensiPelajaran = AbsensiPelajaran::where('jadwal_pelajaran_id', $jadwal->id)
                                ->where('tanggal', $today)
                                ->first();

            $butuhPengganti = false;
            $statusGuruAsli = 'Belum Absen';

            // Skenario A: Sudah ada record absensi (misal otomatis dibuat karena guru Sakit/Izin)
            if ($absensiPelajaran) {
                // Jika statusnya bukan hadir (sakit/izin/alpa) DAN belum ada pengganti
                if (in_array($absensiPelajaran->status_guru, ['sakit', 'izin', 'alpa']) && !$absensiPelajaran->guru_pengganti_user_id) {
                    $butuhPengganti = true;
                    $statusGuruAsli = ucfirst($absensiPelajaran->status_guru);
                }
            } 
            // Skenario B: Belum ada record absensi pelajaran sama sekali
            else {
                // 1. Cek Absensi Harian Guru (Apakah dia Sakit/Izin hari ini?)
                $absensiHarianGuru = \App\Models\Sekolah\AbsensiGuru::where('guru_user_id', $jadwal->guru_user_id)
                    ->where('tanggal', $today)
                    ->whereIn('status', ['sakit', 'izin', 'alpa'])
                    ->first();
                
                if ($absensiHarianGuru) {
                    // Jika guru sakit/izin hari ini, pasti butuh pengganti
                    $butuhPengganti = true;
                    $statusGuruAsli = ucfirst($absensiHarianGuru->status);
                } 
                else {
                    // 2. (PERBAIKAN BARU) Cek Waktu: Apakah jam pelajaran SUDAH LEWAT?
                    // Jika sekarang jam 08:00, dan jadwal jam 07:00, tapi belum absen -> Munculkan!
                    $jamMulai = \Carbon\Carbon::parse($jadwal->jam_mulai);
                    $toleransi = 15; // menit
                    
                    if (now()->gt($jamMulai->addMinutes($toleransi))) {
                        $butuhPengganti = true;
                        $statusGuruAsli = 'Terlambat / Belum Hadir';
                    }
                }
            }

            if ($butuhPengganti) {
                $kelasKosong[] = [
                    'jadwal' => $jadwal,
                    'status_guru_asli' => $statusGuruAsli,
                    'absensi_pelajaran_id' => $absensiPelajaran?->id // Bisa null
                ];
            }
        }

        // Ambil daftar semua guru yang available (untuk dropdown)
        // Idealnya kita filter guru yang TIDAK mengajar di jam yang sama, tapi untuk sekarang semua guru dulu
        $availableGurus = User::role('guru')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $this->getPondokId()))
            // PERBAIKAN: Gunakan relasi 'sekolahs' yang benar
            ->whereHas('sekolahs', fn($q) => $q->where('sekolahs.id', $sekolah->id)) 
            ->orderBy('name')
            ->get();

        return view('sekolah.admin.guru-pengganti.index', compact('kelasKosong', 'availableGurus'));
    }

    /**
     * Simpan penugasan guru pengganti
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_pelajarans,id',
            'guru_pengganti_id' => 'required|exists:users,id',
            'absensi_pelajaran_id' => 'nullable|exists:absensi_pelajarans,id'
        ]);

        $jadwal = JadwalPelajaran::find($request->jadwal_id);
        
        // Validasi: Guru pengganti tidak boleh sama dengan guru asli
        if ($request->guru_pengganti_id == $jadwal->guru_user_id) {
            return back()->with('error', 'Guru pengganti tidak boleh sama dengan guru asli.');
        }

        DB::transaction(function () use ($request, $jadwal) {
            // Jika record absensi pelajaran belum ada, buat dulu
            // (Kasus di mana guru asli belum sempat absen atau sistem belum generate)
            $absensiPelajaran = AbsensiPelajaran::updateOrCreate(
                [
                    'id' => $request->absensi_pelajaran_id ?? null
                ],
                [
                    'jadwal_pelajaran_id' => $jadwal->id,
                    'tanggal' => now()->format('Y-m-d'),
                    // Kita set status guru asli jadi 'izin' (karena digantikan) jika belum diset
                    'status_guru' => 'izin', 
                    'guru_pengganti_user_id' => $request->guru_pengganti_id,
                    'is_substitute' => true
                ]
            );

            // Kirim Notifikasi ke Guru Pengganti
            try {
                $guruPengganti = User::find($request->guru_pengganti_id);
                // Pastikan model User punya relasi 'guru'
                if ($guruPengganti->guru) { 
                    $guruPengganti->guru->notify((new GuruPenggantiAssignedNotification($absensiPelajaran))->delay(now()->addSeconds(5)));
                }
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA Guru Pengganti: ' . $e->getMessage());
            }
        });

        return back()->with('success', 'Guru pengganti berhasil ditugaskan.');
    }
}