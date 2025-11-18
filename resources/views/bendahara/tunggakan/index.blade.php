<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24" x-data="{ showFilter: false }">
        
        {{-- 1. HEADER (Sama seperti sebelumnya) --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('bendahara.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-white">Data Tunggakan</h1>
                        <p class="text-emerald-100 text-xs">Monitor Piutang Santri</p>
                    </div>
                </div>
                
                <button @click="showFilter = !showFilter" class="bg-white/20 backdrop-blur-md p-2 rounded-xl text-white hover:bg-white/30 transition border border-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </button>
            </div>
        </div>

        {{-- 2. KARTU TOTAL PIUTANG --}}
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-red-50 rounded-bl-full -mr-5 -mt-5"></div>
                
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1 relative z-10">Total Piutang Santri</p>
                <h3 class="text-3xl font-black text-red-600 tracking-tight relative z-10">
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-gray-400 mt-2 relative z-10">
                    Dari {{ $santris->total() }} santri yang menunggak.
                </p>
            </div>
        </div>

        {{-- 3. FILTER SECTION --}}
        <div x-show="showFilter" class="px-5 mt-4" style="display: none;" x-transition>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('bendahara.tunggakan.index') }}">
                    <div class="grid grid-cols-1 gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full border-gray-300 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Cari Nama / NIS...">
                        
                        <select name="kelas_id" class="block w-full border-gray-300 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Semua Kelas</option>
                            @foreach($daftarKelas as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                        
                        <button type="submit" class="w-full py-2.5 bg-gray-800 text-white rounded-lg text-sm font-bold">Filter Data</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 4. LIST SANTRI MENUNGGAK (Card Clickable) --}}
        <div class="px-5 mt-6 space-y-4">
            <h3 class="text-gray-800 font-bold text-base">Daftar Penunggak</h3>
            
            @forelse ($santris as $santri)
                {{-- Ubah DIV menjadi Link (A) agar bisa diklik --}}
                <a href="{{ route('bendahara.tunggakan.show', $santri->id) }}" class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden hover:shadow-md transition active:scale-[0.98] group">
                    
                    {{-- Garis Indikator Merah --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500"></div>
                    
                    <div class="pl-3 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-xs border border-gray-200 group-hover:bg-red-50 group-hover:text-red-600 transition-colors">
                                {{ substr($santri->full_name, 0, 2) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $santri->full_name }}</h4>
                                <p class="text-[10px] text-gray-500">
                                    {{ $santri->kelas->nama_kelas ?? '-' }} • NIS: {{ $santri->nis }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Sisa</p>
                            <p class="text-sm font-black text-red-600">Rp {{ number_format($santri->total_tunggakan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    {{-- Indikator panah kecil di pojok kanan (opsional, pemanis visual) --}}
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity -mr-2 group-hover:mr-0">
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            @empty
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 mb-4">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-gray-800 font-bold">Semua Lunas!</h3>
                    <p class="text-gray-500 text-xs mt-1">Tidak ada data tunggakan sesuai filter saat ini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 pb-8 px-5">
            {{ $santris->links() }}
        </div>

    </div>

    @include('layouts.bendahara-nav')

</x-app-layout>