<?php

namespace App\Http\Controllers\Penulis;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookChapterController extends Controller
{
    public function store(Request $request, Book $book)
    {
        // Validasi kepemilikan buku
        if ($book->pondok_id !== Auth::user()->pondok_id) abort(403);

        $request->validate(['title' => 'required|string|max:255']);

        // Cari urutan terakhir
        $lastSequence = $book->chapters()->max('sequence') ?? 0;

        BookChapter::create([
            'book_id' => $book->id,
            'title' => $request->title,
            'sequence' => $lastSequence + 1
        ]);

        return redirect()->back()->with('success', 'Bab berhasil ditambahkan');
    }

    public function update(Request $request, BookChapter $chapter)
    {
        if ($chapter->book->pondok_id !== Auth::user()->pondok_id) abort(403);

        $request->validate(['title' => 'required|string|max:255']);
        $chapter->update(['title' => $request->title]);

        return redirect()->back()->with('success', 'Judul Bab diperbarui');
    }

    public function destroy(BookChapter $chapter)
    {
        if ($chapter->book->pondok_id !== Auth::user()->pondok_id) abort(403);
        $chapter->delete();

        return redirect()->back()->with('success', 'Bab dihapus');
    }
}