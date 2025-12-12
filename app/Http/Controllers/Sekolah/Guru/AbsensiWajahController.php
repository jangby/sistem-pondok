<?php

namespace App\Http\Controllers\Sekolah\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Santri;
use App\Models\Sekolah\JadwalPelajaran;
use App\Models\Sekolah\AbsensiPelajaran;
use App\Models\Sekolah\AbsensiSiswaPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiWajahController extends Controller
{
    public function index($jadwal_id)
    {
        $jadwal = JadwalPelajaran::with(['kelas', 'mataPelajaran'])->findOrFail($jadwal_id);
        return view('sekolah.guru.absensi-wajah.scan', compact('jadwal'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'jadwal_id' => 'required'
        ]);

        // 1. Ambil Data Jadwal
        $jadwal = JadwalPelajaran::findOrFail($request->jadwal_id);

        // 2. Ambil Data Santri di Kelas Tersebut
        $santris = Santri::where('kelas_id', $jadwal->kelas_id)
                         ->whereNotNull('face_embedding')
                         ->select('id', 'face_embedding', 'full_name') 
                         ->get();

        if ($santris->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Belum ada data wajah santri.']);
        }

        // 3. Format Data untuk Python
        $known_faces = $santris->map(function($s) {
            return [
                'id' => $s->id,
                'embedding' => $s->face_embedding
            ];
        })->toArray();

        // 4. Simpan Gambar Sementara
        $image_parts = explode(";base64,", $request->image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'scan_' . time() . '_' . rand(100,999) . '.jpg';
        $filePath = sys_get_temp_dir() . '/' . $fileName;
        file_put_contents($filePath, $image_base64);

        try {
            // 5. Kirim ke Python
            $response = Http::timeout(60) 
                ->attach('image', file_get_contents($filePath), $fileName)
                ->post('http://72.61.208.130:5000/compare', [
                    'known_faces' => json_encode($known_faces)
                ]);
            
            @unlink($filePath);

            $result = $response->json();

            if (isset($result['status']) && $result['status'] == 'match') {
                $santriId = $result['santri_id'];
                $santri = $santris->firstWhere('id', $santriId);

                // === PERBAIKAN LOGIKA DISINI ===

                // A. Pastikan Header Absensi Ada
                $absensiPelajaran = AbsensiPelajaran::firstOrCreate(
                    [
                        'jadwal_pelajaran_id' => $jadwal->id,
                        'tanggal' => Carbon::today()->format('Y-m-d'),
                    ],
                    [
                        'guru_id' => Auth::id(),
                        'tahun_ajaran_id' => $jadwal->tahun_ajaran_id ?? 1,
                        'jam_mulai' => Carbon::now()->format('H:i:s'),
                        'status' => 'berlangsung'
                    ]
                );

                // B. Cek Data Absensi Siswa
                $cekAbsen = AbsensiSiswaPelajaran::where('absensi_pelajaran_id', $absensiPelajaran->id)
                            ->where('santri_id', $santriId)
                            ->first();

                if ($cekAbsen) {
                    // KASUS 1: Data sudah ada (Mungkin status 'alpa' atau 'belum')
                    if ($cekAbsen->status != 'hadir') {
                        // UPDATE jadi Hadir
                        $cekAbsen->update([
                            'status' => 'hadir',
                            'jam_hadir' => Carbon::now()->format('H:i:s'),
                            'keterangan' => 'Face Recognition'
                        ]);

                        return response()->json([
                            'status' => 'success', 
                            'message' => "Hadir (Updated)", 
                            'nama' => $santri->full_name 
                        ]);
                    } else {
                        // KASUS 2: Memang sudah hadir sebelumnya
                        // Kita kirim status 'info' agar log JS tahu, tapi tidak mengubah DB
                        return response()->json([
                            'status' => 'info', 
                            'message' => "Sudah Absen", 
                            'nama' => $santri->full_name 
                        ]);
                    }
                } else {
                    // KASUS 3: Data belum ada sama sekali -> Buat Baru
                    AbsensiSiswaPelajaran::create([
                        'absensi_pelajaran_id' => $absensiPelajaran->id,
                        'santri_id' => $santriId,
                        'status' => 'hadir',
                        'jam_hadir' => Carbon::now()->format('H:i:s'),
                        'keterangan' => 'Face Recognition'
                    ]);
                    
                    return response()->json([
                        'status' => 'success', 
                        'message' => "Hadir (New)", 
                        'nama' => $santri->full_name 
                    ]);
                }

            } else {
                return response()->json(['status' => 'error', 'message' => 'Wajah tidak dikenali.']);
            }

        } catch (\Exception $e) {
            @unlink($filePath);
            return response()->json([
                'status' => 'error', 
                'message' => 'Gagal koneksi AI: ' . $e->getMessage()
            ], 500);
        }
    }
}