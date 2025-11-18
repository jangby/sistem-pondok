<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-16 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold text-white">Data Barang</h1>
                <a href="{{ route('pengurus.inventaris.index') }}" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
            <p class="text-emerald-100 text-xs font-medium">Pilih lokasi penyimpanan untuk melihat barang.</p>
        </div>

        {{-- LIST GRUP LOKASI --}}
        <div class="px-5 -mt-6 relative z-20 space-y-3">
            @forelse($lokasis as $l)
                <a href="{{ route('pengurus.inventaris.barang.by_lokasi', $l->id) }}" class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition group hover:border-emerald-200">
                    <div class="flex items-center gap-4">
                        {{-- Icon Folder --}}
                        <div class="w-14 h-14 bg-yellow-50 text-yellow-500 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-yellow-100 transition">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10z"></path></svg>
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-lg group-hover:text-emerald-600 transition">{{ $l->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded text-gray-500 font-bold">{{ $l->items_count }} Item</span>
                                <span class="text-[10px] text-gray-400">|</span>
                                <span class="text-[10px] text-emerald-600 font-bold">Rp {{ number_format($l->total_nilai, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            @empty
                <div class="text-center py-12 text-gray-400 text-sm bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <p>Belum ada lokasi penyimpanan.</p>
                    <a href="{{ route('pengurus.inventaris.lokasi.index') }}" class="text-emerald-600 font-bold text-xs underline mt-2 block">Buat Lokasi Dulu</a>
                </div>
            @endforelse
        </div>
    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>