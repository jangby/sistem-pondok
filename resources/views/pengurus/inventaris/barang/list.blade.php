<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28" x-data="{ showAdd: false, price: 0, qty: 0 }">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-16 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('pengurus.inventaris.barang.index') }}" class="bg-white/20 p-2 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
                    <h1 class="text-lg font-bold text-white truncate max-w-[200px]">{{ $lokasi->name }}</h1>
                </div>
                {{-- Tombol Tambah di Header --}}
                <button @click="showAdd = true" class="bg-white text-emerald-600 px-3 py-2 rounded-xl text-xs font-bold shadow-sm active:scale-95 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Barang
                </button>
            </div>
            
            {{-- Search --}}
            <form method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari di {{ $lokasi->name }}..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border-0 bg-white/10 text-white placeholder-emerald-200 text-sm focus:ring-2 focus:ring-white/50 backdrop-blur-sm">
                <div class="absolute left-3 top-3 text-emerald-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
            </form>
        </div>

        {{-- LIST BARANG --}}
        <div class="px-5 -mt-4 relative z-20 space-y-3">
            @forelse($barangs as $b)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col gap-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $b->name }}</h3>
                            <p class="text-[10px] text-gray-500 bg-gray-100 px-2 py-0.5 rounded inline-block mt-1 font-mono">{{ $b->code }}</p>
                        </div>
                        {{-- Menu Titik Tiga (Edit/Hapus) bisa ditambahkan di sini --}}
                         <form action="{{ route('pengurus.inventaris.barang.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')" class="ml-2">
                            @csrf @method('DELETE')
                            <button class="text-red-200 hover:text-red-500 p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </form>
                    </div>
                    
                    <div class="flex justify-between items-end border-t border-gray-50 pt-2 mt-1">
                        <div class="text-xs text-gray-500 space-y-1">
                            <p class="flex items-center gap-1"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Bagus: <strong class="text-emerald-600">{{ $b->qty_good }}</strong> {{ $b->unit }}</p>
                            @if($b->qty_damaged > 0 || $b->qty_borrowed > 0)
                                <p class="flex items-center gap-1"><span class="w-2 h-2 bg-orange-500 rounded-full"></span> Lainnya: <strong class="text-orange-500">{{ $b->qty_damaged + $b->qty_borrowed + $b->qty_repairing }}</strong></p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-gray-400 uppercase">Nilai Aset</p>
                            <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($b->price * ($b->qty_good + $b->qty_damaged + $b->qty_borrowed + $b->qty_repairing), 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    @if($b->pic)
                        <div class="mt-1 text-[10px] text-gray-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            PIC: {{ $b->pic->full_name }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 text-gray-400 text-sm bg-white rounded-2xl border-2 border-dashed border-gray-200 mt-4">
                    Belum ada barang di lokasi ini.
                </div>
            @endforelse
            
            <div class="mt-4">{{ $barangs->links('pagination::tailwind') }}</div>
        </div>

        {{-- MODAL TAMBAH (Z-INDEX 60) --}}
        <div x-show="showAdd" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showAdd = false"></div>
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 p-6 shadow-2xl h-[90vh] flex flex-col overflow-hidden">
                
                <div class="flex justify-between items-center mb-4 flex-shrink-0">
                    <h3 class="font-bold text-lg text-gray-800">Tambah Barang di {{ $lokasi->name }}</h3>
                    <button @click="showAdd = false" class="text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="overflow-y-auto flex-1 pb-4 custom-scrollbar">
                    <form action="{{ route('pengurus.inventaris.barang.store') }}" method="POST" class="space-y-4">
                        @csrf
                        {{-- Hidden Location ID --}}
                        <input type="hidden" name="location_id" value="{{ $lokasi->id }}">
                        
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Kode Barang</label>
                            <div class="flex gap-2">
                                <input type="text" name="code" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="Scan/Ketik..." required>
                                <button type="button" class="bg-gray-100 p-3 rounded-xl text-gray-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M19 19h-5m-9-6h5a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zM6 21v-2a1 1 0 011-1h10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"></path></svg></button>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Nama Barang</label>
                            <input type="text" name="name" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Satuan</label>
                                <input type="text" name="unit" class="w-full rounded-xl border-gray-200" placeholder="Pcs" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Stok Awal</label>
                                <input type="number" name="qty_good" x-model="qty" class="w-full rounded-xl border-gray-200" required>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Harga Satuan</label>
                            <input type="number" name="price" x-model="price" class="w-full rounded-xl border-gray-200 mb-2" required>
                            
                            <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                <span class="text-xs font-bold text-gray-500 uppercase">Total Aset</span>
                                <span class="text-lg font-black text-emerald-600" x-text="'Rp ' + (qty * price).toLocaleString('id-ID')"></span>
                            </div>
                        </div>

                        <div wire:ignore>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Penanggung Jawab</label>
                            <select id="select-pic" name="pic_santri_id" placeholder="Cari Nama Santri..." autocomplete="off">
                                <option value="">- Tidak Ada -</option>
                                @foreach($santris as $s)
                                    <option value="{{ $s->id }}">{{ $s->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg mt-4 active:scale-95 transition">Simpan Barang</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    @include('layouts.pengurus-nav')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#select-pic",{ create: false, sortField: { field: "text", direction: "asc" } });
        });
    </script>
</x-app-layout>