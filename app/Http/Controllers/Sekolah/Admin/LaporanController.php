<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\AbsensiGuru;
use App\Models\Sekolah\AbsensiPelajaran;
use App\Models\Sekolah\AbsensiSiswaSekolah;
use App\Models\Sekolah\AbsensiSiswaPelajaran;
use App\Models\Sekolah\TahunAjaran;
use App\Models\Kelas;
use App\Models\Sekolah\MataPelajaran;
use App\Models\User;
use App\Models\Santri;
use PDF; // Alias untuk Barryvdh\DomPDF\Facade\Pdf

class LaporanController extends Controller
{
    private function getSekolah()
    {
        $adminUser = Auth::user();
        $sekolah = $adminUser->sekolahs()->first();
        if (!$sekolah) abort(403);
        return $sekolah;
    }
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    /**
     * Halaman Utama Laporan (Pilih Jenis Laporan)
     */
    public function index()
    {
        $sekolah = $this->getSekolah();
        $pondokId = $this->getPondokId();

        $kelasList = Kelas::where('pondok_id', $pondokId)->orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::where('sekolah_id', $sekolah->id)->orderBy('nama_mapel')->get();
        
        return view('sekolah.admin.laporan.index', compact('kelasList', 'mapelList'));
    }

    /**
     * Proses Cetak PDF
     */
    public function cetak(Request $request)
    {
        $sekolah = $this->getSekolah();
        $pondokId = $this->getPondokId();
        
        $request->validate([
            'jenis_laporan' => 'required|in:guru_sekolah,guru_pelajaran,siswa_sekolah,siswa_pelajaran',
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
            // Validasi kondisional
            'kelas_id' => 'required_if:jenis_laporan,siswa_pelajaran',
            'mapel_id' => 'nullable', // Opsional
        ]);

        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;
        $namaBulan = \Carbon\Carbon::create()->month($bulan)->format('F');
        
        $data = [];
        $view = '';
        $judul = '';

        // --- LOGIKA DATA ---

        if ($request->jenis_laporan == 'guru_sekolah') {
            // 1. Ledger Guru (Sekolah)
            $judul = "Ledger Kehadiran Guru (Sekolah) - $namaBulan $tahun";
            $view = 'sekolah.admin.laporan.pdf.guru-sekolah';
            
            $gurus = $sekolah->users()->role('guru')->orderBy('name')->get();
            foreach ($gurus as $guru) {
                $logs = AbsensiGuru::where('sekolah_id', $sekolah->id)
                    ->where('guru_user_id', $guru->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get()
                    ->keyBy('tanggal'); // Key tanggal (cth: 2024-11-01)
                
                $data[] = ['guru' => $guru, 'logs' => $logs];
            }

        } elseif ($request->jenis_laporan == 'guru_pelajaran') {
            // 2. Ledger Guru (Pelajaran)
            $judul = "Ledger Mengajar Guru - $namaBulan $tahun";
            $view = 'sekolah.admin.laporan.pdf.guru-pelajaran';
            
            // Ambil rekap: Guru A mengajar berapa jam, hadir berapa, telat berapa
            $gurus = $sekolah->users()->role('guru')->orderBy('name')->get();
            foreach ($gurus as $guru) {
                $logs = AbsensiPelajaran::whereHas('jadwalPelajaran', fn($q) => $q->where('guru_user_id', $guru->id))
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->with(['jadwalPelajaran.mataPelajaran', 'jadwalPelajaran.kelas'])
                    ->get();
                
                $data[] = ['guru' => $guru, 'logs' => $logs];
            }

        } elseif ($request->jenis_laporan == 'siswa_sekolah') {
            // 3. Ledger Siswa (Sekolah/Gerbang)
            $judul = "Ledger Kehadiran Siswa (Sekolah) - $namaBulan $tahun";
            $view = 'sekolah.admin.laporan.pdf.siswa-sekolah';
            
            // Filter per kelas jika dipilih (opsional di form, tapi sebaiknya ada)
            $kelasId = $request->kelas_id_sekolah; 
            $santris = Santri::where('pondok_id', $pondokId)->where('status', 'active');
            
            if($kelasId) {
                $santris->where('kelas_id', $kelasId);
                $namaKelas = Kelas::find($kelasId)->nama_kelas;
                $judul .= " - Kelas $namaKelas";
            } else {
                 // Jika tidak pilih kelas, ambil semua santri di tingkat sekolah ini
                 $santris->whereHas('kelas', fn($q) => $q->where('tingkat', $sekolah->tingkat));
            }
            $santris = $santris->orderBy('full_name')->get();

            foreach ($santris as $santri) {
                $logs = AbsensiSiswaSekolah::where('sekolah_id', $sekolah->id)
                    ->where('santri_id', $santri->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get()
                    ->keyBy('tanggal');
                
                $data[] = ['santri' => $santri, 'logs' => $logs];
            }

        } elseif ($request->jenis_laporan == 'siswa_pelajaran') {
            // 4. Ledger Siswa (Pelajaran)
            $kelas = Kelas::find($request->kelas_id);
            $mapel = MataPelajaran::find($request->mapel_id);
            
            $judul = "Ledger Absensi Pelajaran - Kelas {$kelas->nama_kelas}";
            if ($mapel) $judul .= " - Mapel: {$mapel->nama_mapel}";
            $judul .= " ($namaBulan $tahun)";
            
            $view = 'sekolah.admin.laporan.pdf.siswa-pelajaran';

            $santris = $kelas->santris()->orderBy('full_name')->get();
            
            // Ambil ID jadwal pelajaran yang sesuai filter
            $absensiPelajaranIds = AbsensiPelajaran::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->whereHas('jadwalPelajaran', function($q) use ($sekolah, $kelas, $mapel) {
                    $q->where('sekolah_id', $sekolah->id)
                      ->where('kelas_id', $kelas->id);
                    if ($mapel) $q->where('mata_pelajaran_id', $mapel->id);
                })
                ->pluck('id');

            // Siapkan header tanggal pertemuan (untuk kolom tabel)
            $pertemuan = AbsensiPelajaran::whereIn('id', $absensiPelajaranIds)
                            ->orderBy('tanggal')
                            ->get(); // Collection pertemuan

            foreach ($santris as $santri) {
                // Ambil status absensi santri untuk setiap pertemuan
                $logs = AbsensiSiswaPelajaran::whereIn('absensi_pelajaran_id', $absensiPelajaranIds)
                            ->where('santri_id', $santri->id)
                            ->get()
                            ->keyBy('absensi_pelajaran_id'); // Key berdasarkan ID pertemuan
                
                $data[] = ['santri' => $santri, 'logs' => $logs];
            }
            
            // Kirim data pertemuan juga untuk header tabel
            $pdf = PDF::loadView($view, compact('data', 'judul', 'sekolah', 'bulan', 'tahun', 'pertemuan'))
                      ->setPaper('a4', 'landscape'); // Landscape agar muat banyak kolom
            return $pdf->stream('Ledger_Absensi.pdf');
        }

        // Cetak PDF (Default untuk 1, 2, 3)
        $pdf = PDF::loadView($view, compact('data', 'judul', 'sekolah', 'bulan', 'tahun'))
                  ->setPaper('a4', 'landscape');
        return $pdf->stream('Ledger_Absensi.pdf');
    }
}