<?php
use Carbon\Carbon;
Carbon::setLocale('id');
?>

<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden sticky top-0 z-30">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-4 text-white">
                <a href="{{ route('ustadz.absensi.menu', $jadwal->id) }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold leading-tight">Riwayat Absensi</h1>
                    <p class="text-xs text-emerald-100 opacity-90 mt-1">{{ $jadwal->mapel->nama_mapel }} • {{ $jadwal->mustawa->nama }}</p>
                </div>
            </div>
        </div>

        {{-- 2. LIST RIWAYAT --}}
        <div class="px-6 -mt-12 relative z-20 space-y-4">
            
            @forelse($riwayat as $log)
                <a href="{{ route('ustadz.absensi.history.detail', ['jadwal' => $jadwal->id, 'tanggal' => $log->tanggal]) }}" 
                   class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition active:scale-[0.98] group">
                    
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            {{-- Tanggal Icon --}}
                            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex flex-col items-center justify-center text-emerald-700 border border-emerald-100">
                                <span class="text-[10px] font-bold uppercase">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('MMM') }}</span>
                                <span class="text-lg font-bold leading-none">{{ \Carbon\Carbon::parse($log->tanggal)->format('d') }}</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd, D MMMM Y') }}</h4>
                                <p class="text-[10px] text-gray-400 mt-0.5">Total Santri: {{ $log->total_santri }}</p>
                            </div>
                        </div>
                        <div class="text-gray-300 group-hover:text-emerald-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>

                    {{-- Statistik Bar --}}
                    <div class="flex gap-2">
                        {{-- Hadir --}}
                        <div class="flex-1 bg-emerald-50 rounded-lg p-2 text-center border border-emerald-100">
                            <span class="block text-[10px] text-emerald-600 font-bold">Hadir</span>
                            <span class="block text-sm font-extrabold text-emerald-700">{{ $log->hadir }}</span>
                        </div>
                        {{-- Sakit/Izin --}}
                        <div class="flex-1 bg-blue-50 rounded-lg p-2 text-center border border-blue-100">
                            <span class="block text-[10px] text-blue-600 font-bold">Sakit/Izin</span>
                            <span class="block text-sm font-extrabold text-blue-700">{{ $log->sakit + $log->izin }}</span>
                        </div>
                        {{-- Alpha --}}
                        <div class="flex-1 bg-red-50 rounded-lg p-2 text-center border border-red-100">
                            <span class="block text-[10px] text-red-600 font-bold">Alpha</span>
                            <span class="block text-sm font-extrabold text-red-700">{{ $log->alpa }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Belum ada riwayat absensi.</p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
</x-app-layout>