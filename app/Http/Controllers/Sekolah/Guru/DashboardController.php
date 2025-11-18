<?php
namespace App\Http\Controllers\Sekolah\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\TahunAjaran;
use App\Models\Sekolah\JadwalPelajaran;
use App\Models\Sekolah\AbsensiGuru;
use App\Models\Sekolah\SekolahAbsensiSetting;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    // Helper untuk mengambil data utama
    private function getGuruData()
    {
        $guruUser = Auth::user(); //
        $pondokId = $guruUser->pondokStaff->pondok_id; //
        
        // Ambil tahun ajaran yang aktif
        $tahunAjaranAktif = TahunAjaran::where('pondok_id', $pondokId)
                            ->where('is_active', true)
                            ->first(); //
        
        return compact('guruUser', 'pondokId', 'tahunAjaranAktif');
    }

    public function index()
    {
        $data = $this->getGuruData();
        $jadwalHariIni = collect();
        
        // Konversi nama hari ke format Indonesia ('id_ID')
        $namaHariIni = Carbon::now()->locale('id_ID')->isoFormat('dddd'); // Cth: "Senin"

        if ($data['tahunAjaranAktif']) {
            // 1. Ambil ID jadwal di mana guru ini adalah GURU PENGGANTI hari ini
            $idJadwalPengganti = \App\Models\Sekolah\AbsensiPelajaran::where('guru_pengganti_user_id', $data['guruUser']->id) //
                ->whereDate('tanggal', Carbon::today())
                ->pluck('jadwal_pelajaran_id')
                ->toArray();

            // 2. Query Utama
            $jadwalHariIni = JadwalPelajaran::query() //
                ->where('tahun_ajaran_id', $data['tahunAjaranAktif']->id)
                ->where(function($q) use ($data, $namaHariIni, $idJadwalPengganti) {
                    // KONDISI A: Jadwal Asli (Sesuai Hari)
                    $q->where(function($subQ) use ($data, $namaHariIni) {
                        $subQ->where('guru_user_id', $data['guruUser']->id)
                             ->where('hari', $namaHariIni);
                    })
                    // KONDISI B: Jadwal Pengganti (Hari ini)
                    ->orWhereIn('id', $idJadwalPengganti);
                })
                ->with(['kelas', 'mataPelajaran'])
                ->orderBy('jam_mulai')
                ->get();
        }

        // Ambil data absensi kehadiran hari ini
        $absensiHariIni = AbsensiGuru::where('guru_user_id', $data['guruUser']->id) //
                            ->whereDate('tanggal', today())
                            ->first();
        
        // Ambil pengaturan jam (kita ambil dari sekolah pertama yg dia pegang)
        $sekolah = $data['guruUser']->sekolahs()->first(); //
        $settings = null;
        if ($sekolah) {
            $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first(); //
        }
        
        return view('sekolah.guru.dashboard', compact(
            'jadwalHariIni', 
            'absensiHariIni',
            'settings'
        ));
    }
}