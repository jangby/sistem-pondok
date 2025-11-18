<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            {{-- Dekorasi --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-3xl"></div>
            
            <div class="relative z-10 flex justify-between items-center mb-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('orangtua.dashboard') }}" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-lg font-bold text-white">{{ $santri->full_name }}</h1>
                </div>
            </div>
            
            {{-- STATUS TERKINI (Highlight) --}}
            <div class="text-center text-white">
                <p class="text-emerald-100 text-xs uppercase tracking-widest mb-1">Status Terkini</p>
                <div class="inline-flex items-center gap-2 {{ $status['class'] }} px-4 py-1.5 rounded-full shadow-lg shadow-black/10">
                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                    <span class="font-bold text-sm">{{ $status['text'] }}</span>
                </div>
                <p class="text-xs mt-2 text-emerald-50 opacity-80">{{ $status['desc'] }}</p>
            </div>
        </div>

        {{-- LOG TERAKHIR (Floating) --}}
        <div class="px-5 -mt-12 relative z-20 mb-6">
            <div class="bg-white rounded-3xl shadow-xl p-5 border border-gray-100">
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-3">Aktivitas Terakhir</p>
                
                @if($lastAbsen)
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm">{{ $lastAbsen->nama_kegiatan ?? 'Absensi' }}</h3>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($lastAbsen->waktu_catat)->format('d M Y, H:i') }}</p>
                            <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold">HADIR</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Belum ada data aktivitas.</p>
                @endif
            </div>
        </div>

        {{-- MENU GRID --}}
        <div class="px-5 space-y-4">
            <h3 class="font-bold text-gray-800 ml-1">Menu Monitoring</h3>
            
            <div class="grid grid-cols-2 gap-4">
                {{-- 1. Absensi --}}
                <a href="{{ route('orangtua.monitoring.absensi', $santri->id) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-emerald-200">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Riwayat Absensi</span>
                    <p class="text-[10px] text-gray-400 mt-1">Sholat, Asrama, Kegiatan</p>
                </a>

                {{-- 2. Kesehatan --}}
                <a href="{{ route('orangtua.monitoring.kesehatan', $santri->id) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-emerald-200">
                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Kesehatan / UKS</span>
                    <p class="text-[10px] text-gray-400 mt-1">Riwayat sakit & obat</p>
                </a>

                {{-- 3. Perizinan --}}
                <a href="{{ route('orangtua.monitoring.izin', $santri->id) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-emerald-200">
                    <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Izin & Pulang</span>
                    <p class="text-[10px] text-gray-400 mt-1">Keluar komplek/pulang</p>
                </a>

                {{-- 4. Gerbang --}}
                <a href="{{ route('orangtua.monitoring.gerbang', $santri->id) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-emerald-200">
                    <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Log Gerbang</span>
                    <p class="text-[10px] text-gray-400 mt-1">Aktivitas keluar masuk</p>
                </a>
            </div>
        </div>

    </div>
</x-app-layout>