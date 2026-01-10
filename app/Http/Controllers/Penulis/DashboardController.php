<?php

namespace App\Http\Controllers\Penulis;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookItem;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pondokId = Auth::user()->pondok_id;

        // 1. Ambil Statistik
        $totalBuku = Book::where('pondok_id', $pondokId)->count();
        $bukuTerbit = Book::where('pondok_id', $pondokId)->where('status', 'published')->count();
        $totalDoa = BookItem::whereHas('chapter.book', function($q) use ($pondokId) {
            $q->where('pondok_id', $pondokId);
        })->count();

        // 2. Ambil Buku yang Terakhir Diedit (Misal 4 buku terakhir)
        $recentBooks = Book::where('pondok_id', $pondokId)
            ->withCount(['chapters', 'allItems as items_count']) // Hitung bab & doa
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();

        return view('penulis.dashboard', compact('totalBuku', 'bukuTerbit', 'totalDoa', 'recentBooks'));
    }
}