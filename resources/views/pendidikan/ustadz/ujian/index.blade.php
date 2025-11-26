<?php
use Carbon\Carbon;
Carbon::setLocale('id');
?>

<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-10">
        
        {{-- HEADER (Dibuat lebih tipis/compact) --}}
        <div class="bg-emerald-600 pt-6 pb-12 px-5 rounded-b-[25px] shadow-md relative overflow-hidden sticky top-0 z-30">
            {{-- Hiasan Background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-8 -mt-8 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center gap-3 text-white">
                <a href="{{ route('ustadz.dashboard') }}" class="bg-white/20 p-2 rounded-lg hover:bg-white/30 transition backdrop-blur-md flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div class="flex-grow">
                    <h1 class="text-lg font-bold leading-tight">Jadwal Mengawas</h1>
                    <p class="text-[11px] text-emerald-100 opacity-90">Tahun Ajaran Aktif</p>
                </div>
            </div>
        </div>

        {{-- LIST JADWAL --}}
        {{-- Margin top disesuaikan (-mt-6) agar tidak terlalu naik karena header sudah ditipiskan --}}
        <div class="px-5 -mt-1 relative z-20 space-y-3">
            @forelse($jadwals as $jadwal)
                <a href="{{ route('ustadz.ujian.show', $jadwal->id) }}" class="block bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition active:scale-[0.98] group relative overflow-hidden">
                    
                    {{-- Garis Indikator di kiri --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $jadwal->jenis_ujian == 'uts' ? 'bg-blue-500' : 'bg-purple-500' }}"></div>

                    <div class="ml-2">
                        {{-- Baris Atas: Tanggal & Jam --}}
                        <div class="flex justify-between items-start mb-2 border-b border-gray-50 pb-2">
                            <div class="flex items-center gap-2">
                                <div class="text-xs font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded">
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('dddd, D MMM') }}
                                </div>
                            </div>
                            <div class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            </div>
                        </div>
                        
                        {{-- Baris Tengah: Nama Mapel --}}
                        <div class="mb-2">
                            <h3 class="text-base font-bold text-gray-800 leading-snug group-hover:text-emerald-700 transition">
                                {{ $jadwal->mapel->nama_mapel }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $jadwal->mustawa->nama }}</p>
                        </div>

                        {{-- Baris Bawah: Badges --}}
                        <div class="flex gap-2 mt-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border 
                                {{ $jadwal->jenis_ujian == 'uts' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-purple-50 text-purple-600 border-purple-100' }}">
                                {{ $jadwal->jenis_ujian }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border bg-emerald-50 text-emerald-600 border-emerald-100">
                                {{ ucfirst($jadwal->kategori_tes) }}
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-dashed border-gray-300">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-sm text-gray-500 font-medium">Belum ada jadwal ujian.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>