<?php

namespace App\Http\Controllers\Sekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri; //
use App\Models\Kelas; //
use App\Models\Sekolah\Sekolah; //
use App\Models\Sekolah\SekolahAbsensiSetting; //
use App\Models\Sekolah\SekolahHariLibur; //
use App\Models\Sekolah\AbsensiSiswaSekolah;
use App\Models\KesehatanSantri; //
use App\Models\Perizinan; //
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SekolahApiController extends Controller
{
    /**
     * Menangani scan absensi dari perangkat IoT
     */
    public function scanAbsensi(Request $request)
    {
        $validated = $request->validate([
            'kartu_id' => 'required|string',
        ]);

        $kartuId = $validated['kartu_id'];
        $now = Carbon::now();
        $tanggal = $now->format('Y-m-d');
        $waktu = $now->format('H:i:s');

        // === 1. CARI SANTRI ===
        $santri = Santri::where('rfid_uid', $kartuId) //
                       ->orWhere('qrcode_token', $kartuId) //
                       ->first();
        
        if (!$santri) {
            return response()->json(['status' => 'error', 'message' => 'Kartu tidak dikenal'], 404);
        }
        if (!$santri->kelas_id) {
            return response()->json(['status' => 'error', 'message' => $santri->full_name . ' belum punya kelas'], 422);
        }

        // === 2. CARI SEKOLAH & PENGATURAN ===
        $kelas = $santri->kelas; //
        $sekolah = Sekolah::where('pondok_id', $santri->pondok_id) //
                          ->where('tingkat', $kelas->tingkat) //
                          ->first();
                          
        if (!$sekolah) {
            return response()->json(['status' => 'error', 'message' => 'Sekolah (cth: ' . $kelas->tingkat . ') tidak ditemukan'], 404);
        }
        
        $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first(); //
        if (!$settings) {
            return response()->json(['status' => 'error', 'message' => 'Admin belum mengatur jam absensi'], 404);
        }

        // === 3. VALIDASI HARI & JAM ===
        $namaHariIni = $now->locale('id_ID')->isoFormat('dddd');
        $isHariKerja = in_array($namaHariIni, $settings->hari_kerja ?? []); //
        $isHariLibur = SekolahHariLibur::where('sekolah_id', $sekolah->id)->whereDate('tanggal', $tanggal)->exists(); //

        if (!$isHariKerja) return response()->json(['status' => 'error', 'message' => 'Hari ini bukan hari kerja (' . $namaHariIni . ')'], 422);
        if ($isHariLibur) return response()->json(['status' => 'error', 'message' => 'Hari ini libur (Tanggal Merah)'], 422);

        // === 4. VALIDASI SAKIT / IZIN (INTEGRASI) ===
        $isSakit = KesehatanSantri::where('santri_id', $santri->id)->where('status', '!=', 'sembuh')->whereDate('tanggal_sakit', '<=', $tanggal)->where(function ($q) use ($tanggal) { $q->whereDate('tanggal_sembuh', '>=', $tanggal)->orWhereNull('tanggal_sembuh'); })->exists();
        $isIzin = Perizinan::where('santri_id', $santri->id)->where('status', 'disetujui')->whereDate('tgl_mulai', '<=', $tanggal)->whereDate('tgl_selesai_rencana', '>=', $tanggal)->exists();
        
        if ($isSakit) return response()->json(['status' => 'error', 'message' => 'Santri tercatat SAKIT (UKS)'], 422);
        if ($isIzin) return response()->json(['status' => 'error', 'message' => 'Santri tercatat IZIN (Perizinan)'], 422);

        // === 5. PROSES ABSENSI (MASUK / PULANG) ===
        $logAbsen = AbsensiSiswaSekolah::firstOrNew(
            [
                'sekolah_id' => $sekolah->id,
                'santri_id' => $santri->id,
                'tanggal' => $tanggal,
            ]
        );

        if (!$logAbsen->jam_masuk) {
            // --- KASUS: ABSEN MASUK ---
            if ($waktu > $settings->batas_telat) return response()->json(['status' => 'error', 'message' => 'Sudah melewati batas telat masuk'], 422);
            if ($waktu < $settings->jam_masuk) return response()->json(['status' => 'error', 'message' => 'Belum waktunya absen masuk'], 422);

            $logAbsen->jam_masuk = $waktu;
            $logAbsen->status_masuk = ($waktu > $settings->batas_telat) ? 'terlambat' : 'tepat_waktu';
            $logAbsen->save();
            
            return response()->json([
                'status' => 'success',
                'type' => 'MASUK',
                'message' => 'Selamat Datang, ' . $santri->full_name,
            ]);

        } elseif (!$logAbsen->jam_pulang) {
            // --- KASUS: ABSEN PULANG ---
            if ($waktu < $settings->jam_pulang_awal) return response()->json(['status' => 'error', 'message' => 'Belum waktunya absen pulang'], 422);
            if ($waktu > $settings->jam_pulang_akhir) return response()->json(['status' => 'error', 'message' => 'Sudah melewati batas jam pulang'], 422);

            $logAbsen->jam_pulang = $waktu;
            $logAbsen->save();
            
            return response()->json([
                'status' => 'success',
                'type' => 'PULANG',
                'message' => 'Hati-hati di Jalan, ' . $santri->full_name,
            ]);
        
        } else {
            // --- KASUS: SUDAH ABSEN MASUK & PULANG ---
            return response()->json([
                'status' => 'warning',
                'type' => 'DUPLIKAT',
                'message' => $santri->full_name . ' sudah absen Masuk & Pulang hari ini.',
            ], 409);
        }
    }
}