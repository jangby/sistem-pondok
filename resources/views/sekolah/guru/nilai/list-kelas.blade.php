<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER (Gaya Mobile Konsisten) --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi Background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('sekolah.guru.nilai.index') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">{{ $kegiatan->nama_kegiatan }}</h1>
            </div>
        </div>

        {{-- 2. KONTEN LIST --}}
        <div class="px-5 -mt-16 relative z-20 space-y-4">
            
            <div class="mb-3 px-1">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Pilih Kelas & Mapel Anda</p>
            </div>

            <div class="space-y-3">
                @forelse ($listKelasMapel as $item)
                    @php
                        // Hitung persentase untuk visual
                        $persen = $item->persen;
                        $progressColor = 'indigo';
                        if ($persen == 100) {
                            $progressColor = 'emerald';
                        } elseif ($persen > 0) {
                            $progressColor = 'blue';
                        }
                    @endphp

                    {{-- Card Item Mapel/Kelas --}}
                    <a href="{{ route('sekolah.guru.nilai.form', ['kegiatan' => $kegiatan->id, 'kelasId' => $item->kelas_id, 'mapelId' => $item->mapel_id]) }}" 
                       class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative group active:scale-[0.98] transition-transform hover:shadow-md">
                        
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1 pr-4">
                                <span class="inline-block px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-[10px] font-bold uppercase tracking-wider mb-1.5">
                                    Kelas {{ $item->nama_kelas }}
                                </span>
                                <h4 class="text-base font-bold text-gray-800">{{ $item->nama_mapel }}</h4>
                            </div>
                            
                            {{-- Badge Progress --}}
                            <div class="text-right shrink-0">
                                <span class="text-[10px] font-bold px-2 py-1 rounded-full 
                                    {{ $persen == 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $item->sudah_dinilai }} / {{ $item->total_santri }} Siswa
                                </span>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-gray-100 rounded-full h-2.5 mt-1 relative">
                            <div class="bg-{{ $progressColor }}-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
                            <p class="absolute right-0 top-1/2 transform -translate-y-1/2 pr-2 text-[10px] font-bold text-white z-10">{{ $persen }}%</p>
                            <p class="text-[10px] text-right mt-1 text-gray-500">Selesai</p>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-200">
                        <p class="text-gray-500 text-sm font-medium">Anda tidak memiliki tugas mengajar di kegiatan ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>