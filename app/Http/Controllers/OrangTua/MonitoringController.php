<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\Absensi;
use App\Models\KesehatanSantri;
use App\Models\Perizinan;
use App\Models\Gate\GateLog;

class MonitoringController extends Controller
{
    private function checkAccess($santri)
    {
        // Pastikan anak ini milik orang tua yang login
        if ($santri->orang_tua_id != Auth::user()->orangTua->id) {
            abort(403, 'Akses ditolak');
        }
    }

    public function index($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);

        // --- LOGIKA STATUS TERKINI ---
        $status = [
            'text' => 'Di Pondok',
            'class' => 'bg-emerald-500',
            'icon' => 'home',
            'desc' => 'Aktivitas normal'
        ];

        // 1. Cek Sedang Keluar Gerbang?
        $isOut = GateLog::where('santri_id', $id)->whereNull('in_time')->exists();
        if ($isOut) {
            $status = ['text' => 'Sedang Diluar', 'class' => 'bg-blue-500', 'icon' => 'logout', 'desc' => 'Tercatat keluar gerbang'];
        }

        // 2. Cek Sedang Izin?
        $isIzin = Perizinan::where('santri_id', $id)
            ->where('status', 'disetujui')
            ->whereDate('tgl_mulai', '<=', now())
            ->whereDate('tgl_selesai_rencana', '>=', now())
            ->exists();
        if ($isIzin) {
            $status = ['text' => 'Sedang Izin', 'class' => 'bg-purple-500', 'icon' => 'document-text', 'desc' => 'Memiliki izin resmi'];
        }

        // 3. Cek Sedang Sakit?
        $isSakit = KesehatanSantri::where('santri_id', $id)
            ->where('status', '!=', 'sembuh')
            ->exists();
        if ($isSakit) {
            $status = ['text' => 'Sedang Sakit', 'class' => 'bg-red-500', 'icon' => 'heart', 'desc' => 'Tercatat di UKS'];
        }

        // 4. Ambil Absensi Terakhir (Apapun itu)
        $lastAbsen = Absensi::where('santri_id', $id)->latest('waktu_catat')->first();

        return view('orangtua.monitoring.index', compact('santri', 'status', 'lastAbsen'));
    }

    // SUB MENU
    public function kesehatan($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);
        $history = KesehatanSantri::where('santri_id', $id)->latest()->paginate(10);
        return view('orangtua.monitoring.kesehatan', compact('santri', 'history'));
    }

    public function izin($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);
        $history = Perizinan::where('santri_id', $id)->latest()->paginate(10);
        return view('orangtua.monitoring.izin', compact('santri', 'history'));
    }

    public function gerbang($id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);
        $history = GateLog::where('santri_id', $id)->latest('out_time')->paginate(10);
        return view('orangtua.monitoring.gerbang', compact('santri', 'history'));
    }

    public function absensi(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);
        $this->checkAccess($santri);
        
        $kategori = $request->get('kategori', 'jamaah'); // Default sholat
        
        $query = Absensi::where('santri_id', $id)->latest('waktu_catat');
        
        if ($kategori == 'jamaah') {
            $query->where('kategori', 'like', 'jamaah_%');
        } elseif ($kategori == 'asrama') {
            $query->where('kategori', 'asrama');
        } elseif ($kategori == 'kegiatan') {
            $query->whereIn('kategori', ['kegiatan', 'kegiatan_khusus']);
        }

        $history = $query->paginate(15);

        return view('orangtua.monitoring.absensi', compact('santri', 'history', 'kategori'));
    }
}