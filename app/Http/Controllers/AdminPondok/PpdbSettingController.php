<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\PpdbSetting;
use Illuminate\Http\Request;
use App\Models\PpdbBiaya;

class PpdbSettingController extends Controller
{
    // Menampilkan halaman pengaturan
    public function index()
    {
        // Ambil data setting, urutkan dari yang terbaru
        $settings = PpdbSetting::latest()->get();
        return view('adminpondok.ppdb.setting.index', compact('settings'));
    }

    // Menampilkan form buat gelombang baru
    public function create()
    {
        return view('adminpondok.ppdb.setting.create');
    }

    // Menyimpan data gelombang baru
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string',
            'nama_gelombang' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'biaya_pendaftaran' => 'required|numeric|min:0',
        ]);

        // Jika user mencentang "Aktifkan Sekarang", maka yang lain harus non-aktif
        if ($request->has('is_active')) {
            PpdbSetting::where('is_active', true)->update(['is_active' => false]);
        }

        PpdbSetting::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'nama_gelombang' => $request->nama_gelombang,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'biaya_pendaftaran' => $request->biaya_pendaftaran,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('adminpondok.ppdb.setting.index')->with('success', 'Gelombang pendaftaran berhasil dibuat.');
    }

    // Mengubah status aktif/non-aktif
    public function toggleStatus($id)
    {
        $setting = PpdbSetting::findOrFail($id);
        
        if (!$setting->is_active) {
            // Matikan yang lain dulu agar hanya 1 yang aktif
            PpdbSetting::where('is_active', true)->update(['is_active' => false]);
            $setting->update(['is_active' => true]);
            $message = 'Gelombang pendaftaran diaktifkan.';
        } else {
            $setting->update(['is_active' => false]);
            $message = 'Gelombang pendaftaran dinonaktifkan.';
        }

        return back()->with('success', $message);
    }
    
    // Menghapus data (opsional)
    public function destroy($id)
    {
        PpdbSetting::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    // Menampilkan halaman kelola biaya
    public function manageBiaya($id)
    {
        $setting = PpdbSetting::with('biayas')->findOrFail($id);
        return view('adminpondok.ppdb.setting.biaya', compact('setting'));
    }

    // Menyimpan item biaya baru
    public function storeBiaya(Request $request, $id)
    {
        $request->validate([
            'jenjang' => 'required|string',
            'nama_biaya' => 'required|string',
            'nominal' => 'required|numeric|min:0',
        ]);

        PpdbBiaya::create([
            'ppdb_setting_id' => $id,
            'jenjang' => $request->jenjang,
            'nama_biaya' => $request->nama_biaya,
            'nominal' => $request->nominal,
        ]);

        return back()->with('success', 'Item biaya berhasil ditambahkan.');
    }

    // Hapus item biaya
    public function destroyBiaya($id)
    {
        PpdbBiaya::findOrFail($id)->delete();
        return back()->with('success', 'Item biaya dihapus.');
    }
}