<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\KegiatanAkademik;
use App\Models\Sekolah\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanAkademikController extends Controller
{
    private function getSekolah()
    {
        $adminUser = Auth::user();
        $sekolah = $adminUser->sekolahs()->first();
        if (!$sekolah) abort(403, 'Akun tidak terhubung sekolah.');
        return $sekolah;
    }
    
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }
    
    private function checkOwnership(KegiatanAkademik $kegiatanAkademik)
    {
        if ($kegiatanAkademik->sekolah_id != $this->getSekolah()->id) abort(404);
    }
    
    private function getTahunAjaranAktif()
    {
        return TahunAjaran::where('pondok_id', $this->getPondokId())
                            ->where('is_active', true)
                            ->first();
    }

    public function index(Request $request)
    {
        $sekolah = $this->getSekolah();
        $tahunAjaranAktif = $this->getTahunAjaranAktif();
        
        $kegiatanQuery = KegiatanAkademik::where('sekolah_id', $sekolah->id);
        
        if ($tahunAjaranAktif) {
             $kegiatanQuery->where('tahun_ajaran_id', $tahunAjaranAktif->id);
        } else {
            $kegiatanQuery->where('id', -1); 
        }

        // [BARU] Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $kegiatanQuery->where(function($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('tipe', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }
           
        $kegiatans = $kegiatanQuery->orderBy('tanggal_mulai', 'desc')
                                   ->paginate(10)
                                   ->withQueryString();
            
        return view('sekolah.admin.kegiatan-akademik.index', compact('kegiatans', 'tahunAjaranAktif'));
    }

    public function store(Request $request)
    {
        $sekolah = $this->getSekolah();
        $tahunAjaranAktif = $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {
            return back()->withErrors(['msg' => 'Sesi Tahun Ajaran Aktif berakhir.']);
        }

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tipe' => 'required|in:UTS,UAS,Harian,Lainnya',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);
        
        $validated['sekolah_id'] = $sekolah->id;
        $validated['tahun_ajaran_id'] = $tahunAjaranAktif->id;
        
        KegiatanAkademik::create($validated);

        return redirect()->route('sekolah.admin.kegiatan-akademik.index')
                         ->with('success', 'Jadwal Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, KegiatanAkademik $kegiatanAkademik)
    {
        $this->checkOwnership($kegiatanAkademik);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tipe' => 'required|in:UTS,UAS,Harian,Lainnya',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);
        
        $kegiatanAkademik->update($validated);

        return redirect()->route('sekolah.admin.kegiatan-akademik.index')
                         ->with('success', 'Jadwal Kegiatan berhasil diperbarui.');
    }

    public function destroy(KegiatanAkademik $kegiatanAkademik)
    {
        $this->checkOwnership($kegiatanAkademik);
        $kegiatanAkademik->delete();

        return redirect()->route('sekolah.admin.kegiatan-akademik.index')
                         ->with('success', 'Jadwal Kegiatan berhasil dihapus.');
    }
}