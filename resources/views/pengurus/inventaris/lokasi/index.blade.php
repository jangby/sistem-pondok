<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28" x-data="{ showAdd: false }">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-16 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold text-white">Data Lokasi</h1>
                <a href="{{ route('pengurus.inventaris.index') }}" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
            {{-- Tombol Header --}}
            <button @click="showAdd = true" class="bg-white text-emerald-600 w-full py-3 rounded-xl font-bold text-sm shadow-sm active:scale-95 transition">+ Tambah Lokasi</button>
        </div>

        {{-- LIST LOKASI --}}
        <div class="px-5 -mt-6 relative z-20 space-y-3">
            @foreach($lokasis as $l)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $l->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $l->items_count }} Barang tersimpan</p>
                    </div>
                    <form action="{{ route('pengurus.inventaris.lokasi.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-300 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- MODAL TAMBAH (PERBAIKAN: z-[60]) --}}
        <div x-show="showAdd" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" style="display: none;">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showAdd = false"></div>
            
            {{-- Content --}}
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 p-6 shadow-2xl transform transition-transform"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-gray-800">Tambah Lokasi</h3>
                    <button @click="showAdd = false" class="text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <form action="{{ route('pengurus.inventaris.lokasi.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Nama Lokasi</label>
                            <input type="text" name="name" placeholder="Contoh: Gudang A" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Keterangan</label>
                            <textarea name="description" rows="2" placeholder="Opsional..." class="w-full rounded-xl border-gray-200 focus:ring-emerald-500"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg active:scale-95">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>