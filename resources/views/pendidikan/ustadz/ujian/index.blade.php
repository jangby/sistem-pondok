<?php
use Carbon\Carbon;
Carbon::setLocale('id');
?>

<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-10">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden sticky top-0 z-30">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="relative z-10 flex items-center gap-4 text-white">
                <a href="{{ route('ustadz.dashboard') }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold">Jadwal Mengawas</h1>
                    <p class="text-xs text-emerald-100 opacity-90">Daftar ujian yang antum awasi</p>
                </div>
            </div>
        </div>

        {{-- LIST JADWAL --}}
        <div class="px-6 -mt-12 relative z-20 space-y-4">
            @forelse($jadwals as $jadwal)
                <a href="{{ route('ustadz.ujian.show', $jadwal->id) }}" class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition active:scale-[0.98] group">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider 
                                {{ $jadwal->jenis_ujian == 'uts' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ $jadwal->jenis_ujian }}
                            </span>
                            <span class="ml-2 px-2 py-1 rounded text-[10px] font-bold bg-gray-100 text-gray-600">
                                {{ ucfirst($jadwal->kategori_tes) }}
                            </span>
                        </div>
                        <div class="text-xs font-bold text-emerald-600">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-emerald-700 transition">{{ $jadwal->mapel->nama_mapel }}</h3>
                    <p class="text-sm text-gray-500">{{ $jadwal->mustawa->nama }} • {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</p>
                </a>
            @empty
                <div class="text-center py-12 text-gray-400">
                    <p class="text-sm">Belum ada jadwal mengawas.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>