<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $book->title }}
                </h2>
                <p class="text-sm text-gray-500">Penyusun: {{ $book->author_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('penulis.books.edit', $book->id) }}" class="bg-gray-100 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-200">
                    Edit Info Buku
                </a>
                <button class="bg-indigo-600 text-white px-3 py-2 rounded-lg text-sm shadow hover:bg-indigo-700">
                    <i class="fas fa-print"></i> Preview & Cetak
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-blue-800 font-bold">Struktur Buku</h3>
                    <p class="text-xs text-blue-600">Tambahkan Bab untuk mengelompokkan doa.</p>
                </div>
                
                <form action="{{ route('penulis.chapters.store', $book->id) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="title" placeholder="Nama Bab Baru..." class="text-sm border-gray-300 rounded-lg focus:ring-blue-200" required>
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-700 shadow">
                        + Bab
                    </button>
                </form>
            </div>

            @forelse($book->chapters as $chapter)
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center group">
                        <div class="flex items-center gap-3">
                            <span class="bg-gray-200 text-gray-600 text-xs font-bold px-2 py-1 rounded">BAB {{ $loop->iteration }}</span>
                            <h3 class="font-bold text-gray-800 text-lg">{{ $chapter->title }}</h3>
                        </div>
                        
                        <div class="flex items-center gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                            <form action="{{ route('penulis.chapters.destroy', $chapter->id) }}" method="POST" onsubmit="return confirm('Hapus Bab ini beserta isinya?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs underline">Hapus Bab</button>
                             </form>
                        </div>
                    </div>

                    <div class="p-4 space-y-3 bg-white">
                        @if($chapter->items->count() > 0)
                            @foreach($chapter->items as $item)
                                <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition cursor-move">
                                    <div class="flex items-center gap-3">
                                        <div class="text-gray-400 cursor-grab">⋮⋮</div> <div>
                                            <p class="font-semibold text-gray-800">{{ $item->title }}</p>
                                            <p class="text-xs text-gray-500 truncate w-48">{{ Str::limit($item->translation_content, 50) }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('penulis.items.edit', $item->id) }}" class="text-indigo-600 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-full transition">
                                            ✏️
                                        </a>
                                        <form action="{{ route('penulis.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus doa ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 bg-red-50 hover:bg-red-100 p-2 rounded-full transition">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-sm text-gray-400 py-4 italic">Belum ada doa di bab ini.</p>
                        @endif

                        <div class="mt-4 text-center">
                            <a href="{{ route('penulis.items.create', $chapter->id) }}" class="inline-block w-full border-2 border-dashed border-gray-300 rounded-lg p-2 text-gray-500 hover:border-indigo-500 hover:text-indigo-600 transition font-medium text-sm">
                                + Tambah Doa / Materi di Bab Ini
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500">Belum ada Bab yang dibuat.</p>
                    <p class="text-sm text-gray-400">Silakan buat Bab pertama Anda di atas (misal: "Bab Thaharah").</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>