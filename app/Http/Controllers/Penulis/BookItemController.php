<?php

namespace App\Http\Controllers\Penulis;

use App\Http\Controllers\Controller;
use App\Models\BookChapter;
use App\Models\BookItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookItemController extends Controller
{
    public function create(BookChapter $chapter)
    {
        // Load info buku untuk breadcrumb/tombol kembali
        $chapter->load('book');
        if ($chapter->book->pondok_id !== Auth::user()->pondok_id) abort(403);
        
        return view('penulis.items.create', compact('chapter'));
    }

    public function store(Request $request, BookChapter $chapter)
    {
        if ($chapter->book->pondok_id !== Auth::user()->pondok_id) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'arabic_content' => 'nullable|string',
            'translation_content' => 'nullable|string',
        ]);

        $lastSequence = $chapter->items()->max('sequence') ?? 0;

        BookItem::create([
            'chapter_id' => $chapter->id,
            'title' => $request->title,
            'arabic_content' => $request->arabic_content,
            'translation_content' => $request->translation_content,
            'type' => 'doa', // Default doa
            'sequence' => $lastSequence + 1
        ]);

        // Redirect kembali ke halaman Show Buku (Editor Utama)
        return redirect()->route('penulis.books.show', $chapter->book_id)
                         ->with('success', 'Doa berhasil ditambahkan ke ' . $chapter->title);
    }

    public function edit(BookItem $item)
    {
        if ($item->chapter->book->pondok_id !== Auth::user()->pondok_id) abort(403);
        return view('penulis.items.edit', compact('item'));
    }

    public function update(Request $request, BookItem $item)
    {
        if ($item->chapter->book->pondok_id !== Auth::user()->pondok_id) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'arabic_content' => 'nullable|string',
            'translation_content' => 'nullable|string',
        ]);

        $item->update($request->only('title', 'arabic_content', 'translation_content'));

        return redirect()->route('penulis.books.show', $item->chapter->book_id)
                         ->with('success', 'Konten berhasil diperbarui');
    }

    public function destroy(BookItem $item)
    {
        if ($item->chapter->book->pondok_id !== Auth::user()->pondok_id) abort(403);
        $bookId = $item->chapter->book_id;
        $item->delete();

        return redirect()->route('penulis.books.show', $bookId)->with('success', 'Item dihapus');
    }
}