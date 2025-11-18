<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Asrama;
use App\Models\Santri;

class ManajemenAsramaController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    public function index()
    {
        return view('pengurus.asrama.index');
    }

    public function list($gender)
    {
        if (!in_array($gender, ['Putra', 'Putri'])) abort(404);
        $jkDb = ($gender == 'Putra') ? 'Laki-laki' : 'Perempuan';

        $pondokId = $this->getPondokId();
        $asramas = Asrama::where('pondok_id', $pondokId)
            ->where('jenis_kelamin', $jkDb)
            ->withCount('penghuni')
            ->get();

        return view('pengurus.asrama.list', compact('asramas', 'gender', 'jkDb'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_asrama' => 'required',
            'komplek' => 'required',
            'ketua_asrama' => 'required', 
            'kapasitas' => 'required|integer|min:1',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan'
        ]);

        Asrama::create([
            'pondok_id' => $this->getPondokId(),
            'nama_asrama' => $request->nama_asrama,
            'komplek' => $request->komplek,
            'ketua_asrama' => $request->ketua_asrama,
            'kapasitas' => $request->kapasitas,
            'jenis_kelamin' => $request->jenis_kelamin
        ]);

        return back()->with('success', 'Asrama berhasil dibuat.');
    }

    public function show($id)
    {
        $asrama = Asrama::with('penghuni')->findOrFail($id);
        if ($asrama->pondok_id != $this->getPondokId()) abort(404);

        // REVISI: HAPUS Filter 'whereNotIn' agar Ketua Asrama tetap muncul di list calon anggota
        // Santri hanya difilter berdasarkan: Active, Gender Sesuai, dan Belum Punya Asrama
        $calonAnggota = Santri::where('pondok_id', $this->getPondokId())
            ->where('status', 'active')
            ->where('jenis_kelamin', $asrama->jenis_kelamin)
            ->whereNull('asrama_id') // Hanya yang belum punya asrama
            ->orderBy('full_name')
            ->get();

        return view('pengurus.asrama.show', compact('asrama', 'calonAnggota'));
    }

    public function settings($id)
    {
        $asrama = Asrama::findOrFail($id);
        if ($asrama->pondok_id != $this->getPondokId()) abort(404);

        // Ambil daftar nama ketua asrama LAIN (agar tidak double job antar gedung)
        $otherKetuaNames = Asrama::where('pondok_id', $this->getPondokId())
            ->where('id', '!=', $id)
            ->pluck('ketua_asrama')
            ->toArray();

        $calonKetua = Santri::where('pondok_id', $this->getPondokId())
            ->where('status', 'active')
            ->where('jenis_kelamin', $asrama->jenis_kelamin)
            ->whereNotIn('full_name', $otherKetuaNames)
            ->orderBy('full_name')
            ->get();

        return view('pengurus.asrama.settings', compact('asrama', 'calonKetua'));
    }

    public function update(Request $request, $id)
    {
        $asrama = Asrama::findOrFail($id);
        if ($asrama->pondok_id != $this->getPondokId()) abort(404);
        
        // Cek duplikasi ketua di asrama lain
        $isTaken = Asrama::where('pondok_id', $this->getPondokId())
            ->where('id', '!=', $id)
            ->where('ketua_asrama', $request->ketua_asrama)
            ->exists();
            
        if ($isTaken) {
             return back()->with('error', 'Gagal! Santri tersebut sudah menjadi ketua di asrama lain.');
        }

        $asrama->update($request->only(['nama_asrama', 'komplek', 'ketua_asrama', 'kapasitas']));
        
        return redirect()->route('pengurus.asrama.show', $id)->with('success', 'Data asrama diperbarui.');
    }

    public function destroy($id)
    {
        $asrama = Asrama::findOrFail($id);
        if ($asrama->pondok_id != $this->getPondokId()) abort(404);

        $asrama->delete();

        $gender = $asrama->jenis_kelamin == 'Laki-laki' ? 'Putra' : 'Putri';
        return redirect()->route('pengurus.asrama.list', $gender)->with('success', 'Asrama berhasil dihapus.');
    }

    public function addMember(Request $request, $id)
    {
        $asrama = Asrama::findOrFail($id);
        
        if ($asrama->penghuni()->count() >= $asrama->kapasitas) {
            return back()->with('error', 'Gagal! Asrama sudah penuh.');
        }

        $santri = Santri::findOrFail($request->santri_id);
        
        // REVISI: HAPUS blokir ketua asrama. 
        // Ketua Asrama BOLEH (dan harus) ditambahkan sebagai anggota agar datanya lengkap.

        $santri->update(['asrama_id' => $asrama->id]);

        return back()->with('success', 'Santri berhasil ditambahkan ke asrama.');
    }

    public function removeMember($santriId)
    {
        $santri = Santri::findOrFail($santriId);
        if ($santri->pondok_id != $this->getPondokId()) abort(404);

        $santri->update(['asrama_id' => null]);

        return back()->with('success', 'Santri dikeluarkan dari asrama.');
    }

    public function downloadPDF()
    {
        $pondokId = $this->getPondokId();
        
        $asramaPutra = Asrama::where('pondok_id', $pondokId)
            ->where('jenis_kelamin', 'Laki-laki')
            ->with(['penghuni' => function($q) { $q->orderBy('full_name'); }])
            ->orderBy('nama_asrama')->get();

        $asramaPutri = Asrama::where('pondok_id', $pondokId)
            ->where('jenis_kelamin', 'Perempuan')
            ->with(['penghuni' => function($q) { $q->orderBy('full_name'); }])
            ->orderBy('nama_asrama')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pengurus.asrama.pdf_data', compact('asramaPutra', 'asramaPutri'));
        return $pdf->stream('Data-Seluruh-Asrama.pdf');
    }
}