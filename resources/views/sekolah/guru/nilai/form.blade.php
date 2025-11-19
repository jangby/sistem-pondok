<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-32">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('sekolah.guru.nilai.kelas', $kegiatan->id) }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Input Nilai Siswa</h1>
            </div>
        </div>

        {{-- 2. FORM & KONTEN --}}
        <div class="px-5 -mt-16 relative z-20">
            
            {{-- KARTU INFO DETAIL --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 mb-5">
                <span class="inline-block px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-[10px] font-bold uppercase tracking-wider mb-2">
                    Kelas {{ $kelas->nama_kelas }}
                </span>
                <h3 class="text-xl font-bold text-gray-800 leading-tight">
                    {{-- PERBAIKAN: Gunakan $mapel, bukan $mataPelajaran --}}
                    {{ $mapel->nama_mapel }}
                </h3>
                <p class="text-xs text-gray-500 mt-1 font-medium">
                    {{-- PERBAIKAN: Ambil tahun ajaran dari kegiatan --}}
                    Kegiatan: {{ $kegiatan->nama_kegiatan }}
                </p>
                <hr class="my-3 border-gray-100">
                <p class="text-xs text-gray-700 font-medium">
                    {{-- PERBAIKAN: Gunakan $santris, bukan $siswa --}}
                    Total Siswa: {{ $santris->count() }}
                </p>
            </div>
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('sekolah.guru.nilai.store', $kegiatan->id) }}">
                @csrf
                {{-- PERBAIKAN: Gunakan $mapel->id --}}
                <input type="hidden" name="mapel_id" value="{{ $mapel->id }}">
                <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    
                    <div class="bg-gray-50 p-4 border-b border-gray-100">
                         <p class="text-sm font-bold text-gray-800">Daftar Siswa</p>
                         <p class="text-[10px] text-gray-500">Masukkan nilai 0-100</p>
                    </div>

                    {{-- LIST INPUT NILAI --}}
                    <div class="divide-y divide-gray-100 max-h-[60vh] overflow-y-auto">
                        {{-- PERBAIKAN: Loop $santris as $santri --}}
                        @forelse ($santris as $index => $santri)
                            <div class="flex items-center justify-between p-4 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                
                                <div class="flex-1 pr-4">
                                    <p class="text-sm font-medium text-gray-800">{{ $santri->full_name }}</p>
                                    <p class="text-xs text-gray-500">NIS: {{ $santri->nis }}</p>
                                </div>
                                
                                <div class="w-20 shrink-0">
                                    {{-- PERBAIKAN: Ambil nilai dari array $nilaiExisting --}}
                                    @php
                                        $val = $nilaiExisting[$santri->id] ?? '';
                                    @endphp
                                    <input type="number" 
                                           name="nilai[{{ $santri->id }}]" 
                                           value="{{ old('nilai.' . $santri->id, $val) }}"
                                           min="0" max="100" step="0.1"
                                           class="block w-full py-2 px-3 border-gray-200 rounded-lg text-sm text-center focus:border-emerald-500 focus:ring-emerald-500"
                                           placeholder="Nilai">
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-500 text-sm">
                                Tidak ada siswa di kelas ini.
                            </div>
                        @endforelse
                    </div>

                </div>

                {{-- FIXED BOTTOM BAR (Submit Button) --}}
                <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-100 p-4 pb-6 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] z-50">
                    <div class="max-w-3xl mx-auto">
                        <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition active:scale-[0.98] flex items-center justify-center gap-2">
                            <span>Simpan Semua Nilai</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>