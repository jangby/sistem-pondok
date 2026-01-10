<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Karya Tulis Saya') }}
            </h2>
            <a href="{{ route('penulis.books.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm shadow hover:bg-indigo-700 transition">
                + Tulis Buku Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if($books->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center">
                    <p class="text-gray-500 text-lg">Belum ada karya tulis.</p>
                    <p class="text-gray-400 text-sm mb-4">Mulai menulis kitab doa atau novel Anda sekarang.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($books as $book)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full">
                        <div class="h-48 bg-gray-200 w-full object-cover flex items-center justify-center text-gray-400">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/'.$book->cover_image) }}" class="w-full h-full object-cover" alt="Cover">
                            @else
                                <span class="text-4xl"><i class="fas fa-book"></i> 📖</span>
                            @endif
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="text-xs text-indigo-500 font-bold tracking-wide uppercase mb-1">
                                {{ $book->status == 'draft' ? 'Draft' : 'Terbit' }}
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $book->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4">Oleh: {{ $book->author_name }}</p>
                            
                            <div class="mt-auto flex justify-between items-center pt-4 border-t border-gray-100">
                                <span class="text-xs text-gray-500">{{ $book->chapters_count ?? 0 }} Bab</span>
                                <a href="{{ route('penulis.books.show', $book->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                                    Kelola Isi &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>