<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28" x-data="{ showAdd: false }">
        {{-- HEADER --}}
        <div class="bg-orange-500 pt-8 pb-16 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold text-white">Peminjaman</h1>
                <a href="{{ route('pengurus.inventaris.index') }}" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
            <button @click="showAdd = true" class="bg-white text-orange-600 w-full py-3 rounded-xl font-bold text-sm shadow-sm active:scale-95 transition">+ Catat Peminjaman</button>
        </div>

        {{-- LIST --}}
        <div class="px-5 -mt-6 relative z-20 space-y-3">
            @forelse($loans as $l)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $l->barang->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $l->borrower_name }} • {{ $l->qty }} {{ $l->barang->unit }}</p>
                        </div>
                        <span class="text-[10px] px-2 py-1 rounded font-bold uppercase {{ $l->status == 'active' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                            {{ $l->status == 'active' ? 'Dipinjam' : 'Kembali' }}
                        </span>
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-gray-50 flex justify-between items-center">
                        <p class="text-xs {{ $l->end_date < now() && $l->status == 'active' ? 'text-red-500 font-bold' : 'text-gray-400' }}">
                            Deadline: {{ $l->end_date->format('d M Y') }}
                        </p>
                        
                        @if($l->status == 'active')
                            <form action="{{ route('pengurus.inventaris.peminjaman.return', $l->id) }}" method="POST" onsubmit="return confirm('Barang sudah kembali?')">
                                @csrf
                                <button class="bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-200 active:scale-95">
                                    Terima Kembali
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400 text-sm">Tidak ada peminjaman aktif.</div>
            @endforelse
        </div>

        {{-- MODAL TAMBAH (PERBAIKAN: z-[60]) --}}
        <div x-show="showAdd" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showAdd = false"></div>
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 p-6 shadow-2xl h-[85vh] flex flex-col transform transition-transform"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div class="flex justify-between items-center mb-6 flex-shrink-0">
                    <h3 class="font-bold text-lg text-gray-800">Catat Peminjaman</h3>
                    <button @click="showAdd = false" class="text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="overflow-y-auto flex-1">
                    <form action="{{ route('pengurus.inventaris.peminjaman.store') }}" method="POST" class="space-y-4 pb-4">
                        @csrf
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Barang</label>
                            <select id="select-loan-item" name="item_id" class="w-full" placeholder="Pilih Barang..." autocomplete="off">
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }} (Sisa: {{ $b->qty_good }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Peminjam</label>
                                <input type="text" name="borrower_name" class="w-full rounded-xl border-gray-200 focus:ring-orange-500" required placeholder="Nama...">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Jumlah</label>
                                <input type="number" name="qty" class="w-full rounded-xl border-gray-200 focus:ring-orange-500" required placeholder="1">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Tujuan / Lokasi</label>
                            <input type="text" name="destination" class="w-full rounded-xl border-gray-200 focus:ring-orange-500" placeholder="Cth: Aula Utama">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Tanggal Kembali</label>
                            <input type="date" name="end_date" class="w-full rounded-xl border-gray-200 focus:ring-orange-500" required>
                        </div>

                        <button type="submit" class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg active:scale-95">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @include('layouts.pengurus-nav')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#select-loan-item",{ create: false, sortField: { field: "text", direction: "asc" } });
        });
    </script>
</x-app-layout>