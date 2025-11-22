<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan\RaporTemplate;
use Illuminate\Http\Request;

class RaporTemplateController extends Controller
{
    /**
     * Helper untuk mendapatkan Pondok ID dari relasi Staff
     */
    private function getPondokId()
    {
        $user = auth()->user();
        
        // Cek apakah user punya data staff
        if ($user->pondokStaff) {
            return $user->pondokStaff->pondok_id;
        }
        
        // Fallback jika user mungkin admin dengan kolom langsung (opsional)
        return $user->pondok_id; 
    }

    public function index()
    {
        $pondokId = $this->getPondokId();

        $templates = RaporTemplate::where('pondok_id', $pondokId)
            ->latest()
            ->paginate(10);

        return view('pendidikan.admin.rapor.template.index', compact('templates'));
    }

    public function create()
    {
        return view('pendidikan.admin.rapor.template.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'ukuran_kertas' => 'required',
            'orientasi' => 'required',
        ]);

        $pondokId = $this->getPondokId();

        // Validasi tambahan agar tidak error jika pondok_id tidak ditemukan
        if (!$pondokId) {
            return back()->withErrors(['msg' => 'Akun Anda tidak terhubung dengan data pondok.']);
        }

        RaporTemplate::create([
            'pondok_id' => $pondokId, // <-- PERBAIKAN DI SINI
            'nama_template' => $request->nama_template,
            'konten_html' => $request->konten_html,
            'ukuran_kertas' => $request->ukuran_kertas,
            'orientasi' => $request->orientasi,
            'margin_top' => $request->margin_top ?? 10,
            'margin_bottom' => $request->margin_bottom ?? 10,
            'margin_left' => $request->margin_left ?? 10,
            'margin_right' => $request->margin_right ?? 10,
        ]);

        return redirect()->route('pendidikan.admin.rapor-template.index')
            ->with('success', 'Template rapor berhasil dibuat.');
    }

    public function edit($id)
    {
        $pondokId = $this->getPondokId();
        $template = RaporTemplate::where('pondok_id', $pondokId)->findOrFail($id);
        
        return view('pendidikan.admin.rapor.template.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        $template = RaporTemplate::where('pondok_id', $pondokId)->findOrFail($id);
        
        $template->update([
            'nama_template' => $request->nama_template,
            'konten_html' => $request->konten_html,
            'ukuran_kertas' => $request->ukuran_kertas,
            'orientasi' => $request->orientasi,
            'margin_top' => $request->margin_top,
            'margin_bottom' => $request->margin_bottom,
            'margin_left' => $request->margin_left,
            'margin_right' => $request->margin_right,
        ]);

        return redirect()->route('pendidikan.admin.rapor-template.index')
            ->with('success', 'Template rapor berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $pondokId = $this->getPondokId();
        $template = RaporTemplate::where('pondok_id', $pondokId)->findOrFail($id);
        $template->delete();
        
        return redirect()->back()->with('success', 'Template dihapus.');
    }
}