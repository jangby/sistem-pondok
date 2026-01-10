<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Penulis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-indigo-600 rounded-2xl shadow-xl overflow-hidden text-white relative">
                <div class="p-8 md:p-12 relative z-10 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Ahlan Wa Sahlan, {{ Auth::user()->name }}!</h1>
                        <p class="text-indigo-100 text-lg max-w-xl">
                            Mari lanjutkan karya tulis Anda. Menyusun doa dan ilmu untuk kemanfaatan umat.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('penulis.books.create') }}" class="bg-white text-indigo-700 px-6 py-3 rounded-lg font-bold shadow hover:bg-gray-100 transition">
                                + Mulai Tulis Buku Baru
                            </a>
                        </div>
                    </div>
                    <div class="hidden md:block text-9xl opacity-20 transform rotate-12">
                        ✍️
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="bg-blue-100 p-4 rounded-full text-blue-600 text-2xl">📚</div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Buku</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalBuku }} <span class="text-sm font-normal text-gray-400">Judul</span></h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="bg-green-100 p-4 rounded-full text-green-600 text-2xl">✅</div>
                    <div>
                        <p class="text-gray-500 text-sm">Buku Terbit</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $bukuTerbit }} <span class="text-sm font-normal text-gray-400">Siap Cetak</span></h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="bg-purple-100 p-4 rounded-full text-purple-600 text-2xl">🤲</div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Pasal / Doa</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalDoa }} <span class="text-sm font-normal text-gray-400">Item</span></h3>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-end mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Lanjutkan Menulis</h3>
                    <a href="{{ route('penulis.books.index') }}" class="text-indigo-600 text-sm font-semibold hover:underline">Lihat Semua Buku &rarr;</a>
                </div>

                @if($recentBooks->isEmpty())
                    <div class="bg-white rounded-xl p-8 text-center border border-dashed border-gray-300">
                        <p class="text-gray-500 mb-2">Belum ada riwayat penulisan.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($recentBooks as $book)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition flex flex-col h-full">
                            <div class="h-32 bg-gray-100 w-full flex items-center justify-center text-gray-300 rounded-t-xl overflow-hidden">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/'.$book->cover_image) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl">📖</span>
                                @endif
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h4 class="font-bold text-gray-800 mb-1 truncate">{{ $book->title }}</h4>
                                <p class="text-xs text-gray-500 mb-3">Diperbarui: {{ $book->updated_at->diffForHumans() }}</p>
                                
                                <div class="flex items-center gap-2 text-xs text-gray-600 mb-4 bg-gray-50 p-2 rounded">
                                    <span>📑 {{ $book->chapters_count }} Bab</span>
                                    <span>•</span>
                                    <span>🤲 {{ $book->items_count }} Doa</span>
                                </div>

                                <a href="{{ route('penulis.books.show', $book->id) }}" class="mt-auto block w-full text-center bg-indigo-50 text-indigo-700 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-100 transition">
                                    Buka Editor
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>