<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\Guru;
use App\Models\Sekolah\AbsensiGuru;
use App\Models\Sekolah\SekolahAbsensiSetting;
use App\Models\Sekolah\SekolahHariLibur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RfidAbsensiController extends Controller
{
    public function tap(Request $request)
    {
        // 1. Validasi Input dari Alat
        $request->validate([
            'uid' => 'required|string',
            'api_key' => 'required|string', // Opsional: Untuk keamanan alat
        ]);

        // Cek API Key Sederhana (Simpan di .env: RFID_DEVICE_KEY=rahasia123)
        if ($request->api_key != env('RFID_DEVICE_KEY', '123456')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized Device'], 401);
        }

        // 2. Cari Guru berdasarkan UID
        $guru = Guru::where('rfid_uid', $request->uid)->first();

        if (!$guru) {
            return response()->json(['status' => 'error', 'message' => 'Kartu Tidak Terdaftar'], 404);
        }

        // --- PERBAIKAN: AMBIL ID SEKOLAH DARI RELASI USER ---
        // Karena sistem pakai Many-to-Many, kita ambil sekolah pertama yang terdaftar untuk guru ini.
        $userSekolah = $guru->user->sekolahs->first();

        if (!$userSekolah) {
             return response()->json(['status' => 'error', 'message' => 'Guru tidak terkait sekolah'], 400);
        }
        
        $sekolahId = $userSekolah->id;
        // ----------------------------------------------------

        // 3. Ambil Setting Waktu
        $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolahId)->first();
        if (!$settings) {
            return response()->json(['status' => 'error', 'message' => 'Jam kerja belum diatur'], 400);
        }

        $now = Carbon::now();
        $todayStr = $now->format('Y-m-d');
        $timeStr = $now->format('H:i:s');
        $hariIndo = $now->locale('id')->isoFormat('dddd');

        // 4. Cek Hari Libur / Hari Kerja
        if (SekolahHariLibur::where('sekolah_id', $sekolahId)->whereDate('tanggal', $todayStr)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Hari Libur'], 400);
        }
        if (!in_array($hariIndo, $settings->hari_kerja ?? [])) {
            return response()->json(['status' => 'error', 'message' => 'Bukan Hari Kerja'], 400);
        }

        // 5. Cek Riwayat Absen Hari Ini
        $absensi = AbsensiGuru::firstOrNew([
            'guru_user_id' => $guru->user_id, // Pastikan relasi user_id benar
            'sekolah_id'   => $sekolahId,
            'tanggal'      => $todayStr
        ]);

        // --- LOGIKA PINTAR: MENENTUKAN MASUK / PULANG ---
        
        // Skenario A: Belum pernah tap hari ini -> ABSEN MASUK
        if (!$absensi->exists || is_null($absensi->jam_masuk)) {
            
            // Cek apakah terlalu pagi? (Opsional)
            // if ($timeStr < "05:00:00") return response()->json(['message' => 'Terlalu Pagi'], 400);

            $status = 'hadir';
            // Cek Telat
            if ($timeStr > $settings->batas_telat) {
                $status = 'terlambat';
            }

            $absensi->jam_masuk = $timeStr;
            $absensi->status = $status;
            $absensi->verifikasi_masuk = 'RFID';
            $absensi->save();

            return response()->json([
                'status' => 'success',
                'mode' => 'MASUK',
                'nama' => $guru->nama_guru,
                'jam' => $timeStr,
                'pesan' => ($status == 'terlambat') ? 'Terlambat Masuk' : 'Selamat Datang'
            ]);
        }

        // Skenario B: Sudah Masuk, Tapi Belum Pulang -> ABSEN PULANG
        if (!is_null($absensi->jam_masuk) && is_null($absensi->jam_pulang)) {
            
            // Debounce: Cegah tap ganda dalam waktu singkat (misal 1 menit)
            $waktuMasuk = Carbon::parse($absensi->jam_masuk);
            if ($now->diffInMinutes($waktuMasuk) < 5) {
                return response()->json(['status' => 'warning', 'message' => 'Sudah Tap Masuk Barusan']);
            }

            // Cek apakah sudah waktunya pulang?
            if ($timeStr < $settings->jam_pulang_awal) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Belum Jam Pulang',
                    'info' => 'Pulang: ' . $settings->jam_pulang_awal
                ], 400);
            }

            $absensi->jam_pulang = $timeStr;
            $absensi->verifikasi_pulang = 'RFID';
            $absensi->save();

            return response()->json([
                'status' => 'success',
                'mode' => 'PULANG',
                'nama' => $guru->nama_guru,
                'jam' => $timeStr,
                'pesan' => 'Hati-hati di jalan'
            ]);
        }

        // Skenario C: Sudah Masuk & Sudah Pulang -> INFO SUDAH SELESAI
        return response()->json([
            'status' => 'info',
            'message' => 'Anda sudah selesai hari ini',
            'nama' => $guru->nama_guru
        ]);
    }
}