<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-40">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="relative z-10 flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('pengurus.asrama.ketua.index') }}" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">Laporan Ketua</h1>
                        <p class="text-emerald-100 text-xs mt-0.5">Rekapitulasi Periode</p>
                    </div>
                </div>
                
                {{-- Filter Rentang --}}
                <form method="GET" class="flex items-center gap-2 bg-white/10 p-1.5 rounded-2xl backdrop-blur-md border border-white/10">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-1/2 bg-transparent border-0 text-white text-xs font-bold placeholder-emerald-200 focus:ring-0 p-2 text-center" onchange="this.form.submit()">
                    <span class="text-emerald-200 text-xs">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-1/2 bg-transparent border-0 text-white text-xs font-bold placeholder-emerald-200 focus:ring-0 p-2 text-center" onchange="this.form.submit()">
                </form>
            </div>
        </div>

        {{-- LIST DATA --}}
        <div class="px-5 -mt-12 relative z-20 space-y-3">
            <div class="flex justify-between items-end px-1 mb-1 text-white/90">
                <span class="text-xs font-bold uppercase">Daftar Ketua</span>
                <span class="text-xs font-mono bg-white/20 px-2 py-0.5 rounded">{{ $totalHari }} Hari</span>
            </div>

            @foreach($rekapData as $row)
                {{-- Link ke Halaman Detail --}}
                <a href="{{ route('pengurus.asrama.ketua.rekap.detail', ['id' => $row['santri']->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   class="group bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center transition active:scale-95 hover:border-emerald-200">
                    
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-inner bg-emerald-50 text-emerald-700">
                            {{ substr($row['santri']->full_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm group-hover:text-emerald-700 transition">{{ $row['santri']->full_name }}</h3>
                            <p class="text-[10px] text-gray-500 flex items-center gap-1">
                                {{-- Sekarang data asrama sudah dimuat (Eager Loaded) --}}
                                <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                {{ $row['santri']->asrama->nama_asrama ?? 'Asrama ?' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="flex items-center gap-1 justify-end">
                            <span class="text-lg font-black text-emerald-600">{{ $row['hadir'] }}</span>
                            <span class="text-gray-300">/</span>
                            <span class="text-lg font-black {{ $row['bolos'] > 0 ? 'text-red-500' : 'text-gray-400' }}">{{ $row['bolos'] }}</span>
                        </div>
                        <div class="flex items-center justify-end gap-1 text-[9px] text-gray-400 uppercase font-bold">
                            Detail <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>
            @endforeach

            @if(empty($rekapData))
                <div class="text-center py-12 bg-white rounded-2xl border-dashed border border-gray-200 mt-4">
                    <p class="text-gray-400 text-sm">Belum ada data ketua.</p>
                </div>
            @endif
        </div>
    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>