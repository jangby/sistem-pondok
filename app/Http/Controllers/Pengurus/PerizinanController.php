<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perizinan;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PerizinanController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();

        // --- KPI ---
        $sedangDiluar = Perizinan::where('pondok_id', $pondokId)
            ->where('status', 'disetujui') // Status aktif
            ->count();

        $izinHariIni = Perizinan::where('pondok_id', $pondokId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $terlambat = Perizinan::where('pondok_id', $pondokId)
            ->where('status', 'disetujui')
            ->where('tgl_selesai_rencana', '<', now()) // Sudah lewat waktu
            ->count();

        // --- LIST DATA ---
        // Hanya tampilkan yang sedang izin (aktif) atau terlambat
        $query = Perizinan::where('pondok_id', $pondokId)
            ->whereIn('status', ['disetujui', 'terlambat'])
            ->with('santri')
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('santri', fn($q) => $q->where('full_name', 'like', "%$search%"));
        }

        $izins = $query->paginate(10);

        return view('pengurus.perizinan.index', compact('sedangDiluar', 'izinHariIni', 'terlambat', 'izins'));
    }

    public function create()
    {
        return view('pengurus.perizinan.scan');
    }

    public function processScan(Request $request)
    {
        $request->validate(['kode_kartu' => 'required']);
        $pondokId = $this->getPondokId();

        $santri = Santri::where('pondok_id', $pondokId)
            ->where(function($q) use ($request) {
                $q->where('rfid_uid', $request->kode_kartu)
                  ->orWhere('qrcode_token', $request->kode_kartu);
            })->first();

        if (!$santri) return back()->with('error', 'Kartu tidak dikenali.');

        // Cek apakah santri sedang izin (belum kembali)
        $isBusy = Perizinan::where('santri_id', $santri->id)
            ->whereIn('status', ['disetujui', 'terlambat'])
            ->first();

        if ($isBusy) {
            // Jika sedang izin, arahkan ke halaman untuk "Konfirmasi Kembali"
            return redirect()->route('pengurus.perizinan.edit', $isBusy->id)
                ->with('info', 'Santri ini tercatat sedang izin keluar. Silakan konfirmasi kepulangan.');
        }

        return view('pengurus.perizinan.form', compact('santri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required',
            'jenis_izin' => 'required|in:pulang,keluar_sebentar',
            'alasan' => 'required',
            // Validasi Waktu
            'durasi_jam' => 'required_if:jenis_izin,keluar_sebentar',
            'tgl_kembali' => 'required_if:jenis_izin,pulang',
        ]);

        $mulai = now();
        $selesai = now();

        if ($request->jenis_izin == 'keluar_sebentar') {
            // Hitung jam
            $selesai = $mulai->copy()->addHours($request->durasi_jam);
        } else {
            // Hitung hari (sampai jam 18:00 di tanggal tersebut)
            $selesai = Carbon::parse($request->tgl_kembali)->setHour(18)->setMinute(0);
        }

        Perizinan::create([
            'pondok_id' => $this->getPondokId(),
            'santri_id' => $request->santri_id,
            'jenis_izin' => $request->jenis_izin,
            'alasan' => $request->alasan,
            'tgl_mulai' => $mulai,
            'tgl_selesai_rencana' => $selesai,
            'status' => 'disetujui', // Default langsung disetujui karena input di tempat
            'disetujui_oleh' => Auth::id(),
        ]);

        return redirect()->route('pengurus.perizinan.index')->with('success', 'Izin berhasil dicatat.');
    }

    public function show($id)
    {
        $izin = Perizinan::with('santri', 'penyetuju')->findOrFail($id);
        if($izin->pondok_id != $this->getPondokId()) abort(404);
        return view('pengurus.perizinan.show', compact('izin'));
    }

    public function edit($id)
    {
        $izin = Perizinan::with('santri')->findOrFail($id);
        if($izin->pondok_id != $this->getPondokId()) abort(404);
        return view('pengurus.perizinan.edit', compact('izin'));
    }

    public function update(Request $request, $id)
    {
        // Proses "Santri Kembali"
        $izin = Perizinan::findOrFail($id);
        
        $izin->update([
            'status' => 'kembali',
            'tgl_kembali_realisasi' => now(),
        ]);

        return redirect()->route('pengurus.perizinan.index')->with('success', 'Santri telah dicatat kembali ke pondok.');
    }

    /**
     * Halaman Riwayat Izin (Yang sudah selesai/kembali)
     */
    public function history(Request $request)
    {
        $pondokId = $this->getPondokId();

        // Ambil data yang statusnya SUDAH SELESAI (kembali atau ditolak)
        $query = Perizinan::where('pondok_id', $pondokId)
            ->whereIn('status', ['kembali', 'ditolak'])
            ->with('santri')
            ->latest('tgl_kembali_realisasi'); // Urutkan dari yang baru balik

        // Pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('santri', fn($q) => $q->where('full_name', 'like', "%$search%"));
        }

        // Filter Tanggal
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $riwayat = $query->paginate(15)->withQueryString();

        return view('pengurus.perizinan.history', compact('riwayat'));
    }
}