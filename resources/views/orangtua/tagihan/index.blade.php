<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- 1. MOBILE HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-6 shadow-md rounded-b-[25px] relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
                <div class="w-32 h-32 bg-white opacity-10 rounded-full absolute -top-10 -left-10 blur-2xl"></div>
                <div class="w-40 h-40 bg-emerald-400 opacity-10 rounded-full absolute bottom-0 right-0 blur-xl"></div>
            </div>
            
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('orangtua.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-lg font-bold text-white">Daftar Tagihan</h1>
                </div>
                
                {{-- Filter --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-white p-1.5 bg-emerald-700/30 rounded-lg backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </button>
                    
                    <div x-show="open" @click.outside="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl py-1 z-50 border border-gray-100 text-sm" style="display: none;">
                        <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Semua</a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Belum Lunas</a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'paid']) }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Lunas</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. LIST TAGIHAN (CLICKABLE CARDS) --}}
        <div class="px-4 mt-5 space-y-3">
            
            @forelse ($tagihans as $tagihan)
                {{-- 
                    PERUBAHAN UTAMA:
                    Menggunakan tag <a> sebagai pembungkus utama (Card).
                    Ini membuat seluruh area kartu bisa diklik untuk menuju halaman detail.
                --}}
                <a href="{{ route('orangtua.tagihan.show', $tagihan->id) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative transition-transform active:scale-[0.98] hover:shadow-md group">
                    
                    {{-- Garis Status --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 
                        @if($tagihan->status == 'paid') bg-emerald-500 
                        @elseif($tagihan->status == 'partial') bg-yellow-500 
                        @else bg-red-500 @endif">
                    </div>

                    <div class="py-3 pr-4 pl-5">
                        <div class="flex justify-between items-start gap-2">
                            {{-- Kiri: Info Tagihan --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                        {{ $tagihan->santri->full_name }}
                                    </span>
                                    <span class="text-[10px] text-gray-300">•</span>
                                    <span class="text-[10px] text-gray-400">
                                        {{ \Carbon\Carbon::parse($tagihan->due_date)->format('d M Y') }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-800 leading-tight truncate">
                                    {{ $tagihan->jenisPembayaran->name }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5 font-mono">
                                    #{{ $tagihan->invoice_number }}
                                </p>
                            </div>

                            {{-- Kanan: Nominal & Status --}}
                            <div class="text-right shrink-0">
                                @if($tagihan->status == 'paid')
                                    <div class="flex flex-col items-end">
                                        <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold mb-1">
                                            LUNAS
                                        </span>
                                        {{-- Ikon panah kecil untuk Lunas --}}
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                @elseif($tagihan->status == 'partial')
                                    <span class="inline-block px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-[10px] font-bold mb-1">
                                        DICICIL
                                    </span>
                                    <p class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($tagihan->nominal_tagihan, 0, ',', '.') }}
                                    </p>
                                @else
                                    <span class="inline-block px-2 py-0.5 bg-red-50 text-red-600 rounded text-[10px] font-bold mb-1">
                                        BELUM LUNAS
                                    </span>
                                    <p class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($tagihan->nominal_tagihan, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- 
                           Tombol "Bayar Sekarang" hanya visual saja (span), 
                           karena seluruh kartu sudah menjadi link <a>. 
                           Ini menghindari error nested anchor tags.
                        --}}
                        @if($tagihan->status != 'paid')
                            <div class="mt-3 pt-2 border-t border-gray-50 flex justify-end">
                                <span class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-[11px] font-bold uppercase tracking-wider rounded-md shadow-sm group-hover:bg-emerald-700 transition w-full justify-center sm:w-auto">
                                    Bayar Sekarang
                                    <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </span>
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-xs">Tidak ada tagihan saat ini.</p>
                </div>
            @endforelse

            {{-- Pagination (Compact) --}}
            <div class="pt-4 pb-8 px-2">
                {{ $tagihans->onEachSide(1)->links() }} 
            </div>

        </div>
    </div>
</x-app-layout>