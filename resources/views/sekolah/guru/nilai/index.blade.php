<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER (Gaya Mobile Konsisten) --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi Background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('sekolah.guru.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Input Nilai (Kegiatan)</h1>
            </div>
        </div>

        {{-- 2. LIST KEGIATAN (Card Style) --}}
        <div class="px-5 -mt-16 relative z-20 space-y-4">
            
            <div class="mb-3 px-1">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Pilih Kegiatan Akademik</p>
            </div>

            @forelse ($kegiatans as $kegiatan)
                {{-- Link ini mengarah ke halaman DAFTAR KELAS --}}
                {{-- Variabel $kegiatan tetap sama --}}
                <a href="{{ route('sekolah.guru.nilai.kelas', $kegiatan->id) }}" 
                   class="block bg-white p-5 rounded-2xl shadow-md border border-gray-100 relative group hover:border-emerald-300 transition-all active:scale-[0.98] hover:shadow-lg">
                    
                    <div class="flex justify-between items-start">
                        <div class="pr-4">
                            {{-- Tipe Kegiatan --}}
                            <span class="inline-block px-2.5 py-1 text-[10px] uppercase font-bold 
                                {{ $kegiatan->tipe == 'ujian' ? 'text-red-600 bg-red-50' : 'text-blue-600 bg-blue-50' }}
                                rounded-lg mb-1">
                                {{ $kegiatan->tipe }}
                            </span>
                            
                            {{-- Nama Kegiatan --}}
                            <h4 class="text-lg font-bold text-gray-800">{{ $kegiatan->nama_kegiatan }}</h4>
                            
                            {{-- Tanggal --}}
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $kegiatan->tanggal_mulai->format('d M Y') }} 
                                @if ($kegiatan->tanggal_mulai != $kegiatan->tanggal_selesai)
                                    - {{ $kegiatan->tanggal_selesai->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                        
                        {{-- Ikon Navigasi --}}
                        <div class="text-gray-300 group-hover:text-emerald-500 transition-colors shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-500 text-sm">Belum ada kegiatan akademik yang tersedia untuk pengisian nilai.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>