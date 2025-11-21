<?php
use Carbon\Carbon;
Carbon::setLocale('id');
?>

<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden sticky top-0 z-30">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center gap-4 text-white">
                <a href="{{ route('ustadz.absensi.history', $jadwal->id) }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold leading-tight">Detail Kehadiran</h1>
                    <p class="text-xs text-emerald-100 opacity-90 mt-1">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
            </div>
        </div>

        {{-- LIST DETAIL --}}
        <div class="px-6 -mt-12 relative z-20 space-y-3">
            
            @foreach($absensis as $absen)
                <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                            {{ substr($absen->santri->full_name, 0, 2) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $absen->santri->full_name }}</h4>
                            <p class="text-[10px] text-gray-400">{{ $absen->santri->nis }}</p>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div>
                        @if($absen->status == 'H')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Hadir
                            </span>
                        @elseif($absen->status == 'I')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700">
                                Izin
                            </span>
                        @elseif($absen->status == 'S')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-700">
                                Sakit
                            </span>
                        @elseif($absen->status == 'A')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700">
                                Alpha
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>