<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\Santri;
use App\Models\Absensi;
use App\Models\AbsensiDiniyah;
use App\Models\JurnalPendidikan;
use App\Models\KesehatanSantri;
use App\Models\Perizinan;
use App\Models\Gate\GateLog;

class MonitoringController extends Controller
{
    /**
     * Cek Validasi Akses Orang Tua terhadap Santri
     */
    private function checkAccess($santri)
    {
        if ($santri->orang_tua_id != Auth::user()->orangTua->id) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin memantau santri ini.');
        }
    }

    /**
     * Halaman Utama Monitoring (Dashboard Pantau Anak)
     */
    public function index($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);

        $hariIni = Carbon::today();

        // 1. Cek Logika Status Prioritas (Urutan menentukan status yang tampil)
        
        // Default Status (Jika tidak ada data apapun hari ini)
        $status = [
            'text' => 'Di Asrama',
            'class' => 'bg-gray-500',
            'icon' => 'home',
            'desc' => 'Belum ada aktivitas scan hari ini'
        ];

        // Cek 1: Apakah sedang keluar gerbang? (Belum kembali)
        $isOut = GateLog::where('santri_id', $id)->whereNull('in_time')->exists();
        
        // Cek 2: Apakah sedang Izin Resmi?
        $isIzin = Perizinan::where('santri_id', $id)
            ->where('status', 'disetujui')
            ->whereDate('tgl_mulai', '<=', $hariIni)
            ->whereDate('tgl_selesai_rencana', '>=', $hariIni)
            ->exists();

        // Cek 3: Apakah sedang Sakit (Masuk UKS dan belum sembuh)?
        $isSakit = KesehatanSantri::where('santri_id', $id)
            ->where('status', '!=', 'sembuh')
            ->exists();

        // Cek 4: Apakah SUDAH Absen Hari Ini? (Hadir Kegiatan)
        $todayAbsen = Absensi::where('santri_id', $id)
            ->whereDate('waktu_catat', $hariIni)
            ->latest('waktu_catat')
            ->first();

        // --- PENETAPAN STATUS FINAL ---
        if ($isOut) {
            $status = [
                'text' => 'Sedang Diluar',
                'class' => 'bg-blue-600',
                'icon' => 'logout',
                'desc' => 'Tercatat keluar gerbang'
            ];
        } elseif ($isIzin) {
            $status = [
                'text' => 'Sedang Izin',
                'class' => 'bg-purple-600',
                'icon' => 'document-text',
                'desc' => 'Dalam masa perizinan resmi'
            ];
        } elseif ($isSakit) {
            $status = [
                'text' => 'Sedang Sakit',
                'class' => 'bg-red-600',
                'icon' => 'heart',
                'desc' => 'Dalam perawatan / istirahat'
            ];
        } elseif ($todayAbsen) {
            // Jika ada data absen HARI INI
            $status = [
                'text' => 'Hadir Kegiatan',
                'class' => 'bg-emerald-600',
                'icon' => 'check',
                'desc' => 'Terakhir: ' . ($todayAbsen->nama_kegiatan ?? 'Absensi Rutin')
            ];
        }

        // 2. Ambil Aktivitas Terakhir (History Log)
        // Ambil data terakhir HARI INI saja untuk ditampilkan di widget "Aktivitas Terakhir"
        // Jika ingin menampilkan data kemarin sebagai "Terakhir kali terlihat", hapus whereDate
        $lastActivity = Absensi::where('santri_id', $id)
            ->whereDate('waktu_catat', $hariIni) // HANYA HARI INI
            ->latest('waktu_catat')
            ->first();

        return view('orangtua.monitoring.index', compact('santri', 'status', 'lastActivity'));
    }

    /**
     * Halaman Riwayat Kesehatan
     */
    public function kesehatan($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);
        
        $history = KesehatanSantri::where('santri_id', $id)
            ->latest()
            ->paginate(10);
            
        return view('orangtua.monitoring.kesehatan', compact('santri', 'history'));
    }

    /**
     * Halaman Riwayat Izin
     */
    public function izin($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);
        
        $history = Perizinan::where('santri_id', $id)
            ->latest()
            ->paginate(10);
            
        return view('orangtua.monitoring.izin', compact('santri', 'history'));
    }

    /**
     * Halaman Riwayat Keluar Masuk Gerbang
     */
    public function gerbang($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);
        
        $history = GateLog::where('santri_id', $id)
            ->latest('out_time')
            ->paginate(10);
            
        return view('orangtua.monitoring.gerbang', compact('santri', 'history'));
    }

    /**
     * Halaman Riwayat Absensi Harian (Sholat, Kegiatan Pondok)
     */
    public function absensi(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);
        
        $kategori = $request->get('kategori', 'semua');
        
        $query = Absensi::where('santri_id', $id)->latest('waktu_catat');
        
        if ($kategori == 'jamaah') {
            $query->where('kategori', 'like', 'jamaah_%');
        } elseif ($kategori == 'asrama') {
            $query->where('kategori', 'asrama');
        } elseif ($kategori == 'kegiatan') {
            $query->whereIn('kategori', ['kegiatan', 'kegiatan_khusus']);
        }

        $history = $query->paginate(15)->withQueryString();

        return view('orangtua.monitoring.absensi', compact('santri', 'history', 'kategori'));
    }

    /**
     * Halaman Absensi Diniyah / Mengaji (Fitur Baru)
     */
    public function diniyah($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);

        // Ambil riwayat absensi diniyah (sekolah arab/kitab)
        $history = AbsensiDiniyah::where('santri_id', $id)
            ->with(['jadwal', 'jadwal.mapel', 'jadwal.ustadz'])
            ->latest('tanggal')
            ->paginate(15);

        return view('orangtua.monitoring.diniyah', compact('santri', 'history'));
    }

    /**
     * Halaman Progres Hafalan (Fitur Baru)
     */
    public function hafalan($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);

        // Ambil riwayat setoran hafalan
        $history = JurnalPendidikan::where('santri_id', $id)
            ->where('jenis', 'hafalan')
            ->with('ustadz')
            ->latest('tanggal')
            ->latest('created_at')
            ->paginate(10);

        // Statistik sederhana
        $totalSetoran = JurnalPendidikan::where('santri_id', $id)
            ->where('jenis', 'hafalan')
            ->count();
        
        return view('orangtua.monitoring.hafalan', compact('santri', 'history', 'totalSetoran'));
    }
}