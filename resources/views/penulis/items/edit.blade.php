<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&display=swap');
        .font-arab { 
            font-family: 'Amiri', serif; 
            line-height: 2.2; 
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Doa / Materi') }}
        </h2>
        <p class="text-sm text-gray-500">
            Mengedit: <span class="font-bold">{{ $item->title }}</span>
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-8">
                
                <form action="{{ route('penulis.items.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Judul Doa / Pasal</label>
                        <input type="text" name="title" 
                               class="w-full text-lg font-semibold border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200" 
                               value="{{ old('title', $item->title) }}" 
                               required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2 flex justify-between">
                                <span>Teks Arab</span>
                                <span class="text-xs font-normal text-gray-500">Gunakan keyboard Arab</span>
                            </label>
                            <textarea name="arabic_content" rows="12" 
                                class="w-full font-arab text-2xl text-right border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 p-4" 
                                placeholder="Teks Arab...">{{ old('arabic_content', $item->arabic_content) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Terjemahan / Latin / Penjelasan</label>
                            <textarea name="translation_content" rows="12" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 p-4" 
                                placeholder="Terjemahan...">{{ old('translation_content', $item->translation_content) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 border-t pt-6">
                        <a href="{{ route('penulis.books.show', $item->chapter->book_id) }}" class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            Batal
                        </a>
                        
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg transition transform hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>