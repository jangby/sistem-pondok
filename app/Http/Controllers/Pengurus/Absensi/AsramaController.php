<?php

namespace App\Http\Controllers\Pengurus\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\Absensi;
use App\Models\AbsensiSetting;
use App\Models\LiburPondok;
use App\Models\KesehatanSantri;
use App\Models\Perizinan;
use Carbon\Carbon;

class AsramaController extends Controller
{
    private function getPondokId() {
        return Auth::user()->pondokStaff->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();
        $today = Carbon::today();
        
        // Tentukan Sesi
        $jamSekarang = now()->format('H:i');
        $sesi = ($jamSekarang >= '12:00') ? 'asrama_malam' : 'asrama_pagi';

        // --- 1. HITUNG STATISTIK GLOBAL (Sama seperti sebelumnya) ---
        $stats = [
            'Laki-laki' => ['total' => 0, 'sakit' => 0, 'izin' => 0, 'wajib' => 0, 'hadir' => 0, 'persen' => 0],
            'Perempuan' => ['total' => 0, 'sakit' => 0, 'izin' => 0, 'wajib' => 0, 'hadir' => 0, 'persen' => 0],
        ];

        // --- 2. AMBIL DATA DETAIL (UNTUK MODAL) ---
        // Struktur array penampung
        $detailSantri = [
            'hadir' => ['Laki-laki' => [], 'Perempuan' => []],
            'belum' => ['Laki-laki' => [], 'Perempuan' => []]
        ];

        // Ambil semua santri aktif beserta statusnya (Eager Loading agar cepat)
        $allSantris = Santri::where('pondok_id', $pondokId)
            ->where('status', 'active')
            ->with(['riwayatKesehatan' => function($q) {
                $q->where('status', '!=', 'sembuh'); // Cek Sakit Aktif
            }, 'perizinans' => function($q) use ($today) {
                $q->where('status', 'disetujui') // Cek Izin Aktif
                  ->whereDate('tgl_mulai', '<=', $today)
                  ->whereDate('tgl_selesai_rencana', '>=', $today);
            }, 'absensis' => function($q) use ($today, $sesi) {
                $q->where('kategori', 'asrama') // Cek Absen Hari Ini
                  ->where('nama_kegiatan', $sesi)
                  ->whereDate('tanggal', $today);
            }])
            ->orderBy('full_name')
            ->get();

        // Loop dan Kelompokkan
        foreach ($allSantris as $s) {
            $gender = $s->jenis_kelamin;
            
            // Update Counter Total
            $stats[$gender]['total']++;

            $isSakit = $s->riwayatKesehatan->isNotEmpty();
            $isIzin = $s->perizinans->isNotEmpty();
            $isHadir = $s->absensis->isNotEmpty();

            if ($isSakit) {
                $stats[$gender]['sakit']++;
                continue; // Skip (Tidak Wajib Hadir)
            }
            if ($isIzin) {
                $stats[$gender]['izin']++;
                continue; // Skip (Tidak Wajib Hadir)
            }

            // Jika Wajib Hadir
            if ($isHadir) {
                $stats[$gender]['hadir']++;
                $detailSantri['hadir'][$gender][] = $s; // Masukkan ke list Hadir
            } else {
                $detailSantri['belum'][$gender][] = $s; // Masukkan ke list Belum
            }
        }

        // Hitung Sisanya
        foreach (['Laki-laki', 'Perempuan'] as $g) {
            $stats[$g]['wajib'] = count($detailSantri['hadir'][$g]) + count($detailSantri['belum'][$g]);
            $stats[$g]['persen'] = $stats[$g]['wajib'] > 0 
                ? round(($stats[$g]['hadir'] / $stats[$g]['wajib']) * 100) 
                : 0;
        }

        // Gabungan untuk tampilan utama
        $totalWajib = $stats['Laki-laki']['wajib'] + $stats['Perempuan']['wajib'];
        $totalHadir = $stats['Laki-laki']['hadir'] + $stats['Perempuan']['hadir'];
        $totalSakit = $stats['Laki-laki']['sakit'] + $stats['Perempuan']['sakit'];
        $totalIzin = $stats['Laki-laki']['izin'] + $stats['Perempuan']['izin'];
        
        return view('pengurus.absensi.asrama.index', compact(
            'stats', 'detailSantri', 
            'totalWajib', 'totalHadir', 'totalSakit', 'totalIzin'
        ));
    }
    
