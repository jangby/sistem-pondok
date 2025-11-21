<x-app-layout>
    {{-- STYLE KHUSUS --}}
    @push('styles')
    <style>
        .report-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        /* Custom Select */
        .custom-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        /* Animasi Pop */
        .pop-in { animation: popIn 0.2s ease-out forwards; }
        @keyframes popIn {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <span class="text-3xl">📑</span> Pusat Laporan & Ledger
                </h2>
                <p class="text-sm text-gray-500 mt-1">Cetak arsip kehadiran guru dan siswa dalam format PDF.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- CARD 1: LAPORAN GURU --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden report-card relative group" 
                     x-data="{ selected: 'guru_sekolah' }"> {{-- Alpine Data --}}
                    
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                    
                    <div class="p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-2xl shadow-sm border border-indigo-100 group-hover:scale-110 transition-transform">
                                    👨‍🏫
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-800">Laporan Guru</h3>
                                    <p class="text-sm text-gray-500">Rekapitulasi kehadiran tenaga pendidik</p>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('sekolah.admin.laporan.cetak') }}" method="POST" target="_blank" class="space-y-5">
                            @csrf
                            
                            <div class="space-y-3">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pilih Jenis Dokumen</label>
                                <div class="grid grid-cols-1 gap-3">
                                    
                                    {{-- Opsi 1 --}}
                                    <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-200"
                                           :class="selected == 'guru_sekolah' ? 'bg-indigo-50 border-indigo-500 ring-1 ring-indigo-500 shadow-sm' : 'border-gray-200 hover:bg-gray-50 hover:border-gray-300'">
                                        <input type="radio" name="jenis_laporan" value="guru_sekolah" x-model="selected" class="sr-only">
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-800">1. Ledger Absensi Harian</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Data check-in/check-out gerbang sekolah.</div>
                                        </div>
                                        {{-- Custom Radio Circle --}}
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors duration-200"
                                             :class="selected == 'guru_sekolah' ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300 bg-white'">
                                            <div class="w-2.5 h-2.5 bg-white rounded-full pop-in" x-show="selected == 'guru_sekolah'"></div>
                                        </div>
                                    </label>

                                    {{-- Opsi 2 --}}
                                    <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-200"
                                           :class="selected == 'guru_pelajaran' ? 'bg-indigo-50 border-indigo-500 ring-1 ring-indigo-500 shadow-sm' : 'border-gray-200 hover:bg-gray-50 hover:border-gray-300'">
                                        <input type="radio" name="jenis_laporan" value="guru_pelajaran" x-model="selected" class="sr-only">
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-800">2. Jurnal Mengajar (Per Jam)</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Rekap kehadiran guru di setiap jam pelajaran.</div>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors duration-200"
                                             :class="selected == 'guru_pelajaran' ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300 bg-white'">
                                            <div class="w-2.5 h-2.5 bg-white rounded-full pop-in" x-show="selected == 'guru_pelajaran'"></div>
                                        </div>
                                    </label>

                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-2">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 mb-1 block">Bulan</label>
                                    <select name="bulan" class="custom-select block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-semibold text-gray-700">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}" @selected($i == now()->month)>{{ \Carbon\Carbon::create()->month($i)->locale('id')->isoFormat('MMMM') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 mb-1 block">Tahun</label>
                                    <select name="tahun" class="custom-select block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-semibold text-gray-700">
                                        @for($y=now()->year; $y>=2023; $y--)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak Ledger Guru
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- CARD 2: LAPORAN SISWA --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden report-card relative group" 
                     x-data="{ selected: 'siswa_sekolah' }"> {{-- Alpine Data --}}
                     
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-500 to-teal-500"></div>

                    <div class="p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-2xl shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform">
                                    🎓
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-800">Laporan Siswa</h3>
                                    <p class="text-sm text-gray-500">Rekapitulasi kehadiran peserta didik</p>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('sekolah.admin.laporan.cetak') }}" method="POST" target="_blank" class="space-y-5">
                            @csrf
                            
                            <div class="space-y-3">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pilih Jenis Dokumen</label>
                                <div class="grid grid-cols-1 gap-3">
                                    
                                    {{-- Opsi 1 --}}
                                    <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-200"
                                           :class="selected == 'siswa_sekolah' ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500 shadow-sm' : 'border-gray-200 hover:bg-gray-50 hover:border-gray-300'">
                                        <input type="radio" name="jenis_laporan" value="siswa_sekolah" x-model="selected" class="sr-only">
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-800">1. Ledger Absensi Sekolah</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Kehadiran harian (Gerbang Masuk/Pulang).</div>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors duration-200"
                                             :class="selected == 'siswa_sekolah' ? 'border-emerald-600 bg-emerald-600' : 'border-gray-300 bg-white'">
                                            <div class="w-2.5 h-2.5 bg-white rounded-full pop-in" x-show="selected == 'siswa_sekolah'"></div>
                                        </div>
                                    </label>

                                    {{-- Opsi 2 --}}
                                    <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-200"
                                           :class="selected == 'siswa_pelajaran' ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500 shadow-sm' : 'border-gray-200 hover:bg-gray-50 hover:border-gray-300'">
                                        <input type="radio" name="jenis_laporan" value="siswa_pelajaran" x-model="selected" class="sr-only">
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-800">2. Ledger Absensi Mapel</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Kehadiran detail per mata pelajaran.</div>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors duration-200"
                                             :class="selected == 'siswa_pelajaran' ? 'border-emerald-600 bg-emerald-600' : 'border-gray-300 bg-white'">
                                            <div class="w-2.5 h-2.5 bg-white rounded-full pop-in" x-show="selected == 'siswa_pelajaran'"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Filter Tambahan (Kondisional) --}}
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 transition-all duration-300">
                                
                                {{-- Filter Absensi Sekolah --}}
                                <div x-show="selected == 'siswa_sekolah'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                                    <label class="text-xs font-bold text-gray-500 mb-1 block">Filter Kelas (Opsional)</label>
                                    <select name="kelas_id_sekolah" class="custom-select block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm h-10">
                                        <option value="">-- Semua Kelas --</option>
                                        @foreach($kelasList as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-400 mt-2 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Kosongkan untuk mencetak semua siswa.
                                    </p>
                                </div>

                                {{-- Filter Absensi Pelajaran --}}
                                <div x-show="selected == 'siswa_pelajaran'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-xs font-bold text-gray-500 mb-1 block">Pilih Kelas <span class="text-red-500">*</span></label>
                                            <select name="kelas_id" class="custom-select block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm h-10">
                                                <option value="">-- Pilih Kelas --</option>
                                                @foreach($kelasList as $kelas)
                                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-gray-500 mb-1 block">Filter Mapel (Opsional)</label>
                                            <select name="mapel_id" class="custom-select block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm h-10">
                                                <option value="">-- Semua Mata Pelajaran --</option>
                                                @foreach($mapelList as $mapel)
                                                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-2">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 mb-1 block">Bulan</label>
                                    <select name="bulan" class="custom-select block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-semibold text-gray-700">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}" @selected($i == now()->month)>{{ \Carbon\Carbon::create()->month($i)->locale('id')->isoFormat('MMMM') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 mb-1 block">Tahun</label>
                                    <select name="tahun" class="custom-select block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-semibold text-gray-700">
                                        @for($y=now()->year; $y>=2023; $y--)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-emerald-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-emerald-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak Ledger Siswa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>