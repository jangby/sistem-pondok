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
                    <div>
                        <h1 class="text-lg font-bold text-white leading-tight">{{ $santri->full_name }}</h1>
                        <p class="text-[10px] text-emerald-100">{{ $santri->mustawa->nama ?? 'Santri Aktif' }}</p>
                    </div>
                </div>
            </div>
            
            {{-- STATUS TERKINI (Highlight) --}}
            <div class="text-center text-white mt-2">
                <p class="text-emerald-100 text-[10px] uppercase tracking-widest mb-1.5">Status Saat Ini</p>
                <div class="inline-flex items-center gap-2 {{ $status['class'] }} pl-1.5 pr-4 py-1.5 rounded-full shadow-lg shadow-black/10 border border-white/20">
                    <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                        {{-- Icon Dinamis --}}
                        @if($status['icon'] == 'home')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        @elseif($status['icon'] == 'logout')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        @elseif($status['icon'] == 'heart')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        @else
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                    <span class="font-bold text-sm">{{ $status['text'] }}</span>
                </div>
                <p class="text-[10px] mt-2 text-emerald-50 opacity-90 italic">"{{ $status['desc'] }}"</p>
            </div>
        </div>

        {{-- LOG TERAKHIR (Floating Card) --}}
        <div class="px-5 -mt-12 relative z-20 mb-6">
            <div class="bg-white rounded-3xl shadow-xl p-5 border border-gray-100 flex items-center justify-between">
                
                @if($lastActivity)
                    {{-- Logic Badge Warna --}}
                    @php
                        $badgeColor = 'bg-emerald-100 text-emerald-700';
                        $statusLabel = 'HADIR';
                        if(isset($lastActivity->status)) {
                            if($lastActivity->status == 'I') { $badgeColor = 'bg-purple-100 text-purple-700'; $statusLabel = 'IZIN'; }
                            elseif($lastActivity->status == 'S') { $badgeColor = 'bg-red-100 text-red-700'; $statusLabel = 'SAKIT'; }
                            elseif($lastActivity->status == 'A') { $badgeColor = 'bg-red-100 text-red-700'; $statusLabel = 'ALPHA'; }
                        }
                    @endphp

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 {{ $badgeColor }} rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Aktivitas Terakhir</p>
                            <h3 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $lastActivity->nama_kegiatan ?? 'Absensi Harian' }}</h3>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($lastActivity->waktu_catat)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] {{ $badgeColor }} px-2 py-1 rounded-lg font-bold block">
                            {{ $statusLabel }}
                        </span>
                        <span class="text-[10px] text-gray-400 mt-1 block">
                            {{ \Carbon\Carbon::parse($lastActivity->waktu_catat)->format('H:i') }}
                        </span>
                    </div>
                @else
                    {{-- Tampilan Kosong (Jika belum ada absen hari ini) --}}
                    <div class="flex items-center gap-3 w-full">
                        <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Aktivitas Terakhir</p>
                            <h3 class="font-bold text-gray-600 text-sm">Belum ada data</h3>
                            <p class="text-xs text-gray-400 italic">Menunggu absensi masuk...</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- MENU GRID --}}
        <div class="px-5 space-y-5">
            <h3 class="font-bold text-gray-800 text-sm ml-1 flex items-center gap-2">
                <span class="w-1 h-4 bg-emerald-500 rounded-full"></span>
                Menu Monitoring
            </h3>
            
            <div class="grid grid-cols-2 gap-4">
                {{-- 1. Absensi Mengaji (NEW) --}}
                <a href="{{ route('orangtua.monitoring.diniyah', $santri->id) }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-teal-300 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-teal-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-teal-100"></div>
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-3 relative z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm relative z-10">Absensi Mengaji</span>
                    <p class="text-[10px] text-gray-400 mt-0.5 relative z-10">Kehadiran Diniyah</p>
                </a>

                {{-- 2. Hafalan Qur'an (NEW) --}}
                <a href="{{ route('orangtua.monitoring.hafalan', $santri->id) }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-amber-300 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-amber-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-amber-100"></div>
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-3 relative z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm relative z-10">Hafalan Qur'an</span>
                    <p class="text-[10px] text-gray-400 mt-0.5 relative z-10">Setoran & Murojaah</p>
                </a>

                {{-- 3. Riwayat Absensi (Harian) --}}
                <a href="{{ route('orangtua.monitoring.absensi', $santri->id) }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-blue-300">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Absensi Harian</span>
                    <p class="text-[10px] text-gray-400 mt-0.5">Sholat & Asrama</p>
                </a>

                {{-- 4. Kesehatan --}}
                <a href="{{ route('orangtua.monitoring.kesehatan', $santri->id) }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-red-300">
                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Kesehatan / UKS</span>
                    <p class="text-[10px] text-gray-400 mt-0.5">Riwayat sakit</p>
                </a>

                {{-- 5. Perizinan --}}
                <a href="{{ route('orangtua.monitoring.izin', $santri->id) }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-purple-300">
                    <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Izin & Pulang</span>
                    <p class="text-[10px] text-gray-400 mt-0.5">Status perizinan</p>
                </a>

                {{-- 6. Log Gerbang --}}
                <a href="{{ route('orangtua.monitoring.gerbang', $santri->id) }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-orange-300">
                    <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Log Gerbang</span>
                    <p class="text-[10px] text-gray-400 mt-0.5">Keluar masuk</p>
                </a>
            </div>
        </div>

    </div>
</x-app-layout>