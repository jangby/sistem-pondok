<?php

namespace App\Http\Controllers\Pengurus\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\Asrama;
use App\Models\Absensi;
use App\Models\AbsensiSetting;
use App\Models\LiburPondok;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AbsensiKetuaController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    public function index()
    {
        return view('pengurus.asrama.absensi_ketua.index');
    }

    public function process(Request $request)
    {
        $pondokId = $this->getPondokId();
        $rfid = $request->rfid;
        
        // Cari Santri
        $santri = Santri::where('pondok_id', $pondokId)
            ->where(fn($q) => $q->where('rfid_uid', $rfid)->orWhere('qrcode_token', $rfid))
            ->first();

        if (!$santri) return response()->json(['status' => 'error', 'message' => 'Kartu tidak terdaftar'], 404);

        // Validasi Ketua
        $isKetua = Asrama::where('pondok_id', $pondokId)->where('ketua_asrama', $santri->full_name)->exists();
        if (!$isKetua) return response()->json(['status' => 'error', 'message' => 'Bukan Ketua Asrama'], 403);

        // Validasi Libur & Jam
        $isLibur = LiburPondok::where('pondok_id', $pondokId)->whereDate('tanggal', now())->exists();
        if ($isLibur) return response()->json(['status' => 'error', 'message' => 'Hari Libur'], 400);

        $setting = AbsensiSetting::where('pondok_id', $pondokId)->where('jenis', 'ketua_asrama')->first();
        if ($setting) {
            $jam = now()->format('H:i:s');
            if ($jam < $setting->jam_mulai || $jam > $setting->jam_selesai) return response()->json(['status' => 'error', 'message' => 'Diluar Jam Absen'], 400);
        }

        // Cek Duplikat
        $exists = Absensi::where('santri_id', $santri->id)->where('kategori', 'kegiatan_khusus')->where('nama_kegiatan', 'Absensi Ketua')->whereDate('tanggal', now())->exists();
        if ($exists) return response()->json(['status' => 'warning', 'message' => 'Sudah Absen', 'santri' => $santri->full_name]);

        // Simpan
        Absensi::create([
            'pondok_id' => $pondokId, 'santri_id' => $santri->id, 'kategori' => 'kegiatan_khusus', 'nama_kegiatan' => 'Absensi Ketua',
            'status' => 'hadir', 'tanggal' => now()->toDateString(), 'waktu_catat' => now(), 'pencatat_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Hadir', 'santri' => $santri->full_name]);
    }

    public function settings()
    {
        $pondokId = $this->getPondokId();
        $setting = AbsensiSetting::firstOrNew(['pondok_id' => $pondokId, 'jenis' => 'ketua_asrama']);
        $libur = LiburPondok::where('pondok_id', $pondokId)->whereDate('tanggal', '>=', now())->orderBy('tanggal')->get();
        return view('pengurus.asrama.absensi_ketua.settings', compact('setting', 'libur'));
    }

    public function storeSettings(Request $request)
    {
        AbsensiSetting::updateOrCreate(['pondok_id' => $this->getPondokId(), 'jenis' => 'ketua_asrama'], ['jam_mulai' => $request->jam_mulai, 'jam_selesai' => $request->jam_selesai]);
        return back()->with('success', 'Pengaturan disimpan.');
    }

    // --- HALAMAN LIST REKAP ---
    public function rekap(Request $request)
    {
        $pondokId = $this->getPondokId();
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        if ($endDate < $startDate) $endDate = $startDate;

        $namaKetuaList = Asrama::where('pondok_id', $pondokId)->pluck('ketua_asrama')->toArray();
        
        $paraKetua = Santri::where('pondok_id', $pondokId)
            ->where('status', 'active')
            ->whereIn('full_name', $namaKetuaList)
            // PERBAIKAN: Tambahkan 'asrama' agar tidak muncul "Asrama ?"
            ->with(['asrama', 'absensis' => function($q) use ($startDate, $endDate) {
                $q->where('kategori', 'kegiatan_khusus')
                  ->where('nama_kegiatan', 'Absensi Ketua')
                  ->whereBetween('tanggal', [$startDate, $endDate]);
            }])
            ->get();

        // Hitung Ringkasan Sederhana untuk Card Atas
        $periode = CarbonPeriod::create($startDate, $endDate);
        $totalHari = $periode->count();
        
        $rekapData = [];
        foreach ($paraKetua as $ketua) {
            $hadirCount = $ketua->absensis->count(); // Hitung jumlah hadir dalam rentang
            $rekapData[] = [
                'santri' => $ketua,
                'hadir' => $hadirCount,
                'bolos' => $totalHari - $hadirCount
            ];
        }

        return view('pengurus.asrama.absensi_ketua.rekap', compact('rekapData', 'startDate', 'endDate', 'totalHari'));
    }

    // --- HALAMAN DETAIL (HALAMAN BARU) ---
    public function rekapDetail(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        $santri = Santri::where('pondok_id', $pondokId)->with('asrama')->findOrFail($id);

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Ambil Data Lengkap (Absen, Sakit, Izin)
        $santri->load(['absensis' => function($q) use ($startDate, $endDate) {
            $q->where('kategori', 'kegiatan_khusus')
              ->where('nama_kegiatan', 'Absensi Ketua')
              ->whereBetween('tanggal', [$startDate, $endDate]);
        }, 'riwayatKesehatan' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])->where('status', '!=', 'sembuh');
        }, 'perizinans' => function($q) use ($startDate, $endDate) {
            $q->where('status', 'disetujui')->whereDate('tgl_mulai', '<=', $endDate)->whereDate('tgl_selesai_rencana', '>=', $startDate);
        }]);

        // Build Timeline Harian
        $periode = CarbonPeriod::create($startDate, $endDate);
        $history = [];
        $stats = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];

        foreach ($periode as $date) {
            $tglStr = $date->format('Y-m-d');
            $status = 'A';

            $absen = $santri->absensis->first(fn($a) => $a->tanggal->format('Y-m-d') == $tglStr);
            if ($absen) {
                $status = 'H'; $stats['hadir']++;
            } else {
                $sakit = $santri->riwayatKesehatan->first(fn($s) => $date->between($s->created_at, $s->tanggal_sembuh ?? now()));
                if ($sakit) { $status = 'S'; $stats['sakit']++; }
                else {
                    $izin = $santri->perizinans->first(fn($p) => $date->between($p->tgl_mulai, $p->tgl_selesai_rencana));
                    if ($izin) { $status = 'I'; $stats['izin']++; }
                    else {
                         if ($date->isFuture()) $status = '-';
                         else $stats['alpha']++;
                    }
                }
            }
            $history[$tglStr] = $status;
        }

        return view('pengurus.asrama.absensi_ketua.rekap_detail', compact('santri', 'history', 'stats', 'startDate', 'endDate'));
    }
}