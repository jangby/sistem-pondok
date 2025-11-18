<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KesehatanSantri;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <-- Pastikan ini ada
use App\Models\Perizinan;

class UksController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * 1. Halaman Utama (Dashboard UKS - Hanya yang Sedang Sakit)
     */
    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // --- A. HITUNG KPI (Statistik) ---
        
        // 1. Total Kasus Baru Hari Ini
        $sakitHariIni = KesehatanSantri::where('pondok_id', $pondokId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // 2. Total Pasien Aktif (Belum Sembuh)
        $pasienAktif = KesehatanSantri::where('pondok_id', $pondokId)
            ->where('status', '!=', 'sembuh')
            ->count();

        // 3. Total Sembuh Hari Ini
        $sembuhHariIni = KesehatanSantri::where('pondok_id', $pondokId)
            ->whereDate('tanggal_sembuh', Carbon::today())
            ->count();

        // --- B. DATA LIST (Hanya yang BELUM SEMBUH) ---
        $query = KesehatanSantri::where('pondok_id', $pondokId)
                    ->where('status', '!=', 'sembuh') // Filter Status
                    ->with('santri')
                    ->latest();

        // Fitur Pencarian (Hanya cari di antara yang sakit)
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('santri', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        }

        // Ubah nama variabel dari $riwayat ke $sedangSakit agar sesuai View
        $sedangSakit = $query->paginate(10);

        // Kirim SEMUA variabel ke View
        return view('pengurus.uks.index', compact('sakitHariIni', 'pasienAktif', 'sembuhHariIni', 'sedangSakit'));
    }

    /**
     * Halaman Riwayat Lengkap (Arsip Semua Data)
     */
    public function history(Request $request)
    {
        $pondokId = $this->getPondokId();

        $query = KesehatanSantri::where('pondok_id', $pondokId)
                    ->with('santri')
                    ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('santri', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $riwayat = $query->paginate(15)->withQueryString();

        return view('pengurus.uks.history', compact('riwayat'));
    }

    // ... (Method create, processScan, store, show, edit, update biarkan sama) ...
    // Pastikan method-method tersebut tetap ada di bawah sini.
    
    public function create()
    {
        return view('pengurus.uks.scan');
    }

    public function processScan(Request $request)
    {
        $request->validate(['kode_kartu' => 'required|string']);
        $pondokId = $this->getPondokId();
        $kode = $request->kode_kartu;

        $santri = Santri::where('pondok_id', $pondokId)
                    ->where(function($q) use ($kode) {
                        $q->where('rfid_uid', $kode)
                          ->orWhere('qrcode_token', $kode);
                    })
                    ->first();

        if (!$santri) {
            return back()->with('error', 'Kartu tidak dikenali.');
        }

        return view('pengurus.uks.form', compact('santri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'keluhan' => 'required|string',
            'tindakan' => 'nullable|string',
            'status' => 'required|in:sakit_ringan,dirawat_di_asrama,rujuk_rs,sembuh',
        ]);

        $pondokId = $this->getPondokId();

        // 1. Simpan Data UKS
        $uks = KesehatanSantri::create([
            'pondok_id' => $pondokId,
            'santri_id' => $request->santri_id,
            'keluhan' => $request->keluhan,
            'tindakan' => $request->tindakan,
            'status' => $request->status,
            'tanggal_sakit' => now(),
        ]);

        // 2. LOGIKA OTOMATIS IZIN (Jika Rujuk RS)
        if ($request->status == 'rujuk_rs') {
            Perizinan::create([
                'pondok_id' => $pondokId,
                'santri_id' => $request->santri_id,
                'jenis_izin' => 'sakit_pulang',
                'alasan' => 'Rujuk Medis: ' . $request->keluhan,
                'tgl_mulai' => now(),
                'tgl_selesai_rencana' => now()->addDays(1), // Default 1 hari dulu
                'status' => 'disetujui', // Langsung setujui karena darurat
                'disetujui_oleh' => Auth::id(),
            ]);
        }

        return redirect()->route('pengurus.uks.index')->with('success', 'Data kesehatan dicatat' . ($request->status == 'rujuk_rs' ? ' & Izin Pulang otomatis dibuat.' : '.'));
    }

    public function show($id)
    {
        $uks = KesehatanSantri::with('santri')->findOrFail($id);
        if ($uks->pondok_id != $this->getPondokId()) abort(404);
        return view('pengurus.uks.show', compact('uks'));
    }

    public function edit($id)
    {
        $uks = KesehatanSantri::with('santri')->findOrFail($id);
        if ($uks->pondok_id != $this->getPondokId()) abort(404);
        return view('pengurus.uks.edit', compact('uks'));
    }

    public function update(Request $request, $id)
    {
        $uks = KesehatanSantri::findOrFail($id);
        if ($uks->pondok_id != $this->getPondokId()) abort(404);

        $request->validate([
            'keluhan' => 'required|string',
            'tindakan' => 'nullable|string',
            'status' => 'required|in:sakit_ringan,dirawat_di_asrama,rujuk_rs,sembuh',
        ]);

        $data = [
            'keluhan' => $request->keluhan,
            'tindakan' => $request->tindakan,
            'status' => $request->status,
        ];

        if ($request->status == 'sembuh' && !$uks->tanggal_sembuh) {
            $data['tanggal_sembuh'] = now();
        } elseif ($request->status != 'sembuh') {
            $data['tanggal_sembuh'] = null;
        }

        $uks->update($data);

        return redirect()->route('pengurus.uks.index')->with('success', 'Data kesehatan diperbarui.');
    }
}