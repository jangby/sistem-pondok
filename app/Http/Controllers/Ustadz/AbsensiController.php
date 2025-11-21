<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalDiniyah;
use App\Models\AbsensiDiniyah;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function showMenu($jadwal_id)
    {
        $jadwal = JadwalDiniyah::with(['mapel', 'mustawa'])->findOrFail($jadwal_id);
        return view('pendidikan.ustadz.absensi.menu', compact('jadwal'));
    }

    // 2. Halaman Scanner / Input Absensi
    public function create($jadwal_id)
    {
        $jadwal = JadwalDiniyah::with(['mapel', 'mustawa'])->findOrFail($jadwal_id);
        
        // PERBAIKAN FATAL: Gunakan 'mustawa_id' bukan 'class'
        $santris = Santri::where('mustawa_id', $jadwal->mustawa_id) 
                         ->select('id', 'full_name', 'nis', 'rfid_uid', 'status') 
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->get();

        $sudahAbsen = AbsensiDiniyah::where('jadwal_diniyah_id', $jadwal_id)
                                    ->whereDate('tanggal', Carbon::today())
                                    ->exists();

        return view('pendidikan.ustadz.absensi.create', compact('jadwal', 'santris', 'sudahAbsen'));
    }

    // 3. Proses Simpan (Auto Alpha)
    public function store(Request $request, $jadwal_id)
    {
        $jadwal = JadwalDiniyah::findOrFail($jadwal_id);
        $attendanceData = $request->input('attendance', []); 

        DB::beginTransaction();
        try {
            $tanggal = Carbon::today();

            // PERBAIKAN FATAL: Gunakan 'mustawa_id'
            $allSantris = Santri::where('mustawa_id', $jadwal->mustawa_id)
                                ->where('status', 'active')
                                ->pluck('id');

            foreach ($allSantris as $santriId) {
                $status = $attendanceData[$santriId] ?? 'A'; 
                $metode = $request->input('metode', 'manual'); 

                AbsensiDiniyah::updateOrCreate(
                    [
                        'jadwal_diniyah_id' => $jadwal_id,
                        'santri_id' => $santriId,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'status' => $status, 
                        'metode' => $metode,
                        'waktu_scan' => ($status == 'H') ? Carbon::now() : null,
                    ]
                );
            }

            DB::commit();
            return redirect()->route('ustadz.jadwal.index')->with('success', 'Absensi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // 4. Halaman Riwayat (List Tanggal)
    public function history($jadwal_id)
    {
        $jadwal = JadwalDiniyah::with(['mapel', 'mustawa'])->findOrFail($jadwal_id);

        // Grouping berdasarkan tanggal untuk mendapatkan statistik
        $riwayat = AbsensiDiniyah::where('jadwal_diniyah_id', $jadwal_id)
            ->select('tanggal', 
                 DB::raw('count(*) as total_santri'),
                 DB::raw('sum(case when status = "H" then 1 else 0 end) as hadir'),
                 DB::raw('sum(case when status = "I" then 1 else 0 end) as izin'),
                 DB::raw('sum(case when status = "S" then 1 else 0 end) as sakit'),
                 DB::raw('sum(case when status = "A" then 1 else 0 end) as alpa'))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->paginate(10); // Pagination biar ringan jika data sudah banyak

        return view('pendidikan.ustadz.absensi.history', compact('jadwal', 'riwayat'));
    }

    // 5. Detail Riwayat (List Santri per Tanggal)
    public function historyDetail($jadwal_id, $tanggal)
    {
        $jadwal = JadwalDiniyah::with(['mapel', 'mustawa'])->findOrFail($jadwal_id);
        
        // Ambil data detail santri pada tanggal tersebut
        $absensis = AbsensiDiniyah::where('jadwal_diniyah_id', $jadwal_id)
                                  ->whereDate('tanggal', $tanggal)
                                  ->with('santri') // Eager load relasi santri
                                  ->get()
                                  ->sortBy('santri.full_name'); // Urutkan abjad nama

        return view('pendidikan.ustadz.absensi.history-detail', compact('jadwal', 'absensis', 'tanggal'));
    }
}