<?php
namespace App\Http\Controllers\Sekolah\Petugas;
use App\Http\Controllers\Controller;
use App\Models\Perpus\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index()
    {
        $kunjungan = Kunjungan::whereDate('created_at', now())->latest()->get();
        return view('sekolah.petugas.kunjungan.index', compact('kunjungan'));
    }

    public function store(Request $request)
    {
        // Simpan Data
        // Kunjungan::create($request->all());
        return back()->with('success', 'Pengunjung dicatat.');
    }
}