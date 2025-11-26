<?php
namespace App\Http\Controllers\Sekolah\Petugas;
use App\Http\Controllers\Controller;
use App\Models\Perpus\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();
        if ($request->has('search')) {
            $query->where('judul', 'like', '%'.$request->search.'%')
                  ->orWhere('penulis', 'like', '%'.$request->search.'%');
        }
        $bukus = $query->paginate(10);
        return view('sekolah.petugas.buku.index', compact('bukus'));
    }
}