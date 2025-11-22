<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan\KartuUjianTemplate;
use Illuminate\Http\Request;

class KartuUjianTemplateController extends Controller
{
    private function getPondokId()
    {
        $user = auth()->user();
        return $user->pondokStaff ? $user->pondokStaff->pondok_id : $user->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();
        $templates = KartuUjianTemplate::where('pondok_id', $pondokId)->latest()->paginate(10);
        return view('pendidikan.admin.kartu.template.index', compact('templates'));
    }

    public function create()
    {
        return view('pendidikan.admin.kartu.template.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required',
            'ukuran_kertas' => 'required',
        ]);

        KartuUjianTemplate::create([
            'pondok_id' => $this->getPondokId(),
            'nama_template' => $request->nama_template,
            'konten_html' => $request->konten_html,
            'ukuran_kertas' => $request->ukuran_kertas,
            'orientasi' => $request->orientasi,
            'margin_top' => $request->margin_top ?? 5,
            'margin_bottom' => $request->margin_bottom ?? 5,
            'margin_left' => $request->margin_left ?? 5,
            'margin_right' => $request->margin_right ?? 5,
        ]);

        return redirect()->route('pendidikan.admin.kartu-template.index')->with('success', 'Template kartu berhasil disimpan.');
    }

    public function edit($id)
    {
        $template = KartuUjianTemplate::where('pondok_id', $this->getPondokId())->findOrFail($id);
        return view('pendidikan.admin.kartu.template.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = KartuUjianTemplate::where('pondok_id', $this->getPondokId())->findOrFail($id);
        $template->update($request->except('_token', '_method'));
        return redirect()->route('pendidikan.admin.kartu-template.index')->with('success', 'Template diperbarui.');
    }

    public function destroy($id)
    {
        $template = KartuUjianTemplate::where('pondok_id', $this->getPondokId())->findOrFail($id);
        $template->delete();
        return back()->with('success', 'Template dihapus.');
    }
}