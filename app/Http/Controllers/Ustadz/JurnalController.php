<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JurnalPendidikan;
use App\Models\Mustawa;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function index()
    {
        $ustadz = Auth::user()->ustadz;
        
        // Cek jika akun belum terhubung data ustadz
        if (!$ustadz) {
            return redirect()->route('dashboard')->with('error', 'Profil Ustadz tidak ditemukan.');
        }

        // Ambil riwayat setoran hari ini milik ustadz tersebut
        $hariIni = JurnalPendidikan::where('ustadz_id', $ustadz->id)
            ->whereDate('tanggal', Carbon::today())
            ->with(['santri', 'santri.mustawa']) // Eager load mustawa agar tidak N+1 query
            ->latest()
            ->get();

        $totalHariIni = $hariIni->count();

        return view('pendidikan.ustadz.jurnal.index', compact('hariIni', 'totalHariIni'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        // PERBAIKAN: Ambil pondok_id dari relasi ustadz, bukan langsung dari user
        $pondokId = $user->ustadz->pondok_id ?? null; 

        if (!$pondokId) {
             return redirect()->back()->with('error', 'Data Pondok tidak ditemukan.');
        }

        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->get();

        $santris = collect();
        $selectedMustawaId = $request->query('mustawa_id');

        if ($selectedMustawaId) {
            $santris = Santri::where('mustawa_id', $selectedMustawaId)
                ->where('status', 'active') // Pastikan status santri aktif sesuai enum di database Anda
                ->orderBy('full_name') // Sesuaikan column nama di tabel santris (biasanya 'nama' atau 'nama_lengkap')
                ->get();
        }

        return view('pendidikan.ustadz.jurnal.create', compact('mustawas', 'santris', 'selectedMustawaId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kategori_hafalan' => 'required|in:quran,hadits,kitab,doa',
            'jenis_setoran' => 'required|in:ziyadah,murojaah',
            'materi' => 'required|string|max:255',
            'start_at' => 'nullable|string|max:50',
            'end_at' => 'nullable|string|max:50',
            'predikat' => 'required|string',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        JurnalPendidikan::create([
            'santri_id' => $request->santri_id,
            'ustadz_id' => Auth::user()->ustadz->id,
            'jenis' => 'hafalan',
            'kategori_hafalan' => $request->kategori_hafalan,
            'jenis_setoran' => $request->jenis_setoran,
            'materi' => $request->materi,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'predikat' => $request->predikat,
            'catatan' => $request->catatan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('ustadz.jurnal.index')->with('success', 'Alhamdulillah, setoran berhasil dicatat!');
    }
}