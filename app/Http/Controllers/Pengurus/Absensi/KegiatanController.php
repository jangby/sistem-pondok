<?php

namespace App\Http\Controllers\Pengurus\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Kelas;
use App\Models\KesehatanSantri;
use App\Models\Perizinan;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    // 1. HALAMAN INDEX
    public function index()
    {
        $kegiatans = Kegiatan::where('pondok_id', $this->getPondokId())->get();
        return view('pengurus.absensi.kegiatan.index', compact('kegiatans'));
    }

    // 2. SETTINGS
    public function settings()
    {
        $kegiatans = Kegiatan::where('pondok_id', $this->getPondokId())->latest()->get();
        $kelas = Kelas::where('pondok_id', $this->getPondokId())->get();
        $santris = Santri::where('pondok_id', $this->getPondokId())->where('status', 'active')->orderBy('full_name')->get();
        
        return view('pengurus.absensi.kegiatan.settings', compact('kegiatans', 'kelas', 'santris'));
    }

    public function storeSettings(Request $request)
    {
        $data = $request->validate([
            'nama_kegiatan' => 'required',
            'frekuensi' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tipe_peserta' => 'required',
        ]);

        $data['pondok_id'] = $this->getPondokId();
        $data['detail_waktu'] = $request->detail_waktu; 
        $data['detail_peserta'] = $request->detail_peserta; 

        Kegiatan::create($data);
        return back()->with('success', 'Kegiatan berhasil dibuat.');
    }
    
    public function delete($id)
    {
        Kegiatan::where('id', $id)->where('pondok_id', $this->getPondokId())->delete();
        return back()->with('success', 'Kegiatan dihapus.');
    }

    // 3. SCANNER
    public function scan(Request $request)
    {
        $kegiatans = Kegiatan::where('pondok_id', $this->getPondokId())->get();
        return view('pengurus.absensi.kegiatan.scan', compact('kegiatans'));
    }

    public function processScan(Request $request)
    {
        $pondokId = $this->getPondokId();
        $rfid = $request->rfid;
        $kegiatanId = $request->kegiatan_id;

        if(!$kegiatanId) return response()->json(['status' => 'error', 'message' => 'Pilih kegiatan dulu!'], 400);

        $santri = Santri::where('pondok_id', $pondokId)
            ->where(fn($q) => $q->where('rfid_uid', $rfid)->orWhere('qrcode_token', $rfid))
            ->first();

        if (!$santri) return response()->json(['status' => 'error', 'message' => 'Kartu tidak terdaftar'], 404);

        $kegiatan = Kegiatan::find($kegiatanId);
        $isPeserta = true;

        if ($kegiatan->tipe_peserta == 'kelas') {
            if (!in_array($santri->kelas_id, $kegiatan->detail_peserta ?? [])) $isPeserta = false;
        } elseif ($kegiatan->tipe_peserta == 'khusus') {
            if (!in_array($santri->id, $kegiatan->detail_peserta ?? [])) $isPeserta = false;
        }

        if (!$isPeserta) return response()->json(['status' => 'error', 'message' => 'Bukan peserta kegiatan ini'], 400);

        $exists = Absensi::where('santri_id', $santri->id)
            ->where('kategori', 'kegiatan_khusus')
            ->where('kegiatan_id', $kegiatanId)
            ->whereDate('tanggal', now())
            ->exists();
        
        if ($exists) return response()->json(['status' => 'warning', 'message' => 'Sudah absen', 'santri' => $santri->full_name]);

        // PERBAIKAN UTAMA: Gunakan toDateString() agar format tanggal bersih (Y-m-d)
        Absensi::create([
            'pondok_id' => $pondokId,
            'santri_id' => $santri->id,
            'kategori' => 'kegiatan_khusus',
            'kegiatan_id' => $kegiatanId,
            'nama_kegiatan' => $kegiatan->nama_kegiatan,
            'status' => 'hadir',
            'tanggal' => now()->toDateString(), // <-- INI SOLUSINYA
            'waktu_catat' => now(),
            'pencatat_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Hadir', 'santri' => $santri->full_name]);
    }

    // 4. REKAP LIST
    public function rekapList()
    {
        $kegiatans = Kegiatan::where('pondok_id', $this->getPondokId())->get();
        return view('pengurus.absensi.kegiatan.rekap_list', compact('kegiatans'));
    }

    // 5. REKAP DETAIL (GRAFIK & LIST NAMA)
    public function rekapShow(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        $kegiatan = Kegiatan::findOrFail($id);
        $tanggal = $request->get('date', now()->format('Y-m-d'));

        // A. Cari Populasi Santri yg Wajib Hadir
        $querySantri = Santri::where('pondok_id', $pondokId)->where('status', 'active');

        if ($kegiatan->tipe_peserta == 'kelas') {
            $querySantri->whereIn('kelas_id', $kegiatan->detail_peserta ?? []);
        } elseif ($kegiatan->tipe_peserta == 'khusus') {
            $querySantri->whereIn('id', $kegiatan->detail_peserta ?? []);
        }
        
        // Eager Load Status
        $santris = $querySantri->with(['absensis' => function($q) use ($kegiatan, $tanggal) {
            $q->where('kegiatan_id', $kegiatan->id)->whereDate('tanggal', $tanggal);
        }, 'riwayatKesehatan' => function($q) use ($tanggal) {
             $q->where('created_at', '>=', $tanggal.' 00:00:00')->where('status', '!=', 'sembuh');
        }, 'perizinans' => function($q) use ($tanggal) {
             $q->where('status', 'disetujui')->whereDate('tgl_mulai', '<=', $tanggal)->whereDate('tgl_selesai_rencana', '>=', $tanggal);
        }])->orderBy('full_name')->get();

        // B. Siapkan Array Penampung
        $detail = [
            'hadir' => ['Laki-laki' => [], 'Perempuan' => []],
            'belum' => ['Laki-laki' => [], 'Perempuan' => []]
        ];
        
        // Variabel bantu hitung sakit/izin (tidak masuk wajib hadir)
        $countSakit = ['Laki-laki' => 0, 'Perempuan' => 0];
        $countIzin = ['Laki-laki' => 0, 'Perempuan' => 0];

        foreach ($santris as $s) {
            $gender = $s->jenis_kelamin;
            // Pastikan gender valid (jaga-jaga data lama kosong)
            if (!in_array($gender, ['Laki-laki', 'Perempuan'])) continue;

            $isSakit = $s->riwayatKesehatan->isNotEmpty();
            $isIzin = $s->perizinans->isNotEmpty();
            $isHadir = $s->absensis->isNotEmpty();

            // Jika Sakit/Izin, catat tapi jangan masukkan ke list Hadir/Belum
            if ($isSakit) { 
                $countSakit[$gender]++; 
                continue; 
            }
            if ($isIzin) { 
                $countIzin[$gender]++; 
                continue; 
            }

            // Masukkan ke List Detail
            if ($isHadir) {
                $detail['hadir'][$gender][] = $s;
            } else {
                $detail['belum'][$gender][] = $s;
            }
        }

        // C. Hitung Statistik DARI Array Detail (Agar Konsisten 100%)
        $stats = [];
        foreach (['Laki-laki', 'Perempuan'] as $g) {
            $hadir = count($detail['hadir'][$g]);
            $belum = count($detail['belum'][$g]);
            $wajib = $hadir + $belum;
            
            $stats[$g] = [
                'wajib' => $wajib,
                'hadir' => $hadir,
                'sakit' => $countSakit[$g],
                'izin'  => $countIzin[$g],
                'persen' => $wajib > 0 ? round(($hadir / $wajib) * 100) : 0
            ];
        }

        return view('pengurus.absensi.kegiatan.rekap_show', compact('kegiatan', 'stats', 'detail', 'tanggal'));
    }
}