    // --- REKAP & HISTORY ---
    public function rekap(Request $request)
    {
        $pondokId = $this->getPondokId();
        $mode = $request->get('mode', 'harian');

        if ($mode == 'santri') {
            // (Logika Pencarian Per Santri - Tidak Berubah)
            $santri = null;
            $riwayat = collect([]);

            if ($request->has('search')) {
                $keyword = $request->search;
                $santri = Santri::where('pondok_id', $pondokId)
                    ->where(function($q) use ($keyword) {
                        $q->where('nis', $keyword)->orWhere('full_name', 'like', "%$keyword%");
                    })->first();

                if ($santri) {
                    $riwayat = Absensi::where('santri_id', $santri->id)
                        ->where('kategori', 'asrama')
                        ->latest('waktu_catat')
                        ->limit(30)
                        ->get();
                }
            }
            return view('pengurus.absensi.asrama.rekap', compact('mode', 'santri', 'riwayat'));

        } else {
            // --- PERBAIKAN DISINI (EAGER LOADING ABSENSI) ---
            $tanggal = $request->get('date', now()->format('Y-m-d'));
            $sesi = $request->get('sesi', 'semua');

            $santris = Santri::where('pondok_id', $pondokId)
                ->where('status', 'active')
                // Load absensi HANYA pada tanggal yang dipilih agar ringan & akurat
                ->with(['absensis' => function($q) use ($tanggal) {
                    $q->whereDate('tanggal', $tanggal)
                      ->where('kategori', 'asrama');
                }, 'riwayatKesehatan' => function($q) use ($tanggal) {
                     $q->where('created_at', '>=', $tanggal.' 00:00:00')
                       ->where('created_at', '<=', $tanggal.' 23:59:59')
                       ->where('status', '!=', 'sembuh');
                }])
                ->orderBy('full_name')
                ->paginate(20)
                ->withQueryString();

            return view('pengurus.absensi.asrama.rekap', compact('mode', 'santris', 'tanggal', 'sesi'));
        }
    }

    // ... (SISA KODE SETTINGS, LIBUR, SCAN BIARKAN SAMA SEPERTI SEBELUMNYA) ...
    
    public function settings()
    {
        $pondokId = $this->getPondokId();
        $pagi = AbsensiSetting::firstOrNew(['pondok_id' => $pondokId, 'jenis' => 'asrama_pagi']);
        $malam = AbsensiSetting::firstOrNew(['pondok_id' => $pondokId, 'jenis' => 'asrama_malam']);
        $libur = LiburPondok::where('pondok_id', $pondokId)->whereDate('tanggal', '>=', now())->orderBy('tanggal')->get();
        return view('pengurus.absensi.asrama.settings', compact('pagi', 'malam', 'libur'));
    }

    public function storeSettings(Request $request)
    {
        $pondokId = $this->getPondokId();
        AbsensiSetting::updateOrCreate(['pondok_id' => $pondokId, 'jenis' => 'asrama_pagi'], ['jam_mulai' => $request->pagi_mulai, 'jam_selesai' => $request->pagi_selesai]);
        AbsensiSetting::updateOrCreate(['pondok_id' => $pondokId, 'jenis' => 'asrama_malam'], ['jam_mulai' => $request->malam_mulai, 'jam_selesai' => $request->malam_selesai]);
        return back()->with('success', 'Pengaturan waktu berhasil disimpan.');
    }
    
    public function storeLibur(Request $request)
    {
        LiburPondok::create(['pondok_id' => $this->getPondokId(), 'tanggal' => $request->tanggal, 'keterangan' => $request->keterangan]);
        return back()->with('success', 'Hari libur ditambahkan.');
    }
    
    public function deleteLibur($id)
    {
        LiburPondok::where('id', $id)->where('pondok_id', $this->getPondokId())->delete();
        return back()->with('success', 'Hari libur dihapus.');
    }

    public function scan()
    {
        $isLibur = LiburPondok::where('pondok_id', $this->getPondokId())->whereDate('tanggal', now())->exists();  
        if ($isLibur) return redirect()->route('pengurus.absensi.asrama')->with('error', 'Hari ini libur, absensi ditutup.');
        return view('pengurus.absensi.asrama.scan');
    }

    public function processScan(Request $request)
    {
        $pondokId = $this->getPondokId();
        $rfid = $request->rfid;

        $santri = Santri::where('pondok_id', $pondokId)
                    ->where(function($q) use ($rfid) {
                        $q->where('rfid_uid', $rfid)->orWhere('qrcode_token', $rfid);
                    })->first();

        if (!$santri) return response()->json(['status' => 'error', 'message' => 'Kartu tidak terdaftar'], 404);

        $jamSekarang = now()->format('H:i:s');
        $sesi = ($jamSekarang >= '12:00:00') ? 'asrama_malam' : 'asrama_pagi';

        $setting = AbsensiSetting::where('pondok_id', $pondokId)->where('jenis', $sesi)->first();
        if ($setting) {
            if ($jamSekarang < $setting->jam_mulai || $jamSekarang > $setting->jam_selesai) {
                return response()->json(['status' => 'error', 'message' => 'Di luar jam absen (' . $setting->jam_mulai . '-' . $setting->jam_selesai . ')'], 400);
            }
        }

        $exists = Absensi::where('santri_id', $santri->id)->where('kategori', 'asrama')->where('nama_kegiatan', $sesi)->whereDate('tanggal', now())->exists();
        if ($exists) return response()->json(['status' => 'warning', 'message' => 'Sudah absen sebelumnya', 'santri' => $santri->full_name]);

        Absensi::create([
            'pondok_id' => $pondokId,
            'santri_id' => $santri->id,
            'kategori' => 'asrama',
            'nama_kegiatan' => $sesi,
            'status' => 'hadir',
            'tanggal' => now(),
            'waktu_catat' => now(),
            'pencatat_id' => Auth::id(),
        ]); 

        return response()->json(['status' => 'success', 'message' => 'Berhasil', 'santri' => $santri->full_name, 'nis' => $santri->nis, 'waktu' => now()->format('H:i')]);
    }
}