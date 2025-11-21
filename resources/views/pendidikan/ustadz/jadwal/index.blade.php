<?php
use Carbon\Carbon;
Carbon::setLocale('id');
?>

<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-10">
        
        {{-- 1. HEADER SECTION --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden sticky top-0 z-30">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>
            
            <div class="relative z-10 flex items-center gap-4">
                {{-- Tombol Kembali --}}
                <a href="{{ route('ustadz.dashboard') }}" class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 border border-white/20 text-emerald-50 hover:bg-white/20 transition-all duration-300 backdrop-blur-md shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div>
                    <h1 class="text-2xl font-bold text-white">Jadwal Mengajar</h1>
                    <p class="text-xs text-emerald-100 opacity-80 mt-1">Pilih jadwal untuk mulai kelas</p>
                </div>
            </div>
        </div>

        {{-- 2. CONTENT LIST --}}
        <div class="px-6 -mt-12 relative z-20 space-y-8">

            @php
                $hariIni = \Carbon\Carbon::now()->isoFormat('dddd'); 
            @endphp

            @forelse ($urutanHari as $hari)
                @if(isset($jadwalPerHari[$hari]))
                    
                    {{-- Group Per Hari --}}
                    <div>
                        {{-- Label Hari --}}
                        <div class="flex items-center gap-3 mb-4 ml-1">
                            @if($hari == $hariIni)
                                <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-[10px] font-bold shadow-sm animate-pulse uppercase tracking-wider">
                                    Hari Ini
                                </span>
                            @endif
                            <h3 class="text-gray-700 font-bold text-lg {{ $hari == $hariIni ? 'text-emerald-700' : '' }}">
                                {{ $hari }}
                            </h3>
                            <div class="h-px bg-gray-200 flex-grow rounded-full"></div>
                        </div>

                        {{-- List Card Jadwal --}}
                        <div class="space-y-4">
                            @foreach ($jadwalPerHari[$hari] as $jadwal)
                                @php
                                    $isToday = ($hari == $hariIni);
                                    $waktuMulai = Carbon::parse($jadwal->jam_mulai);
                                    $waktuSelesai = Carbon::parse($jadwal->jam_selesai);
                                    $sekarang = Carbon::now();
                                    $sudahLewat = $isToday && $sekarang->gt($waktuSelesai);
                                    
                                    // Logika warna garis samping
                                    $lineColor = $isToday ? ($sudahLewat ? 'bg-gray-300' : 'bg-emerald-500') : 'bg-blue-100';
                                @endphp

                                {{-- LINK WRAPPER (PERBAIKAN ROUTE DI SINI) --}}
                                <a href="{{ route('ustadz.absensi.menu', $jadwal->id) }}" 
                                   class="block bg-white p-5 rounded-2xl border border-gray-50 shadow-sm relative overflow-hidden group active:scale-[0.98] transition-all duration-200 hover:shadow-md">
                                    
                                    {{-- Garis Indikator Samping --}}
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $lineColor }}"></div>

                                    <div class="flex justify-between items-center pl-3">
                                        
                                        {{-- Info Kiri --}}
                                        <div class="flex-grow">
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                                    {{ $waktuMulai->format('H:i') }} - {{ $waktuSelesai->format('H:i') }}
                                                </span>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                                    {{ $jadwal->mustawa->nama }}
                                                </span>
                                            </div>
                                            
                                            <h4 class="font-bold text-gray-800 text-lg leading-tight group-hover:text-emerald-700 transition-colors">
                                                {{ $jadwal->mapel->nama_mapel }}
                                            </h4>
                                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                {{ $jadwal->mapel->nama_kitab }}
                                            </p>
                                        </div>

                                        {{-- Indikator Panah Kanan --}}
                                        <div class="flex-shrink-0 ml-4">
                                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </div>

                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @empty
                {{-- State Kosong --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-50 p-8 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-gray-800 font-bold">Belum Ada Jadwal</h3>
                    <p class="text-gray-500 text-xs mt-1 max-w-xs mx-auto">Jadwal mengajar belum ditambahkan oleh Admin.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>