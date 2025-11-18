<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24" x-data="{ showFilter: false }">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('bendahara.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-white">Buku Kas</h1>
                        <p class="text-emerald-100 text-xs">Pencatatan Arus Kas</p>
                    </div>
                </div>
                
                {{-- Tombol Filter & PDF --}}
                <div class="flex gap-2">
                    <button @click="showFilter = !showFilter" class="bg-white/20 backdrop-blur-md p-2 rounded-xl text-white hover:bg-white/30 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </button>
                    <a href="{{ route('bendahara.kas.pdf', request()->query()) }}" target="_blank" class="bg-red-500/80 backdrop-blur-md p-2 rounded-xl text-white hover:bg-red-600 transition" title="Cetak PDF">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- 2. PANEL FILTER (Collapsible) --}}
        <div x-show="showFilter" class="px-5 -mt-20 mb-4 relative z-20" style="display: none;">
            <div class="bg-white p-4 rounded-2xl shadow-lg border border-gray-100">
                <form method="GET" action="{{ route('bendahara.kas.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="tanggal_mulai" :value="__('Dari Tanggal')" />
                            <x-text-input id="tanggal_mulai" class="block mt-1 w-full text-sm" type="date" name="tanggal_mulai" :value="request('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'))" />
                        </div>
                        <div>
                            <x-input-label for="tanggal_selesai" :value="__('Sampai Tanggal')" />
                            <x-text-input id="tanggal_selesai" class="block mt-1 w-full text-sm" type="date" name="tanggal_selesai" :value="request('tanggal_selesai', now()->endOfMonth()->format('Y-m-d'))" />
                        </div>
                        <div>
                            <x-input-label for="tipe" :value="__('Tipe Transaksi')" />
                            <select name="tipe" id="tipe" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                <option value="">Semua</option>
                                <option value="pemasukan" {{ request('tipe') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="pengeluaran" {{ request('tipe') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <x-primary-button class="w-full justify-center">Terapkan Filter</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- 3. SUMMARY CARDS --}}
        <div class="px-5 {{ request('showFilter') ? 'mt-4' : '-mt-16' }} relative z-20">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 mb-6 text-center">
                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Saldo Akhir Periode Ini</p>
                <h3 class="text-3xl font-black text-emerald-600 tracking-tight">
                    Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                </h3>
                
                <div class="grid grid-cols-3 gap-2 mt-6 pt-6 border-t border-gray-100 text-center">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Awal</p>
                        <p class="text-sm font-bold text-gray-700">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</p>
                    </div>
                    <div class="border-l border-gray-100">
                        <p class="text-[10px] text-emerald-500 font-bold uppercase">Masuk</p>
                        <p class="text-sm font-bold text-emerald-600">+{{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    </div>
                    <div class="border-l border-gray-100">
                        <p class="text-[10px] text-red-500 font-bold uppercase">Keluar</p>
                        <p class="text-sm font-bold text-red-600">-{{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- 4. DAFTAR TRANSAKSI --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 text-base">Mutasi Kas</h3>
                <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">{{ $transaksis->total() }} Data</span>
            </div>

            <div class="space-y-3">
                @php $saldoBerjalan = $saldoAwal; @endphp
                
                @forelse ($transaksis as $trx)
                    @php
                        if ($trx->tipe == 'pemasukan') {
                            $saldoBerjalan += $trx->nominal;
                            $color = 'emerald';
                            $sign = '+';
                        } else {
                            $saldoBerjalan -= $trx->nominal;
                            $color = 'red';
                            $sign = '-';
                        }
                    @endphp

                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-start group active:scale-[0.99] transition-transform relative overflow-hidden">
                        
                        {{-- Indikator Warna Kiri --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-{{ $color }}-500"></div>

                        <div class="flex gap-3 items-start pl-3 w-full">
                            <div class="mt-1">
                                @if($trx->tipe == 'pemasukan')
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between">
                                    <h4 class="text-sm font-bold text-gray-800 truncate max-w-[60%]">{{ $trx->deskripsi }}</h4>
                                    <span class="text-sm font-bold text-{{ $color }}-600">{{ $sign }}Rp {{ number_format($trx->nominal, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-[10px] text-gray-400">
                                        {{ $trx->tanggal_transaksi->format('d M Y') }} • Oleh: {{ $trx->user->name }}
                                    </p>
                                    
                                    {{-- Tombol Aksi (Hanya untuk Pengeluaran Manual) --}}
                                    @if($trx->tipe == 'pengeluaran')
                                        <div class="flex gap-3">
                                            <a href="{{ route('bendahara.kas.edit', $trx->id) }}" class="text-[10px] text-blue-600 font-bold hover:underline">Edit</a>
                                            <form action="{{ route('bendahara.kas.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-[10px] text-red-500 font-bold hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Belum ada transaksi.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4 pb-8">
                {{ $transaksis->links() }}
            </div>

        </div>
    </div>

    {{-- Floating Action Button (Catat Pengeluaran) --}}
    <div class="fixed bottom-20 right-6 z-40">
        <a href="{{ route('bendahara.kas.create') }}" class="flex items-center justify-center w-14 h-14 bg-red-600 rounded-full shadow-lg hover:bg-red-700 transition text-white focus:outline-none focus:ring-4 focus:ring-red-300 active:scale-95" title="Catat Pengeluaran">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
        </a>
    </div>

    @include('layouts.bendahara-nav')

</x-app-layout>