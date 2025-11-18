<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. MOBILE HEADER (Gaya Konsisten Dashboard) --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi Background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('sekolah.guru.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Jadwal Mengajar</h1>
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
                            <a href="{{ route('sekolah.guru.jadwal.show', $jadwal->id) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative group active:scale-[0.98] transition-transform">
                                
                                {{-- Garis Indikator --}}
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>

                                <div class="p-4 pl-5 flex justify-between items-center">
                                    <div>
                                        {{-- Baris Atas: Hari & Jam --}}
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-600 border border-gray-200">
                                                {{ $jadwal->hari }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                            </span>
                                        </div>

                                        {{-- Judul Mapel --}}
                                        <h4 class="text-sm font-bold text-gray-800 leading-tight mb-0.5">
                                            {{ $jadwal->mataPelajaran->nama_mapel }}
                                        </h4>

                                        {{-- Kelas --}}
                                        <p class="text-xs text-gray-500 font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $jadwal->kelas->nama_kelas }}
                                        </p>
                                    </div>

                                    {{-- Panah Kanan --}}
                                    <div class="text-gray-300 group-hover:text-emerald-500 transition-colors pl-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @empty
                {{-- State Kosong --}}
                <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-8 text-center mt-4">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-800">Belum Ada Jadwal</h3>
                    <p class="text-xs text-gray-500 mt-1">Anda belum memiliki jadwal mengajar yang ditugaskan.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>