<?php

namespace App\Http\Controllers\Pengurus\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\Gate\GateSetting;
use App\Models\Gate\GateLog;
use App\Models\Perizinan;
use Carbon\Carbon;

class GateController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    // --- TAMPILAN WEB ---

    public function index()
    {
        $pondokId = $this->getPondokId();
        
        // Ambil daftar santri yang SEDANG DILUAR (in_time = NULL)
        $sedangDiluar = GateLog::where('pondok_id', $pondokId)
            ->whereNull('in_time')
            ->with('santri')
            ->orderBy('out_time', 'desc')
            ->get();

        // Statistik
        $totalDiluar = $sedangDiluar->count();
        $totalTerlambat = $sedangDiluar->where('is_late', true)->count();

        return view('pengurus.absensi.gerbang.index', compact('sedangDiluar', 'totalDiluar', 'totalTerlambat'));
    }

    public function settings()
    {
        $setting = GateSetting::firstOrNew(['pondok_id' => $this->getPondokId()]);
        return view('pengurus.absensi.gerbang.settings', compact('setting'));
    }

    public function storeSettings(Request $request)
    {
        GateSetting::updateOrCreate(
            ['pondok_id' => $this->getPondokId()],
            [
                'max_minutes_out' => $request->max_minutes_out,
                'satpam_wa_number' => $request->satpam_wa_number,
                'auto_notify' => $request->has('auto_notify')
            ]
        );
        return back()->with('success', 'Pengaturan Gerbang disimpan.');
    }

    // --- API UNTUK IOT (MESIN GERBANG) ---
    // IoT mengirim POST ke: /api/gate/scan dengan body { "rfid": "..." }
    
    public function apiScan(Request $request)
    {
        $rfid = $request->rfid;
        // Hardcode Pondok ID atau kirim via API Key (Disini kita cari santri dulu)
        
        // 1. Cari Santri Global (karena IoT mungkin belum tahu pondok_id)
        $santri = Santri::where('rfid_uid', $rfid)->orWhere('qrcode_token', $rfid)->first();
        
        if (!$santri) {
            return response()->json(['status' => 'error', 'message' => 'Kartu tidak dikenal'], 404);
        }

        $pondokId = $santri->pondok_id;

        // 2. Cek Status Terakhir (Apakah sedang diluar?)
        $lastLog = GateLog::where('pondok_id', $pondokId)
            ->where('santri_id', $santri->id)
            ->whereNull('in_time') // Masih di luar
            ->latest()
            ->first();

        if ($lastLog) {
            // --- KASUS B: SEDANG DILUAR -> BERARTI MAU MASUK (TAP IN) ---
            $lastLog->update(['in_time' => now()]);
            
            return response()->json([
                'status' => 'success',
                'type' => 'IN',
                'message' => 'Selamat Datang Kembali',
                'nama' => $santri->full_name
            ]);

        } else {
            // --- KASUS A: DI DALAM -> BERARTI MAU KELUAR (TAP OUT) ---
            GateLog::create([
                'pondok_id' => $pondokId,
                'santri_id' => $santri->id,
                'out_time' => now(),
                'notified' => false
            ]);

            return response()->json([
                'status' => 'success',
                'type' => 'OUT',
                'message' => 'Hati-hati di Jalan',
                'nama' => $santri->full_name
            ]);
        }
    }
}