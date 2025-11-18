<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- Kita gunakan x-data untuk mengontrol Filter Drawer --}}
    <div class="min-h-screen bg-gray-50 pb-24" x-data="{ showFilter: false }">
        
        {{-- 1. HEADER & SEARCH (Sticky) --}}
        <div class="bg-emerald-700 pt-6 pb-6 px-5 shadow-md sticky top-0 z-30 rounded-b-[25px]">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-xl font-bold text-white">Riwayat Penjualan</h1>
                <div class="text-xs text-emerald-100 bg-emerald-800/50 px-2 py-1 rounded-lg border border-emerald-600">
                    {{ Auth::user()->warung->nama_warung }}
                </div>
            </div>

            {{-- Search Bar & Filter Button --}}
            <form method="GET" action="{{ route('pos.history') }}" class="flex gap-2">
                {{-- Input Hidden untuk menjaga tanggal saat search --}}
                @if(request('start_date'))
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                @endif

                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2.5 border-none rounded-xl bg-emerald-800/50 text-white placeholder-emerald-200/70 focus:ring-2 focus:ring-emerald-400 focus:bg-emerald-800 transition" 
                           placeholder="Cari nama santri...">
                </div>
                
                <button type="button" 
                        @click="showFilter = true"
                        class="bg-white text-emerald-700 px-3.5 rounded-xl shadow-sm flex items-center justify-center active:scale-95 transition relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    {{-- Dot Indikator jika sedang difilter --}}
                    @if(request('start_date'))
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                    @endif
                </button>
            </form>
        </div>

        {{-- 2. SUMMARY CARD (Hasil Filter) --}}
        <div class="px-5 mt-4">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Omset</p>
                    <p class="text-lg font-black text-emerald-600">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
                </div>
                <div class="text-right border-l border-gray-100 pl-4">
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Transaksi</p>
                    <p class="text-lg font-bold text-gray-800">{{ $totalTransaksi }}</p>
                </div>
            </div>
            
            {{-- Keterangan Filter Aktif --}}
            @if(request('start_date'))
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                    <span>Filter: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}</span>
                    <a href="{{ route('pos.history') }}" class="text-red-500 hover:underline">Hapus Filter</a>
                </div>
            @endif
        </div>

        {{-- 3. LIST TRANSAKSI --}}
        <div class="px-5 mt-4 space-y-3">
            @forelse($transaksis as $trx)
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center active:scale-[0.99] transition-transform">
                    <div class="flex items-center gap-3">
                        {{-- Avatar Inisial --}}
                        <div class="w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-700 font-bold text-xs border border-emerald-100 shrink-0">
                            {{ substr($trx->dompet->santri->full_name ?? 'U', 0, 2) }}
                        </div>
                        
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate max-w-[150px]">
                                {{ $trx->dompet->santri->full_name ?? 'Santri Dihapus' }}
                            </p>
                            <div class="flex items-center gap-2 text-[10px] text-gray-400 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $trx->created_at->format('d M Y • H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right shrink-0">
                        <p class="text-sm font-bold text-emerald-600">+{{ number_format(abs($trx->nominal), 0, ',', '.') }}</p>
                        <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Jajan</span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Tidak ada transaksi ditemukan.</p>
                    @if(request('search') || request('start_date'))
                        <a href="{{ route('pos.history') }}" class="text-emerald-600 text-xs mt-2 hover:underline">Reset Filter</a>
                    @endif
                </div>
            @endforelse

            {{-- Pagination --}}
            <div class="pt-4 pb-8">
                {{ $transaksis->onEachSide(1)->links() }}
            </div>
        </div>

        {{-- 4. FILTER DRAWER (MODAL BAWAH) --}}
        {{-- PERBAIKAN: z-index dinaikkan ke z-[60] agar di atas navigasi (z-50) --}}
        <div x-show="showFilter" 
             class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" 
             style="display: none;">
            
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
                 x-show="showFilter"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showFilter = false"></div>

            {{-- Drawer Panel --}}
            {{-- PERBAIKAN: Tambahkan pb-10 agar tombol tidak mepet bawah --}}
            <div class="bg-white w-full max-w-md mx-auto rounded-t-[25px] shadow-2xl p-6 pb-10 relative transform transition-all"
                 x-show="showFilter"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full">
                
                {{-- Handle Bar --}}
                <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>

                <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Tanggal</h3>

                <form method="GET" action="{{ route('pos.history') }}">
                    {{-- Keep Search --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="space-y-4">
                        {{-- Quick Chips --}}
                        <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
                            <button type="button" 
                                    @click="document.getElementById('start_date').value = '{{ date('Y-m-d') }}'; document.getElementById('end_date').value = '{{ date('Y-m-d') }}'"
                                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold whitespace-nowrap hover:bg-emerald-50 hover:text-emerald-600 transition">
                                Hari Ini
                            </button>
                            <button type="button" 
                                    @click="document.getElementById('start_date').value = '{{ date('Y-m-d', strtotime('-1 day')) }}'; document.getElementById('end_date').value = '{{ date('Y-m-d', strtotime('-1 day')) }}'"
                                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold whitespace-nowrap hover:bg-emerald-50 hover:text-emerald-600 transition">
                                Kemarin
                            </button>
                            <button type="button" 
                                    @click="document.getElementById('start_date').value = '{{ date('Y-m-01') }}'; document.getElementById('end_date').value = '{{ date('Y-m-t') }}'"
                                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold whitespace-nowrap hover:bg-emerald-50 hover:text-emerald-600 transition">
                                Bulan Ini
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Dari</label>
                                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Sampai</label>
                                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="showFilter = false" class="flex-1 py-3 border border-gray-200 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-200 hover:bg-emerald-700">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @include('layouts.pos-nav')
</x-app-layout>