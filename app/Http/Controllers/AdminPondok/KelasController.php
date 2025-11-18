<?php
namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    private function getPondokId() {
        return Auth::user()->pondokStaff->pondok_id;
    }

    public function index()
    {
        // Trait 'BelongsToPondok' di Model Kelas otomatis memfilter
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('adminpondok.kelas.index', compact('kelasList'));
    }

    public function create()
    {
        return view('adminpondok.kelas.create');
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();
        $validated = $request->validate([
            'nama_kelas' => [
                'required', 'string', 'max:255',
                Rule::unique('kelas')->where(fn ($q) => $q->where('pondok_id', $pondokId))
            ],
            'tingkat' => 'nullable|string|max:100',
        ]);
        
        // Trait otomatis mengisi pondok_id
        Kelas::create($validated);
        return redirect()->route('adminpondok.kelas.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    public function edit(Kelas $kela) // 'kela' untuk hindari bentrok
    {
        // Trait otomatis cek kepemilikan
        return view('adminpondok.kelas.edit', ['kelas' => $kela]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $pondokId = $this->getPondokId();
        $validated = $request->validate([
            'nama_kelas' => [
                'required', 'string', 'max:255',
                Rule::unique('kelas')->where(fn ($q) => $q->where('pondok_id', $pondokId))->ignore($kela->id)
            ],
            'tingkat' => 'nullable|string|max:100',
        ]);
        
        $kela->update($validated);
        return redirect()->route('adminpondok.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        // Nanti tambahkan cek jika kelas masih punya santri
        $kela->delete();
        return redirect()->route('adminpondok.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}