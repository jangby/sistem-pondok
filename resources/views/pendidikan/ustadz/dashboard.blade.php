<?php
use Carbon\Carbon;
Carbon::setLocale('id');
?>

<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER SECTION (Profile & Greeting) --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden">
            {{-- Dekorasi Background --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <p class="text-emerald-100 text-sm font-medium mb-1">Ahlan wa Sahlan,</p>
                    <h1 class="text-2xl font-bold text-white truncate max-w-[200px]">
                        {{ Auth::user()->ustadz->nama_lengkap ?? Auth::user()->name }}
                    </h1>
                    <p class="text-xs text-emerald-100 opacity-80 mt-1">
                        {{ Auth::user()->ustadz->spesialisasi ?? 'Pengajar Diniyah' }}
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Tombol Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Keluar dari aplikasi?');" class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 border border-white/20 text-emerald-50 hover:bg-red-500/80 hover:text-white hover:border-red-500/50 transition-all duration-300 backdrop-blur-md shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>

                    {{-- Avatar --}}
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full border-2 border-emerald-200/50 p-0.5 shadow-lg">
                        <div class="w-full h-full bg-white rounded-full flex items-center justify-center text-emerald-600 font-bold text-lg uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. JADWAL TERDEKAT (Highlight Card) --}}
        <div class="px-6 -mt-16 relative z-20 mb-6">
            @if($jadwalHariIni->isNotEmpty())
                @php 
                    // Cari jadwal yang belum lewat jam-nya
                    $nextJadwal = $jadwalHariIni->filter(function($j) {
                        return \Carbon\Carbon::parse($j->jam_selesai)->gt(\Carbon\Carbon::now());
                    })->first() ?? $jadwalHariIni->last(); // Fallback ke jadwal terakhir jika semua sudah lewat
                @endphp

                <div class="bg-white rounded-2xl p-5 shadow-xl border border-gray-50 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50 rounded-bl-full -mr-2 -mt-2 transition-transform group-hover:scale-110"></div>
                    
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-1">Sedang / Akan Berlangsung</p>
                            <h3 class="text-lg font-bold text-gray-800">{{ $nextJadwal->mapel->nama_mapel }}</h3>
                            <p class="text-xs text-gray-500">{{ $nextJadwal->mustawa->nama }} • {{ $nextJadwal->mapel->nama_kitab }}</p>
                        </div>
                        <div class="text-right">
                            <span class="block text-2xl font-bold text-emerald-600">{{ \Carbon\Carbon::parse($nextJadwal->jam_mulai)->format('H:i') }}</span>
                            <span class="text-[10px] text-gray-400">WIB</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
                        <a href="{{ route('ustadz.absensi.create', $nextJadwal->id) }}" class="flex-1 bg-emerald-600 text-white text-center py-2 rounded-lg text-xs font-bold hover:bg-emerald-700 transition shadow-md shadow-emerald-200">
                            Mulai Kelas
                        </a>
                        <a href="{{ route('ustadz.absensi.menu', $nextJadwal->id) }}" class="px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </a>
                    </div>
                </div>
            @else
                {{-- Jika Libur / Tidak Ada Jadwal --}}
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-50 text-center">
                    <p class="text-gray-800 font-bold">Tidak ada jadwal hari ini</p>
                    <p class="text-xs text-gray-500 mt-1">Silakan istirahat atau cek jadwal ujian.</p>
                </div>
            @endif
        </div>

        {{-- 3. MENU GRID UTAMA --}}
        <div class="px-6 mb-8">
            <h3 class="text-gray-800 font-bold text-sm mb-4 flex items-center gap-2">
                <span class="w-1 h-4 bg-emerald-500 rounded-full"></span>
                Menu Utama
            </h3>
            
            <div class="grid grid-cols-4 gap-3">
                {{-- Menu 1: Jadwal Ngaji --}}
                <a href="{{ route('ustadz.jadwal.index') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl border border-blue-100 flex items-center justify-center text-blue-600 group-active:scale-95 transition-all duration-200 shadow-sm group-hover:bg-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-[10px] text-gray-600 font-bold text-center leading-tight">Jadwal<br>Ngaji</span>
                </a>

                {{-- Menu 2: Jurnal Hafalan --}}
                <a href="{{ route('ustadz.jurnal.index') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl border border-purple-100 flex items-center justify-center text-purple-600 group-active:scale-95 transition-all duration-200 shadow-sm group-hover:bg-purple-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <span class="text-[10px] text-gray-600 font-bold text-center leading-tight">Jurnal<br>Hafalan</span>
                </a>

                {{-- Menu 3: Jadwal Ujian (BARU) --}}
                <a href="{{ route('ustadz.ujian.index') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 bg-red-50 rounded-2xl border border-red-100 flex items-center justify-center text-red-600 group-active:scale-95 transition-all duration-200 shadow-sm group-hover:bg-red-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span class="text-[10px] text-gray-600 font-bold text-center leading-tight">Jadwal<br>Ujian</span>
                </a>

                {{-- Menu 4: Profil --}}
                <a href="{{ route('ustadz.profil') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl border border-orange-100 flex items-center justify-center text-orange-600 group-active:scale-95 transition-all duration-200 shadow-sm group-hover:bg-orange-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-[10px] text-gray-600 font-bold text-center leading-tight">Profil<br>Saya</span>
                </a>
            </div>
        </div>

        {{-- 4. DAFTAR JADWAL HARI INI --}}
        <div class="px-6">
            <div class="flex justify-between items-end mb-4">
                <h3 class="text-gray-800 font-bold text-sm flex items-center gap-2">
                    <span class="w-1 h-4 bg-emerald-500 rounded-full"></span>
                    Jadwal Hari Ini
                </h3>
                <p class="text-[10px] text-gray-400 bg-white px-2 py-1 rounded border border-gray-100 shadow-sm">
                    {{ Carbon::now()->isoFormat('dddd, D MMM') }}
                </p>
            </div>

            <div class="space-y-3">
                @forelse ($jadwalHariIni as $jadwal)
                    @php
                        $waktuMulai = Carbon::parse($jadwal->jam_mulai);
                        $waktuSelesai = Carbon::parse($jadwal->jam_selesai);
                        $sekarang = Carbon::now();
                        
                        $isLive = $sekarang->between($waktuMulai, $waktuSelesai);
                        $isDone = $sekarang->gt($waktuSelesai);
                        
                        $statusColor = $isDone ? 'bg-gray-100 border-gray-200' : ($isLive ? 'bg-emerald-50 border-emerald-200' : 'bg-white border-gray-100');
                        $iconColor = $isDone ? 'text-gray-400' : ($isLive ? 'text-emerald-600' : 'text-blue-500');
                    @endphp

                    <a href="{{ route('ustadz.absensi.menu', $jadwal->id) }}" class="block p-4 rounded-2xl border {{ $statusColor }} shadow-sm active:scale-[0.98] transition-all relative overflow-hidden">
                        @if($isLive)
                            <div class="absolute top-0 right-0 bg-emerald-500 text-white text-[8px] font-bold px-2 py-1 rounded-bl-lg animate-pulse">
                                SEDANG BERLANGSUNG
                            </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center {{ $iconColor }} shadow-sm border border-gray-100">
                                <span class="text-xs font-bold">{{ $waktuMulai->format('H:i') }}</span>
                            </div>
                            
                            <div class="flex-grow">
                                <h4 class="text-sm font-bold text-gray-800 {{ $isDone ? 'text-gray-500 line-through' : '' }}">
                                    {{ $jadwal->mapel->nama_mapel }}
                                </h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-200 text-gray-600 font-medium">{{ $jadwal->mustawa->nama }}</span>
                                    <span class="text-[10px] text-gray-400 truncate max-w-[120px]">{{ $jadwal->mapel->nama_kitab }}</span>
                                </div>
                            </div>

                            <div class="text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8">
                        <p class="text-xs text-gray-400">Tidak ada jadwal lagi hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>