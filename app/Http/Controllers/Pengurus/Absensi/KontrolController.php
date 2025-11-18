<?php

namespace App\Http\Controllers\Pengurus\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Kelas;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;

class KontrolController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    /**
     * Helper: Pusat Logika Pengambilan Data (Agar Web & PDF sama persis)
     */
    private function getData(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // 1. AMBIL PARAMETER
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $kelasId = $request->get('kelas_id');
        $gender = $request->get('gender'); // Filter Gender
        $search = $request->get('search');
        $kategoriFilter = $request->get('kategori');

        if ($endDate < $startDate) $endDate = $startDate;
        $isRentang = $startDate !== $endDate;

        // 2. VALIDASI
        $error = null;
        if ($isRentang && empty($kategoriFilter)) {
            $error = "Untuk rentang tanggal > 1 hari, wajib pilih kategori.";
            $endDate = $startDate; 
            $isRentang = false;
        }

        // 3. KATEGORI & KEGIATAN
        $listKategori = [
            'asrama_pagi' => 'Asrama Pagi',
            'jamaah_subuh' => 'Sholat Subuh',
            'jamaah_dzuhur' => 'Sholat Dzuhur',
            'jamaah_ashar' => 'Sholat Ashar',
            'jamaah_maghrib' => 'Sholat Maghrib',
            'jamaah_isya' => 'Sholat Isya',
            'asrama_malam' => 'Asrama Malam',
        ];
        $kegiatanDb = Kegiatan::where('pondok_id', $pondokId)->get();
        foreach($kegiatanDb as $k) {
            $listKategori['kegiatan_' . $k->id] = $k->nama_kegiatan;
        }

        // 4. QUERY SANTRI (Filter Lengkap)
        $querySantri = Santri::where('pondok_id', $pondokId)->where('status', 'active');
        if ($kelasId) $querySantri->where('kelas_id', $kelasId);
        if ($gender) $querySantri->where('jenis_kelamin', $gender); // Filter Gender diterapkan di sini
        if ($search) $querySantri->where('full_name', 'like', "%$search%");

        $santris = $querySantri->with(['riwayatKesehatan' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])->where('status', '!=', 'sembuh');
        }, 'perizinans' => function($q) use ($startDate, $endDate) {
            $q->where('status', 'disetujui')->whereDate('tgl_mulai', '<=', $endDate)->whereDate('tgl_selesai_rencana', '>=', $startDate);
        }, 'absensis' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal', [$startDate, $endDate]);
        }, 'kelas'])->orderBy('kelas_id')->orderBy('full_name')->get();

        // 5. PROCESSING DATA
        $headerKolom = [];
        $ledger = [];
        $pelanggaran = [];

        // --- LOGIKA HEADER ---
        if ($isRentang) {
            $periode = CarbonPeriod::create($startDate, $endDate);
            foreach ($periode as $date) $headerKolom[$date->format('Y-m-d')] = $date->format('d M');
        } else {
            $targetDate = Carbon::parse($startDate);
            $isToday = $targetDate->isToday();
            $isPast = $targetDate->isPast() && !$isToday;
            $jamSekarang = now()->format('H:i:s');

            if ($kategoriFilter) {
                $headerKolom[$kategoriFilter] = $listKategori[$kategoriFilter];
            } else {
                $hariIndo = $targetDate->locale('id')->isoFormat('dddd');
                $tglAngka = $targetDate->day;
                
                $jadwalRutin = [
                    'asrama_pagi' => ['label' => 'Pagi', 'cutoff' => '07:00:00'],
                    'jamaah_subuh' => ['label' => 'Subuh', 'cutoff' => '06:00:00'],
                    'jamaah_dzuhur' => ['label' => 'Dzuhur', 'cutoff' => '13:30:00'],
                    'jamaah_ashar' => ['label' => 'Ashar', 'cutoff' => '16:30:00'],
                    'jamaah_maghrib' => ['label' => 'Maghrib', 'cutoff' => '19:00:00'],
                    'jamaah_isya' => ['label' => 'Isya', 'cutoff' => '20:30:00'],
                    'asrama_malam' => ['label' => 'Malam', 'cutoff' => '22:00:00'],
                ];

                foreach ($jadwalRutin as $key => $rule) {
                    if ($isPast || ($isToday && $jamSekarang >= $rule['cutoff'])) {
                        $headerKolom[$key] = $rule['label'];
                    }
                }
                
                foreach ($kegiatanDb as $k) {
                    $cocok = false;
                    if ($k->frekuensi == 'harian') $cocok = true;
                    elseif ($k->frekuensi == 'mingguan' && in_array($hariIndo, $k->detail_waktu ?? [])) $cocok = true;
                    elseif ($k->frekuensi == 'bulanan' && in_array((string)$tglAngka, $k->detail_waktu ?? [])) $cocok = true;
                    
                    if ($cocok && ($isPast || ($isToday && $jamSekarang >= $k->jam_selesai))) {
                        $headerKolom['kegiatan_'.$k->id] = $k->nama_kegiatan;
                    }
                }
            }
            
            // Siapkan array pelanggaran sesuai header aktif
            foreach ($headerKolom as $key => $label) {
                $pelanggaran[$key] = ['label' => $label, 'santri' => []];
            }
        }

        // --- ISI DATA LEDGER ---
        foreach ($santris as $s) {
            $row = ['nama' => $s->full_name, 'kelas' => $s->kelas->nama_kelas ?? '-', 'gender' => $s->jenis_kelamin, 'data' => []];
            
            if ($isRentang) {
                $periode = CarbonPeriod::create($startDate, $endDate);
                foreach ($periode as $date) {
                    $tglStr = $date->format('Y-m-d');
                    $status = $this->cekStatusHarian($s, $tglStr, $kategoriFilter, $kegiatanDb);
                    $row['data'][$tglStr] = $status;
                }
            } else {
                foreach ($headerKolom as $key => $label) {
                    $status = $this->cekStatusHarian($s, $startDate, $key, $kegiatanDb);
                    $row['data'][$key] = $status;
                    
                    // Catat Pelanggaran (Hanya mode harian)
                    if ($status['status'] == 'A') {
                        $pelanggaran[$key]['santri'][] = $s;
                    }
                }
            }
            $ledger[] = $row;
        }

        return compact('headerKolom', 'ledger', 'pelanggaran', 'startDate', 'endDate', 'isRentang', 'kelasId', 'gender', 'search', 'listKategori', 'kategoriFilter', 'error');
    }

    // --- HALAMAN INDEX (VIEW WEB) ---
    public function index(Request $request)
    {
        $data = $this->getData($request);
        $data['daftarKelas'] = Kelas::where('pondok_id', $this->getPondokId())->orderBy('nama_kelas')->get();
        $data['tanggal'] = $data['startDate'];

        return view('pengurus.absensi.kontrol.index', $data);
    }

    // --- HALAMAN CETAK PDF (BARU) ---
    public function downloadPDF(Request $request)
    {
        $data = $this->getData($request);
        
        // Judul Laporan
        if ($data['isRentang']) {
            $judul = 'Rekap ' . ($data['listKategori'][$data['kategoriFilter']] ?? 'Absensi') . 
                     ' (' . Carbon::parse($data['startDate'])->format('d M') . ' - ' . Carbon::parse($data['endDate'])->format('d M Y') . ')';
        } else {
            $judul = 'Laporan Harian Absensi (' . Carbon::parse($data['startDate'])->format('d M Y') . ')';
        }

        $data['judulLaporan'] = $judul;

        $pdf = Pdf::loadView('pengurus.absensi.kontrol.pdf', $data);
        $pdf->setPaper('a4', 'landscape'); // Landscape agar muat banyak kolom
        
        return $pdf->stream('Laporan-Absensi.pdf');
    }

    // Helper cekStatusHarian (Sama seperti sebelumnya)
    private function cekStatusHarian($s, $tgl, $kategoriKey, $allKegiatan)
    {
        $isSakit = $s->riwayatKesehatan->where('created_at', '>=', $tgl.' 00:00:00')->where('created_at', '<=', $tgl.' 23:59:59')->first();
        if ($isSakit) return ['status' => 'S', 'class' => 'bg-yellow-100 text-yellow-700', 'text' => 'Sakit'];

        $isIzin = $s->perizinans->filter(fn($p) => $tgl >= $p->tgl_mulai->format('Y-m-d') && $tgl <= $p->tgl_selesai_rencana->format('Y-m-d'))->first();
        if ($isIzin) return ['status' => 'I', 'class' => 'bg-blue-100 text-blue-700', 'text' => 'Izin'];

        if (str_starts_with($kategoriKey, 'kegiatan_')) {
            $kid = str_replace('kegiatan_', '', $kategoriKey);
            $kDef = $allKegiatan->find($kid);
            
            if ($kDef) {
                $wajib = true;
                if ($kDef->tipe_peserta == 'kelas' && !in_array($s->kelas_id, $kDef->detail_peserta ?? [])) $wajib = false;
                if ($kDef->tipe_peserta == 'khusus' && !in_array($s->id, $kDef->detail_peserta ?? [])) $wajib = false;
                if (!$wajib) return ['status' => 'TM', 'class' => 'bg-gray-50 text-gray-400', 'text' => 'Tidak Mengikuti'];
            }
        }

        $found = $s->absensis->filter(function ($absen) use ($kategoriKey, $tgl) {
            if ($absen->tanggal->format('Y-m-d') !== $tgl) return false;

            if (str_starts_with($kategoriKey, 'kegiatan_')) {
                $kid = str_replace('kegiatan_', '', $kategoriKey);
                return $absen->kegiatan_id == $kid;
            } 
            elseif (str_starts_with($kategoriKey, 'jamaah_')) {
                $namaSholat = ucfirst(str_replace('jamaah_', '', $kategoriKey));
                return $absen->nama_kegiatan === $namaSholat;
            } 
            else {
                return $absen->nama_kegiatan === $kategoriKey;
            }
        })->first();

        if ($found) return ['status' => 'H', 'class' => 'bg-green-100 text-green-700', 'text' => 'Hadir'];
        
        if ($tgl > now()->format('Y-m-d')) return ['status' => '-', 'class' => 'text-gray-300', 'text' => '-'];
        return ['status' => 'A', 'class' => 'bg-red-100 text-red-700', 'text' => 'Alpha'];
    }
}