<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28" x-data="{ showAdd: false, showResolve: false, selectedId: null }">
        
        {{-- HEADER --}}
        <div class="bg-red-600 pt-8 pb-16 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold text-white">Laporan Kerusakan</h1>
                <a href="{{ route('pengurus.inventaris.index') }}" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
            <button @click="showAdd = true" class="bg-white text-red-600 w-full py-3 rounded-xl font-bold text-sm shadow-sm active:scale-95 transition">+ Lapor Barang Rusak</button>
        </div>

        {{-- LIST --}}
        <div class="px-5 -mt-6 relative z-20 space-y-3">
            @forelse($damages as $d)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $d->barang->name }}</h3>
                            <p class="text-xs text-gray-500">Jml: {{ $d->qty }} {{ $d->barang->unit }} • {{ ucfirst($d->severity) }}</p>
                        </div>
                        <span class="text-[10px] px-2 py-1 rounded font-bold uppercase {{ $d->status == 'dilaporkan' ? 'bg-red-100 text-red-600' : ($d->status == 'diperbaiki' ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600') }}">
                            {{ $d->status }}
                        </span>
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-gray-50 flex justify-between items-center">
                        <p class="text-xs text-gray-400 italic truncate max-w-[150px]">{{ $d->notes }}</p>
                        @if($d->status != 'selesai')
                            <button @click="selectedId = {{ $d->id }}; showResolve = true" class="bg-gray-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm active:scale-95">
                                Tindak Lanjut
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400 text-sm">Tidak ada laporan kerusakan aktif.</div>
            @endforelse
        </div>

        {{-- MODAL LAPOR (PERBAIKAN: z-[60]) --}}
        <div x-show="showAdd" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showAdd = false"></div>
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 p-6 shadow-2xl h-[85vh] flex flex-col transform transition-transform"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div class="flex justify-between items-center mb-6 flex-shrink-0">
                    <h3 class="font-bold text-lg text-gray-800">Lapor Kerusakan</h3>
                    <button @click="showAdd = false" class="text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="overflow-y-auto flex-1">
                    <form action="{{ route('pengurus.inventaris.kerusakan.store') }}" method="POST" class="space-y-4 pb-4">
                        @csrf
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Barang</label>
                            <select id="select-item" name="item_id" class="w-full rounded-xl border-gray-200 focus:ring-red-500" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }} (Stok: {{ $b->qty_good }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Jumlah</label>
                                <input type="number" name="qty" class="w-full rounded-xl border-gray-200" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Tingkat</label>
                                <select name="severity" class="w-full rounded-xl border-gray-200">
                                    <option value="ringan">Ringan</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="parah">Parah</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Catatan</label>
                            <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-200"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-xl shadow-lg active:scale-95">Laporkan</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL TINDAK LANJUT (PERBAIKAN: z-[60]) --}}
        <div x-show="showResolve" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showResolve = false"></div>
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 p-6 shadow-2xl transform transition-transform">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-gray-800">Update Status</h3>
                    <button @click="showResolve = false" class="text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                
                <form x-bind:action="'/pengurus/inventaris/kerusakan/' + selectedId + '/resolve'" method="POST" class="space-y-3">
                    @csrf
                    <button type="submit" name="action" value="perbaiki" class="w-full bg-yellow-50 text-yellow-700 font-bold py-3 rounded-xl text-left px-4 hover:bg-yellow-100 border border-yellow-100">
                        🔧 Sedang Diperbaiki (Service)
                    </button>
                    <button type="submit" name="action" value="selesai_fix" class="w-full bg-green-50 text-green-700 font-bold py-3 rounded-xl text-left px-4 hover:bg-green-100 border border-green-100">
                        ✅ Selesai Diperbaiki (Stok Kembali)
                    </button>
                    <button type="submit" name="action" value="ganti" class="w-full bg-blue-50 text-blue-700 font-bold py-3 rounded-xl text-left px-4 hover:bg-blue-100 border border-blue-100">
                        🔄 Ganti Baru (Stok Kembali)
                    </button>
                    <button type="submit" name="action" value="buang" class="w-full bg-red-50 text-red-700 font-bold py-3 rounded-xl text-left px-4 hover:bg-red-100 border border-red-100">
                        🗑️ Rusak Total / Buang
                    </button>
                </form>
            </div>
        </div>

    </div>
    @include('layouts.pengurus-nav')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#select-item",{ create: false, sortField: { field: "text", direction: "asc" } });
        });
    </script>
</x-app-layout>