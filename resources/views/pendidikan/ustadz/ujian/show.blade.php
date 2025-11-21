<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24" x-data="{ tab: 'nilai' }"> {{-- Default tab Nilai --}}
        
        {{-- HEADER FIXED --}}
        <div class="bg-white shadow-sm sticky top-0 z-30">
            <div class="bg-emerald-600 px-6 pt-6 pb-12 rounded-b-[25px]">
                <div class="flex items-center gap-4 text-white">
                    <a href="{{ route('ustadz.ujian.index') }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition backdrop-blur-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div class="flex-grow">
                        <h1 class="text-lg font-bold leading-tight">{{ $jadwal->mapel->nama_mapel }}</h1>
                        <p class="text-xs text-emerald-100 opacity-90">
                            {{ $jadwal->mustawa->nama }} • {{ ucfirst($jadwal->kategori_tes) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- TAB SWITCHER (Floating) --}}
            <div class="px-6 -mt-6 pb-4">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-1 flex">
                    <button @click="tab = 'nilai'" :class="tab === 'nilai' ? 'bg-emerald-100 text-emerald-700 shadow-sm' : 'text-gray-500'" class="flex-1 py-2.5 rounded-lg text-xs font-bold transition-all">
                        Input Nilai
                    </button>
                    <button @click="tab = 'absensi'" :class="tab === 'absensi' ? 'bg-blue-100 text-blue-700 shadow-sm' : 'text-gray-500'" class="flex-1 py-2.5 rounded-lg text-xs font-bold transition-all">
                        Absensi Ujian
                    </button>
                </div>
            </div>
        </div>

        <div class="px-4 mt-2">
            
            {{-- TAB NILAI --}}
            <div x-show="tab === 'nilai'" x-transition>
                <form action="{{ route('ustadz.ujian.nilai', $jadwal->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4 pb-20"> {{-- Tambah padding bawah biar gak ketutup tombol --}}
                        
                        {{-- Header Info --}}
                        <div class="flex justify-between items-center px-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            <span>Santri</span>
                            <div class="flex gap-4 text-right">
                                <span class="w-20">Nilai Ujian</span>
                                <span class="w-20 text-emerald-600">Kehadiran</span>
                            </div>
                        </div>

                        @foreach($santris as $santri)
                            @php 
                                $record = $nilai[$santri->id] ?? null;
                                
                                // 1. Nilai Ujian Utama
                                $val = 0;
                                if($record) {
                                    if($jadwal->kategori_tes == 'tulis') $val = $record->nilai_tulis;
                                    elseif($jadwal->kategori_tes == 'lisan') $val = $record->nilai_lisan;
                                    elseif($jadwal->kategori_tes == 'praktek') $val = $record->nilai_praktek;
                                }
                                $val = $val == 0 ? '' : $val;

                                // 2. Nilai Kehadiran (Logika Otomatis vs Database)
                                // Jika di DB sudah ada (pernah disimpan), pakai DB. 
                                // Jika belum (0), pakai hasil kalkulasi otomatis dari statistik.
                                $valHadir = 0;
                                if($record && $record->nilai_kehadiran > 0) {
                                    $valHadir = $record->nilai_kehadiran;
                                } else {
                                    $valHadir = $statistikKehadiran[$santri->id] ?? 100; // Default 100
                                }
                            @endphp

                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between gap-3">
                                
                                {{-- Nama Santri --}}
                                <div class="flex-grow min-w-0">
                                    <h4 class="text-sm font-bold text-gray-800 truncate">{{ $santri->full_name }}</h4>
                                    <p class="text-[10px] text-gray-400">{{ $santri->nis }}</p>
                                </div>

                                {{-- Input Wrapper --}}
                                <div class="flex gap-3">
                                    
                                    {{-- Input Nilai Ujian --}}
                                    <div class="flex flex-col items-center">
                                        <input type="number" name="grades[{{ $santri->id }}]" value="{{ $val }}" 
                                            class="w-20 text-center text-lg font-bold text-gray-700 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 p-2 bg-gray-50"
                                            placeholder="0" min="0" max="100" step="0.01">
                                        <span class="text-[9px] text-gray-400 mt-1">{{ ucfirst($jadwal->kategori_tes) }}</span>
                                    </div>

                                    {{-- Input Kehadiran (Otomatis tapi Editable) --}}
                                    <div class="flex flex-col items-center">
                                        <input type="number" name="attendance_score[{{ $santri->id }}]" value="{{ $valHadir }}" 
                                            class="w-20 text-center text-lg font-bold text-emerald-700 border-emerald-100 rounded-lg focus:border-emerald-500 focus:ring-emerald-500 p-2 bg-emerald-50"
                                            placeholder="0" min="0" max="100">
                                        <span class="text-[9px] text-emerald-600 mt-1 font-bold">Absen %</span>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Sticky Save Button --}}
                    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 z-40 max-w-md mx-auto shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-emerald-700 transition flex justify-center items-center gap-2 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Semua Nilai
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB ABSENSI --}}
            <div x-show="tab === 'absensi'" x-transition style="display: none;">
                <form action="{{ route('ustadz.ujian.absensi', $jadwal->id) }}" method="POST">
                    @csrf
                    <div class="space-y-3 pb-20">
                        @foreach($santris as $santri)
                            @php $status = $absensi[$santri->id] ?? 'H'; @endphp {{-- Default Hadir --}}
                            
                            <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                <div class="mb-2 border-b border-gray-50 pb-2">
                                    <h4 class="text-sm font-bold text-gray-800">{{ $santri->full_name }}</h4>
                                </div>
                                <div class="flex justify-between gap-2">
                                    {{-- Radio Button Gaya Tombol --}}
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $santri->id }}]" value="H" class="peer hidden" {{ $status == 'H' ? 'checked' : '' }}>
                                        <div class="text-center py-2 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 peer-checked:bg-emerald-100 peer-checked:text-emerald-700 transition">Hadir</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $santri->id }}]" value="I" class="peer hidden" {{ $status == 'I' ? 'checked' : '' }}>
                                        <div class="text-center py-2 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 peer-checked:bg-blue-100 peer-checked:text-blue-700 transition">Izin</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $santri->id }}]" value="S" class="peer hidden" {{ $status == 'S' ? 'checked' : '' }}>
                                        <div class="text-center py-2 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 peer-checked:bg-orange-100 peer-checked:text-orange-700 transition">Sakit</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $santri->id }}]" value="A" class="peer hidden" {{ $status == 'A' ? 'checked' : '' }}>
                                        <div class="text-center py-2 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 peer-checked:bg-red-100 peer-checked:text-red-700 transition">Alpha</div>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 z-40 max-w-md mx-auto">
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-blue-700 transition">
                            Simpan Absensi
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>