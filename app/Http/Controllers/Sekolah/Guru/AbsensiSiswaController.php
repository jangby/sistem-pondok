<?php
namespace App\Http\Controllers\Sekolah\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah\AbsensiPelajaran;
use App\Models\Sekolah\AbsensiSiswaPelajaran;
use App\Models\KesehatanSantri; //
use App\Models\Perizinan; //
use App\Models\Santri; //
use App\Jobs\ProcessSiswaAbsensi; // <-- IMPORT JOB BARU
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AbsensiSiswaController extends Controller
{
    // Helper Keamanan
    // Helper Keamanan
    private function checkOwnership(AbsensiPelajaran $absensiPelajaran)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();

        // 1. Cek jika saya adalah Guru Asli
        if ($absensiPelajaran->jadwalPelajaran->guru_user_id == $userId) {
            return true;
        }

        // 2. Cek jika saya adalah Guru Pengganti (yang tercatat di absensi ini)
        if ($absensiPelajaran->guru_pengganti_user_id == $userId) {
            return true;
        }

        // Jika bukan keduanya, blokir
        abort(404, 'Data absensi tidak ditemukan atau Anda tidak memiliki akses.');
    }

    /**
     * Tampilkan halaman input absensi siswa (untuk 1 absensi_pelajaran_id)
     * (Logika ini tetap sama, untuk menampilkan daftar awal)
     */
    public function index(AbsensiPelajaran $absensiPelajaran) 
    {
        $this->checkOwnership($absensiPelajaran);
        
        $absensiPelajaran->load([
            'jadwalPelajaran.mataPelajaran', 
            'jadwalPelajaran.kelas.santris' 
        ]);
        
        $jadwal = $absensiPelajaran->jadwalPelajaran;
        $santris = $jadwal->kelas->santris()->where('status', 'active')->get() ?? collect(); 
        $tanggal = $absensiPelajaran->tanggal;

        // Ambil status yang tersimpan di DB (key: santri_id, value: status)
        $absensiTersimpan = AbsensiSiswaPelajaran::where('absensi_pelajaran_id', $absensiPelajaran->id) 
                            ->pluck('status', 'santri_id');
                            
        $santriList = [];

        foreach ($santris as $santri) {
            $statusIntegrasi = null; 
            
            // 1. Cek UKS
            $isSakit = KesehatanSantri::where('santri_id', $santri->id)
                        ->where('status', '!=', 'sembuh')
                        ->whereDate('tanggal_sakit', '<=', $tanggal)
                        ->where(function ($q) use ($tanggal) {
                            $q->whereDate('tanggal_sembuh', '>=', $tanggal)
                              ->orWhereNull('tanggal_sembuh');
                        })
                        ->exists();
            if ($isSakit) $statusIntegrasi = 'sakit'; // Gunakan huruf kecil

            // 2. Cek Perizinan
            if (!$statusIntegrasi) {
                $isIzin = Perizinan::where('santri_id', $santri->id)
                            ->where('status', 'disetujui')
                            ->whereDate('tgl_mulai', '<=', $tanggal)
                            ->whereDate('tgl_selesai_rencana', '>=', $tanggal)
                            ->exists();
                if ($isIzin) $statusIntegrasi = 'izin'; // Gunakan huruf kecil
            }

            // 3. LOGIKA PENENTUAN STATUS (DIPERBAIKI)
            // Default awal
            $statusAbsensi = 'alpa'; 

            // Cek apakah ada data di database absensi pelajaran
            if (isset($absensiTersimpan[$santri->id])) {
                // Langsung pakai data dari DB karena formatnya sudah 'hadir', 'sakit', dll.
                $statusAbsensi = strtolower($absensiTersimpan[$santri->id]);
            }

            // Jika ada status integrasi (Sakit/Izin dr sistem lain), timpa status manual
            if ($statusIntegrasi) {
                $statusAbsensi = $statusIntegrasi;
            }

            $santriList[] = [
                'santri_id' => $santri->id,
                'full_name' => $santri->full_name,
                'nis' => $santri->nis,
                'status_absensi' => $statusAbsensi, 
            ];
        }
        
        // Sortir agar status Hadir di paling bawah, yang bermasalah di atas
        usort($santriList, function($a, $b) {
            $order = ['sakit' => 1, 'izin' => 2, 'alpa' => 3, 'hadir' => 4];
            
            $valA = $order[$a['status_absensi']] ?? 99;
            $valB = $order[$b['status_absensi']] ?? 99;
            
            return $valA <=> $valB;
        });
        
        return view('sekolah.guru.absensi-siswa.index', compact(
            'absensiPelajaran', 
            'santriList'
        ));
    }

    /**
     * Simpan absensi siswa (via AJAX/Scan)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'absensi_pelajaran_id' => 'required|exists:absensi_pelajarans,id',
            'kartu_id' => 'required|string', // Ini bisa RFID atau QR Token
        ]);
        
        $absensiPelajaran = AbsensiPelajaran::find($validated['absensi_pelajaran_id']); //
        $this->checkOwnership($absensiPelajaran); // Keamanan

        $waktuSekarang = now();
        $tanggal = $absensiPelajaran->tanggal;

        // 1. Cari Santri
        $santri = Santri::where('rfid_uid', $validated['kartu_id']) //
                       ->orWhere('qrcode_token', $validated['kartu_id']) //
                       ->first();
        
        if (!$santri) {
            return response()->json(['status' => 'error', 'message' => 'Kartu tidak terdaftar.'], 404);
        }

        // 2. Validasi: Apakah santri ini ada di kelas ini?
        $jadwal = $absensiPelajaran->jadwalPelajaran; //
        if ($santri->kelas_id != $jadwal->kelas_id) { //
            return response()->json(['status' => 'error', 'message' => 'Santri (' . $santri->full_name . ') tidak terdaftar di kelas ini.'], 422);
        }
        
        // 3. Validasi: Apakah santri ini Sakit atau Izin?
        $isSakit = KesehatanSantri::where('santri_id', $santri->id)->where('status', '!=', 'sembuh')->whereDate('tanggal_sakit', '<=', $tanggal)->where(function ($q) use ($tanggal) { $q->whereDate('tanggal_sembuh', '>=', $tanggal)->orWhereNull('tanggal_sembuh'); })->exists();
        $isIzin = Perizinan::where('santri_id', $santri->id)->where('status', 'disetujui')->whereDate('tgl_mulai', '<=', $tanggal)->whereDate('tgl_selesai_rencana', '>=', $tanggal)->exists();
        
        if ($isSakit) return response()->json(['status' => 'error', 'message' => $santri->full_name . ' tercatat SAKIT (UKS).'], 422);
        if ($isIzin) return response()->json(['status' => 'error', 'message' => $santri->full_name . ' tercatat IZIN (Perizinan).'], 422);

        // 4. Validasi: Apakah sudah diabsen?
        $sudahAbsen = AbsensiSiswaPelajaran::where('absensi_pelajaran_id', $absensiPelajaran->id) //
                                         ->where('santri_id', $santri->id)
                                         ->first();
                                         
        if ($sudahAbsen && $sudahAbsen->status == 'hadir') {
            return response()->json(['status' => 'warning', 'message' => $santri->full_name . ' sudah diabsen hadir.'], 409);
        }

        // 5. Lolos Validasi -> LEMPAR KE JOB (ANTRIAN)
        ProcessSiswaAbsensi::dispatch(
            $absensiPelajaran->id,
            $santri->id,
            $waktuSekarang->format('H:i:s')
        ); //

        // 6. Kirim respon sukses INSTAN ke guru
        return response()->json([
            'status' => 'success',
            'message' => 'Hadir',
            'santri_id' => $santri->id,
            'nama_santri' => $santri->full_name,
            'jam_hadir' => $waktuSekarang->format('H:i:s')
        ]);
    }
}