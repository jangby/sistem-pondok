<?php
namespace App\Http\Controllers\Sekolah\Petugas;
use App\Http\Controllers\Controller;
use App\Models\Perpus\Buku;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $buku = null;
        if($request->has('kode_buku')) {
            $buku = Buku::where('kode_buku', $request->kode_buku)->first();
        }
        return view('sekolah.petugas.audit.index', compact('buku'));
    }

    public function updateStock(Request $request)
    {
        $buku = Buku::findOrFail($request->buku_id);
        // Update stok fisik (Contoh sederhana)
        $buku->stok = $request->stok_fisik; 
        $buku->save();
        
        return redirect()->route('sekolah.petugas.audit.index')
               ->with('success', 'Stok buku '.$buku->judul.' berhasil diperbarui.');
    }
}