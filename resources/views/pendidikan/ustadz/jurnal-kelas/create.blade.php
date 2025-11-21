<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden sticky top-0 z-30">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center gap-4 text-white">
                <a href="{{ route('ustadz.absensi.menu', $jadwal->id) }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold leading-tight">Jurnal Materi</h1>
                    <p class="text-xs text-emerald-100 opacity-90 mt-1">{{ $jadwal->mapel->nama_mapel }} • {{ $jadwal->mustawa->nama }}</p>
                </div>
            </div>
        </div>

        {{-- 2. FORM INPUT --}}
        <div class="px-6 -mt-12 relative z-20">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-50 mb-6">
                
                <div class="flex items-center gap-2 mb-6 text-emerald-700 border-b border-emerald-50 pb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <h3 class="font-bold text-sm">Materi Hari Ini</h3>
                </div>

                <form method="POST" action="{{ route('ustadz.jurnal-kelas.store', $jadwal->id) }}">
                    @csrf
                    
                    {{-- Input Materi --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pokok Bahasan / Materi</label>
                        <input type="text" name="materi" value="{{ old('materi', $jurnalHariIni->materi ?? '') }}" 
                            class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-semibold text-gray-700 placeholder-gray-300" 
                            placeholder="Contoh: Bab Wudhu (Hal. 12-15)" required autofocus>
                    </div>

                    {{-- Input Catatan --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan / Kejadian (Opsional)</label>
                        <textarea name="catatan" rows="3" 
                            class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm text-gray-700 placeholder-gray-300" 
                            placeholder="Contoh: Santri sangat antusias, fulan sakit perut...">{{ old('catatan', $jurnalHariIni->catatan ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white py-3.5 rounded-xl font-bold text-sm shadow-lg hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        {{ $jurnalHariIni ? 'Perbarui Jurnal' : 'Simpan Jurnal' }}
                    </button>
                </form>
            </div>

            {{-- 3. RIWAYAT MATERI (Accordion Style) --}}
            @if($riwayat->isNotEmpty())
                <h3 class="text-gray-500 font-bold text-xs uppercase tracking-wider mb-3 px-2">Riwayat Pertemuan Sebelumnya</h3>
                
                <div class="space-y-3">
                    @foreach($riwayat as $log)
                        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex gap-4 items-start">
                            {{-- Tanggal --}}
                            <div class="flex-shrink-0 text-center bg-gray-50 p-2 rounded-lg border border-gray-200 w-14">
                                <span class="block text-xs font-bold text-gray-400">{{ $log->tanggal->format('M') }}</span>
                                <span class="block text-lg font-bold text-gray-700">{{ $log->tanggal->format('d') }}</span>
                            </div>
                            
                            {{-- Konten --}}
                            <div>
                                <h4 class="text-sm font-bold text-gray-800">{{ $log->materi }}</h4>
                                @if($log->catatan)
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2 italic">"{{ $log->catatan }}"</p>
                                @else
                                    <p class="text-[10px] text-gray-400 mt-1">Tidak ada catatan khusus</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-xs text-gray-400">Belum ada riwayat materi sebelumnya.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>