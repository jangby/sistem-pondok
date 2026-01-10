<?php

namespace App\Http\Controllers\Penulis;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Helper untuk mengambil ID Pondok dari tabel Penulis
    private function getPondokId()
    {
        // Mengambil: User Login -> Tabel Penulis -> Kolom pondok_id
        return Auth::user()->penulis->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();

        $books = Book::where('pondok_id', $pondokId)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('penulis.books.index', compact('books'));
    }

    public function create()
    {
        return view('penulis.books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // DISINI KUNCINYA: Ambil pondok_id dari fungsi helper di atas
        $data['pondok_id'] = $this->getPondokId(); 

        $data['status'] = 'draft';

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $data['cover_image'] = $path;
        }

        Book::create($data);

        // Redirect ke dashboard penulis (karena halaman show buku belum ada di kode ini, 
        // nanti bisa diarahkan ke 'penulis.books.show' user setelah dibuat)
        return redirect()->route('penulis.dashboard')->with('success', 'Buku berhasil dibuat.');
    }

    public function show(Book $book)
    {
        // Validasi: Pastikan buku milik pondok si penulis
        if ($book->pondok_id !== $this->getPondokId()) abort(403);

        $book->load('chapters.items');
        return view('penulis.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        if ($book->pondok_id !== $this->getPondokId()) abort(403);
        return view('penulis.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        if ($book->pondok_id !== $this->getPondokId()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['cover_image']);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('penulis.books.show', $book->id)->with('success', 'Buku diperbarui');
    }

    public function destroy(Book $book)
    {
        if ($book->pondok_id !== $this->getPondokId()) abort(403);

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();
        return redirect()->route('penulis.books.index')->with('success', 'Buku dihapus');
    }
}