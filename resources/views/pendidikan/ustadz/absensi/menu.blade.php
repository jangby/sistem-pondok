<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden sticky top-0 z-30">
            {{-- Dekorasi Background --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-4 text-white">
                {{-- Tombol Kembali --}}
                <a href="{{ route('ustadz.jadwal.index') }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold leading-tight">{{ $jadwal->mapel->nama_mapel }}</h1>
                    <p class="text-xs text-emerald-100 opacity-90 mt-1">{{ $jadwal->mustawa->nama }} • {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- MENU GRID (Floating) --}}
        <div class="px-6 -mt-12 relative z-20">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-50">
                <h3 class="text-gray-800 font-bold text-sm mb-4 text-center flex items-center justify-center gap-2">
                    <span class="w-1 h-4 bg-emerald-500 rounded-full"></span>
                    Menu Sesi Ini
                </h3>
                
                <div class="grid grid-cols-3 gap-4">
                    
                    {{-- 1. Mulai Absensi --}}
                    <a href="{{ route('ustadz.absensi.create', $jadwal->id) }}" class="flex flex-col items-center gap-2 group p-3 rounded-xl hover:bg-emerald-50 transition border border-transparent hover:border-emerald-100 active:scale-95">
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm group-hover:scale-110 transition-transform border border-emerald-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 17h.01M9 14h.01M3 21h18M4.5 4.5l15 15"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold text-gray-700 text-center leading-tight">Mulai<br>Absensi</span>
                    </a>

                    {{-- 2. Jurnal Materi --}}
                    <a href="{{ route('ustadz.jurnal-kelas.create', $jadwal->id) }}" class="flex flex-col items-center gap-2 group p-3 rounded-xl hover:bg-blue-50 transition border border-transparent hover:border-blue-100 active:scale-95">
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm group-hover:scale-110 transition-transform border border-blue-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold text-gray-700 text-center leading-tight">Jurnal<br>Materi</span>
                    </a>

                    {{-- 3. Riwayat Absensi --}}
                    <a href="{{ route('ustadz.absensi.history', $jadwal->id) }}" class="flex flex-col items-center gap-2 group p-3 rounded-xl hover:bg-orange-50 transition border border-transparent hover:border-orange-100 active:scale-95">
                        <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 shadow-sm group-hover:scale-110 transition-transform border border-orange-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold text-gray-700 text-center leading-tight">Riwayat<br>Absensi</span>
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>