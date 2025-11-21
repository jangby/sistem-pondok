<x-app-layout>
    {{-- STYLE KHUSUS (INDEPENDEN) --}}
    @push('styles')
    <style>
        /* Efek Juara 1 yang lebih menonjol */
        .rank-1-card {
            transform: scale(1.05);
            border-color: #F59E0B; /* Amber 500 */
            box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.15), 0 10px 10px -5px rgba(245, 158, 11, 0.1);
            z-index: 10;
        }
        /* Progress Bar Custom */
        .progress-stack {
            display: flex;
            height: 0.75rem;
            overflow: hidden;
            background-color: #F3F4F6; /* Gray 100 */
            border-radius: 9999px;
        }
    </style>
    @endpush

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER FILTER & NAVIGASI --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                <div>
                    {{-- TOMBOL KEMBALI (BARU) --}}
                    <a href="{{ route('sekolah.admin.monitoring.guru') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 font-bold mb-2 transition group">
                        <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Dashboard
                    </a>
                    
                    <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                        Laporan Kinerja Guru
                    </h2>
                    <p class="text-sm text-gray-500 font-medium">
                        Periode: <span class="text-indigo-600 font-bold">{{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}</span>
                    </p>
                </div>
                
                {{-- Form Filter --}}
                <form method="GET" class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <select name="bulan" class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 text-sm font-bold rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 pr-8">
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" @selected($i == $bulan)>{{ \Carbon\Carbon::create()->month($i)->locale('id')->isoFormat('MMMM') }}</option>
                            @endfor
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <div class="relative flex-1 md:flex-none">
                        <select name="tahun" class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 text-sm font-bold rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 pr-8">
                            @for($y=now()->year; $y>=2023; $y--)
                                <option value="{{ $y }}" @selected($y == $tahun)>{{ $y }}</option>
                            @endfor
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-xl text-sm p-2.5 text-center inline-flex items-center transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>

            @if(count($laporan) > 0)
                
                {{-- 1. PODIUM 3 BESAR (Layout: 2 - 1 - 3) --}}
                <div class="flex flex-col md:flex-row items-end justify-center gap-6 mb-8 px-2 md:px-0">
                    
                    {{-- RANK 2 (PERAK) --}}
                    @if(isset($laporan[1]))
                    <div class="order-2 md:order-1 w-full md:w-1/3 bg-white rounded-2xl p-6 border-2 border-gray-300 shadow-sm relative flex flex-col items-center transform hover:-translate-y-1 transition">
                        <div class="absolute -top-3 bg-gray-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                            Peringkat 2
                        </div>
                        <div class="w-20 h-20 rounded-full border-4 border-gray-300 p-1 mb-3 mt-2">
                            <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-xl font-bold text-gray-500">
                                {{ substr($laporan[1]->nama, 0, 2) }}
                            </div>
                        </div>
                        <h3 class="font-bold text-gray-800 text-center text-lg line-clamp-1 w-full">{{ $laporan[1]->nama }}</h3>
                        <p class="text-xs text-gray-500 mb-4 font-mono">{{ $laporan[1]->nip ?? '-' }}</p>
                        <div class="text-3xl font-black text-gray-700">{{ $laporan[1]->skor }}<span class="text-sm text-gray-400">%</span></div>
                    </div>
                    @endif

                    {{-- RANK 1 (EMAS - LEBIH BESAR) --}}
                    @if(isset($laporan[0]))
                    <div class="order-1 md:order-2 w-full md:w-1/3 bg-white rounded-2xl p-8 border-4 border-yellow-400 shadow-xl relative flex flex-col items-center rank-1-card">
                        <div class="absolute -top-4 bg-yellow-500 text-white text-sm font-bold px-6 py-1.5 rounded-full uppercase tracking-widest shadow-md flex items-center gap-1">
                            <svg class="w-4 h-4 text-yellow-100" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Juara 1
                        </div>
                        <div class="w-28 h-28 rounded-full border-4 border-yellow-400 p-1 mb-4 mt-2 shadow-sm bg-yellow-50">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-3xl font-black text-yellow-600">
                                {{ substr($laporan[0]->nama, 0, 2) }}
                            </div>
                        </div>
                        <h3 class="font-black text-gray-900 text-center text-xl line-clamp-1 w-full">{{ $laporan[0]->nama }}</h3>
                        <p class="text-sm text-gray-500 mb-4 font-mono">{{ $laporan[0]->nip ?? '-' }}</p>
                        <div class="text-5xl font-black text-yellow-600">{{ $laporan[0]->skor }}<span class="text-xl text-gray-400 font-bold">%</span></div>
                        <p class="text-[10px] text-yellow-600 font-bold uppercase tracking-wide mt-1">Performa Terbaik</p>
                    </div>
                    @endif

                    {{-- RANK 3 (PERUNGGU) --}}
                    @if(isset($laporan[2]))
                    <div class="order-3 md:order-3 w-full md:w-1/3 bg-white rounded-2xl p-6 border-2 border-orange-300 shadow-sm relative flex flex-col items-center transform hover:-translate-y-1 transition">
                        <div class="absolute -top-3 bg-orange-400 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                            Peringkat 3
                        </div>
                        <div class="w-20 h-20 rounded-full border-4 border-orange-300 p-1 mb-3 mt-2">
                            <div class="w-full h-full rounded-full bg-orange-50 flex items-center justify-center text-xl font-bold text-orange-500">
                                {{ substr($laporan[2]->nama, 0, 2) }}
                            </div>
                        </div>
                        <h3 class="font-bold text-gray-800 text-center text-lg line-clamp-1 w-full">{{ $laporan[2]->nama }}</h3>
                        <p class="text-xs text-gray-500 mb-4 font-mono">{{ $laporan[2]->nip ?? '-' }}</p>
                        <div class="text-3xl font-black text-gray-700">{{ $laporan[2]->skor }}<span class="text-sm text-gray-400">%</span></div>
                    </div>
                    @endif

                </div>

                {{-- 2. TABEL LENGKAP --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    {{-- Legend Header --}}
                    <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Peringkat Keseluruhan</h3>
                            <p class="text-xs text-gray-500">Statistik lengkap seluruh guru.</p>
                        </div>
                        <div class="flex gap-3 bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 bg-emerald-500 rounded-sm"></div>
                                <span class="text-[10px] font-bold uppercase text-gray-600">Hadir</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 bg-amber-400 rounded-sm"></div>
                                <span class="text-[10px] font-bold uppercase text-gray-600">Telat</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 bg-blue-500 rounded-sm"></div>
                                <span class="text-[10px] font-bold uppercase text-gray-600">Izin</span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider w-16">#</th>
                                    <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Guru</th>
                                    <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-500 uppercase tracking-wider w-2/5">Statistik Kehadiran</th>
                                    <th class="px-6 py-4 text-right text-xs font-extrabold text-gray-500 uppercase tracking-wider">Total Skor</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($laporan as $index => $row)
                                    <tr class="hover:bg-indigo-50/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm font-bold {{ $index < 3 ? 'text-indigo-600' : 'text-gray-500' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-xs border border-gray-200">
                                                    {{ substr($row->nama, 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800 text-sm">{{ $row->nama }}</p>
                                                    <p class="text-xs text-gray-500 font-mono">{{ $row->nip ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-middle">
                                            <div class="w-full max-w-xs mx-auto">
                                                {{-- Progress Bar Stack --}}
                                                <div class="progress-stack mb-1.5 shadow-inner">
                                                    @php
                                                        $total = $row->hadir + $row->sakit + $row->izin; 
                                                        $pHadir = $total > 0 ? ($row->tepat_waktu / $total) * 100 : 0;
                                                        $pTelat = $total > 0 ? ($row->terlambat / $total) * 100 : 0;
                                                        $pIzin  = $total > 0 ? (($row->sakit + $row->izin) / $total) * 100 : 0;
                                                    @endphp
                                                    <div style="width: {{ $pHadir }}%" class="bg-emerald-500" title="Hadir Tepat Waktu: {{ $row->tepat_waktu }}"></div>
                                                    <div style="width: {{ $pTelat }}%" class="bg-amber-400" title="Terlambat: {{ $row->terlambat }}"></div>
                                                    <div style="width: {{ $pIzin }}%" class="bg-blue-500" title="Izin/Sakit: {{ $row->sakit + $row->izin }}"></div>
                                                </div>
                                                
                                                {{-- Detail Angka --}}
                                                <div class="flex justify-between text-[10px] font-bold uppercase tracking-wide text-gray-400">
                                                    <span><span class="text-emerald-600">{{ $row->tepat_waktu }}</span> Tepat</span>
                                                    <span><span class="text-amber-600">{{ $row->terlambat }}</span> Telat</span>
                                                    <span><span class="text-blue-600">{{ $row->sakit + $row->izin }}</span> Izin</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            @php
                                                // Warna Skor
                                                $scoreClass = 'bg-gray-100 text-gray-700 border-gray-200';
                                                if ($row->skor >= 90) {
                                                    $scoreClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                                } elseif ($row->skor >= 75) {
                                                    $scoreClass = 'bg-amber-100 text-amber-700 border-amber-200';
                                                } else {
                                                    $scoreClass = 'bg-red-100 text-red-700 border-red-200';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg border font-black text-sm {{ $scoreClass }}">
                                                {{ $row->skor }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if(count($laporan) > 10)
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 text-center text-xs text-gray-500">
                            Menampilkan 10 besar.
                        </div>
                    @endif
                </div>

            @else
                {{-- EMPTY STATE --}}
                <div class="bg-white rounded-2xl border-2 border-dashed border-gray-300 p-12 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Belum Ada Data Absensi</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-sm">Tidak ada rekaman kehadiran guru untuk periode bulan ini.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>