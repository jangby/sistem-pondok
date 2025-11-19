<?php
// Pastikan Carbon sudah di-use di layout atau file utama jika menggunakan Blade di luar Laravel
use Carbon\Carbon;
Carbon::setLocale('id'); 
?>

<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER (Gaya Mobile Dashboard Guru) --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi Background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('sekolah.guru.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Jadwal Mengajar Saya</h1>
            </div>
        </div>

        {{-- 2. LIST JADWAL (Card Style) --}}
        <div class="px-5 -mt-16 relative z-20 space-y-8">
            
            @forelse ($jadwals as $tahunAjaran => $jadwalGroup)
                <div>
                    {{-- Header Tahun Ajaran --}}
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">
                            {{ $tahunAjaran }}
                        </h3>
                        @if($jadwalGroup->first()->tahunAjaran->is_active)
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200 shadow-sm">
                                AKTIF
                            </span>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @foreach ($jadwalGroup as $jadwal)
                            {{-- Card Item Jadwal --}}
                            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center relative overflow-hidden group active:scale-[0.99] transition-transform">
                                
                                @php
                                    // Logika Waktu
                                    $hariSekarang = Carbon::now()->locale('id_ID')->isoFormat('dddd');
                                    // Karena data hari di DB mungkin "Senin" dan di Carbon "Senin", kita pastikan formatnya sama
                                    $isHariIni = strtolower($hariSekarang) == strtolower($jadwal->hari);
                                    
                                    $waktuMulai = Carbon::parse($jadwal->jam_mulai);
                                    $waktuBuka = $waktuMulai->copy()->subMinutes(15);
                                    $sekarang = Carbon::now();

                                    // Aktif jika: HARI INI dan SUDAH WAKTUNYA
                                    $isActive = $isHariIni && $sekarang->greaterThanOrEqualTo($waktuBuka);
                                    
                                    // Visual Styling
                                    $garisWarna = $isActive ? 'bg-indigo-500' : ($isHariIni ? 'bg-yellow-500' : 'bg-gray-300');
                                @endphp

                                {{-- Garis Dekorasi Kiri --}}
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $garisWarna }}"></div>

                                <div class="pl-3 flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                            {{ $jadwal->hari }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold">{{ $jadwal->kelas->nama_kelas }}</span>
                                    </div>
                                    <h4 class="text-gray-800 font-bold text-sm truncate">{{ $jadwal->mataPelajaran->nama_mapel }}</h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</p>
                                </div>

                                {{-- AKSI (MODIFIKASI Sesuai Permintaan User) --}}
                                <div>
                                    @if($isActive)
                                        <a href="{{ route('sekolah.guru.jadwal.show', $jadwal->id) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 shadow-md">
                                            Mulai
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs font-semibold cursor-not-allowed px-3 py-2" 
                                              title="{{ $isHariIni ? 'Dibuka jam ' . $waktuBuka->format('H:i') : 'Bukan jadwal hari ini' }}">
                                            @if($isHariIni)
                                                Dibuka {{ $waktuBuka->format('H:i') }}
                                            @else
                                                <span class="text-red-500">Bukan Hari Ini</span>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-10 text-center">
                    <p class="text-gray-500 text-sm font-medium">Anda belum memiliki jadwal mengajar.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>