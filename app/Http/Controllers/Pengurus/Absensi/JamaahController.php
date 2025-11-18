<?php

namespace App\Http\Controllers\Pengurus\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\Absensi;
use App\Models\Haid;
use Carbon\Carbon;

class JamaahController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    // 1. DASHBOARD JAMAAH
    public function index()
    {
        $pondokId = $this->getPondokId();
        $today = Carbon::today();
        $sholatList = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
        
        // 1. Ambil Santri Aktif (Eager Load Status)
        $santris = Santri::where('pondok_id', $pondokId)
            ->where('status', 'active')
            ->with(['riwayatKesehatan' => function($q) {
                $q->where('status', '!=', 'sembuh');
            }, 'perizinans' => function($q) use ($today) {
                $q->where('status', 'disetujui')
                  ->whereDate('tgl_mulai', '<=', $today)
                  ->whereDate('tgl_selesai_rencana', '>=', $today);
            }, 'absensis' => function($q) use ($today) {
                // Ambil semua absen hari ini yang terkait sholat
                $q->whereDate('tanggal', $today)
                  ->whereIn('nama_kegiatan', ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya']);
            }])
            ->orderBy('full_name')
            ->get();

        // 2. Struktur Data Statistik Lengkap
        $dataSholat = [];

        foreach ($sholatList as $sholat) {
            // Inisialisasi Struktur Per Sholat
            $dataSholat[$sholat] = [
                'Laki-laki' => ['wajib' => 0, 'hadir' => 0, 'persen' => 0, 'list_hadir' => [], 'list_belum' => []],
                'Perempuan' => ['wajib' => 0, 'hadir' => 0, 'persen' => 0, 'list_hadir' => [], 'list_belum' => []],
                'total_hadir' => 0 // Untuk tampilan awal di card
            ];
        }

        // 3. Loop Santri & Masukkan ke Slot
        foreach ($santris as $s) {
            $gender = $s->jenis_kelamin;
            if (!in_array($gender, ['Laki-laki', 'Perempuan'])) continue;

            $isSakit = $s->riwayatKesehatan->isNotEmpty();
            $isIzin = $s->perizinans->isNotEmpty();

            // Loop setiap waktu sholat untuk santri ini
            foreach ($sholatList as $sholat) {
                
                // Jika Sakit/Izin, skip dari kewajiban sholat ini
                if ($isSakit || $isIzin) continue;

                // Tambah Wajib Hadir
                $dataSholat[$sholat][$gender]['wajib']++;

                // Cek Kehadiran (Pakai contains agar tidak query ulang)
                // Kita cek kolom 'nama_kegiatan' yang menyimpan nama sholat (Subuh, Dzuhur, dst)
                $isHadir = $s->absensis->contains('nama_kegiatan', $sholat);

                if ($isHadir) {
                    $dataSholat[$sholat][$gender]['hadir']++;
                    $dataSholat[$sholat]['total_hadir']++;
                    $dataSholat[$sholat][$gender]['list_hadir'][] = [
                        'nama' => $s->full_name,
                        'nis' => $s->nis
                    ];
                } else {
                    $dataSholat[$sholat][$gender]['list_belum'][] = [
                        'nama' => $s->full_name,
                        'nis' => $s->nis
                    ];
                }
            }
        }

        // 4. Hitung Persentase Akhir
        foreach ($sholatList as $sholat) {
            foreach (['Laki-laki', 'Perempuan'] as $gender) {
                $wajib = $dataSholat[$sholat][$gender]['wajib'];
                $hadir = $dataSholat[$sholat][$gender]['hadir'];
                $dataSholat[$sholat][$gender]['persen'] = $wajib > 0 ? round(($hadir / $wajib) * 100) : 0;
            }
        }

        // Info Haid (Tetap Sama)
        $sedangHaid = \App\Models\Haid::where('pondok_id', $pondokId)->whereNull('tgl_selesai')->count();

        return view('pengurus.absensi.jamaah.index', compact('dataSholat', 'sholatList', 'sedangHaid'));
    }

    // 2. HALAMAN MANAJEMEN HAID
    public function haidIndex(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // List yang SEDANG HAID
        $activeHaid = Haid::where('pondok_id', $pondokId)
            ->whereNull('tgl_selesai')
            ->with('santri')
            ->get();
            
        // Untuk Form Tambah (Hanya Santri Putri yang TIDAK sedang haid)
        $santriPutri = Santri::where('pondok_id', $pondokId)
            ->where('status', 'active')
            ->where('jenis_kelamin', 'Perempuan')
            ->whereDoesntHave('haidAktif') // Filter yang bersih
            ->orderBy('full_name')
            ->get();

        return view('pengurus.absensi.jamaah.haid', compact('activeHaid', 'santriPutri'));
    }

    // 3. SIMPAN DATA HAID BARU
    public function haidStore(Request $request)
    {
        $request->validate(['santri_id' => 'required|exists:santris,id']);
        
        Haid::create([
            'pondok_id' => $this->getPondokId(),
            'santri_id' => $request->santri_id,
            'tgl_mulai' => now(),
            'catatan' => $request->catatan
        ]);

        return back()->with('success', 'Santri ditandai sedang haid.');
    }

    // 4. SELESAI HAID (SUCI)
    public function haidFinish($id)
    {
        $haid = Haid::where('id', $id)->where('pondok_id', $this->getPondokId())->firstOrFail();
        $haid->update(['tgl_selesai' => now()]);

        return back()->with('success', 'Status haid selesai (Suci).');
    }

    // 5. SCANNER SHOLAT
    public function scan()
    {
        return view('pengurus.absensi.jamaah.scan');
    }

    public function processScan(Request $request)
    {
        $pondokId = $this->getPondokId();
        $rfid = $request->rfid;
        $sholat = $request->sholat; // subuh, dzuhur, dst

        if (!$sholat) return response()->json(['status' => 'error', 'message' => 'Pilih Waktu Sholat!'], 400);

        // Cari Santri
        $santri = Santri::where('pondok_id', $pondokId)
            ->where(fn($q) => $q->where('rfid_uid', $rfid)->orWhere('qrcode_token', $rfid))
            ->with('haidAktif') // Eager load cek haid
            ->first();

        if (!$santri) return response()->json(['status' => 'error', 'message' => 'Kartu tidak terdaftar'], 404);

        // --- CEK KHUSUS PEREMPUAN: SEDANG HAID? ---
        if ($santri->jenis_kelamin == 'Perempuan' && $santri->haidAktif) {
            return response()->json([
                'status' => 'error', // Kita lempar error/warning agar merah
                'message' => 'Sedang Haid',
                'subtext' => 'Tidak wajib sholat',
                'santri' => $santri->full_name
            ]);
        }

        // Cek Duplikat
        $kategori = 'jamaah_' . strtolower($sholat);
        $exists = Absensi::where('santri_id', $santri->id)
            ->where('kategori', $kategori) // Hati-hati dengan ENUM di database, pastikan sudah ditambahkan
            ->whereDate('tanggal', now())
            ->exists();
        
        if ($exists) return response()->json(['status' => 'warning', 'message' => 'Sudah absen', 'santri' => $santri->full_name]);

        // Simpan Absen (Gunakan 'jamaah_subuh', 'jamaah_dzuhur' dst sesuai enum database Anda)
        // *Catatan: Pastikan kolom ENUM di database sudah support 'jamaah_subuh' dll. 
        // Jika belum, kita pakai 'kegiatan_khusus' dulu sebagai fallback atau tambah enum baru.*
        
        // Mari kita asumsikan ENUM database Anda terbatas (subuh/maghrib/isya). 
        // Jika Dzuhur/Ashar belum ada di ENUM, kita simpan nama_kegiatan saja.
        
        // Logic Mapping Kategori (Sesuai Database Existing)
        $kategoriDb = 'kegiatan_khusus'; 
        if (in_array($kategori, ['jamaah_subuh', 'jamaah_maghrib', 'jamaah_isya'])) {
            $kategoriDb = $kategori;
        }
        
        Absensi::create([
            'pondok_id' => $pondokId,
            'santri_id' => $santri->id,
            'kategori' => $kategoriDb, 
            'nama_kegiatan' => ucfirst($sholat), // Simpan nama sholatnya
            'status' => 'hadir',
            'tanggal' => now()->toDateString(),
            'waktu_catat' => now(),
            'pencatat_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Hadir', 'santri' => $santri->full_name]);
    }
}