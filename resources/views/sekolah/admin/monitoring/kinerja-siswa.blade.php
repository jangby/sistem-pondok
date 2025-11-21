<x-app-layout>
    {{-- STYLE KHUSUS (INDEPENDEN & MEWAH) --}}
    @push('styles')
    <style>
        /* Background Mewah (Tema Emerald/Academic) */
        .bg-academic {
            background: radial-gradient(circle at top right, #10b981 0%, #059669 40%, #064e3b 100%);
        }
        
        /* Efek Kaca Premium */
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(5, 150, 105, 0.07);
        }
        
        /* Animasi Kartu Juara */
        .card-winner {
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .card-winner:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        
        /* Gradien Ranking */
        .gradient-gold { background: linear-gradient(135deg, #FFD700 0%, #FDB931 50%, #C5960C 100%); }
        .text-gradient-gold { 
            background: linear-gradient(135deg, #FFD700 0%, #d69e2e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gradient-silver { background: linear-gradient(135deg, #E0E0E0 0%, #BDBDBD 100%); }
        .gradient-bronze { background: linear-gradient(135deg, #E6AA68 0%, #CA8A4B 100%); }

        /* Progress Bar Halus */
        .progress-bar-container {
            background: rgba(0,0,0,0.06);
            border-radius: 999px;
            overflow: hidden;
            height: 10px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <span class="text-3xl">🎓</span> Ranking Kedisiplinan Siswa
                </h2>
                <p class="text-sm text-gray-500 mt-1 font-medium">
                    Periode: <span class="text-emerald-600 font-bold uppercase">{{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}</span>
                </p>
            </div>
            
            {{-- Filter Form --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('sekolah.admin.monitoring.siswa') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition mr-2">
                    &larr; Dashboard
                </a>

                <form method="GET" class="flex items-center gap-2 bg-white p-1.5 rounded-xl border border-gray-300 shadow-sm">
                    <select name="bulan" class="bg-transparent font-bold text-sm text-gray-700 py-2 px-3 rounded-lg hover:bg-gray-50 focus:outline-none cursor-pointer border-none">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" @selected($i == $bulan)>{{ \Carbon\Carbon::create()->month($i)->locale('id')->isoFormat('MMMM') }}</option>
                        @endfor
                    </select>
                    <div class="w-px h-6 bg-gray-300"></div>
                    <select name="tahun" class="bg-transparent font-bold text-sm text-gray-700 py-2 px-3 rounded-lg hover:bg-gray-50 focus:outline-none cursor-pointer border-none">
                        @for($y=now()->year; $y>=2023; $y--)
                            <option value="{{ $y }}" @selected($y == $tahun)>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-emerald-600 text-white p-2 rounded-lg hover:bg-emerald-700 shadow-md transition-transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-10 min-h-screen relative overflow-hidden" style="background-color: #f0fdf4;">
        
        {{-- Dekorasi Latar Belakang (Blob Hijau) --}}
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-emerald-100/80 to-transparent pointer-events-none"></div>
        <div class="absolute top-10 right-10 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-10 left-10 w-96 h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12 relative z-10">

            @if(count($laporan) > 0)
                
                {{-- 1. PODIUM JUARA SISWA --}}
                <div class="flex flex-col md:flex-row justify-center items-end gap-6 px-2 mb-16">
                    
                    {{-- RANK 2 (SILVER) --}}
                    @if(isset($laporan[1]))
                    <div class="order-2 md:order-1 w-full md:w-1/3 card-winner">
                        <div class="bg-white rounded-3xl shadow-xl p-6 border-t-4 border-gray-300 flex flex-col items-center relative overflow-hidden">
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2">
                                <span class="bg-gray-200 text-gray-700 text-xs font-black px-4 py-1 rounded-b-lg uppercase tracking-widest shadow-sm">Rank 2</span>
                            </div>

                            <div class="mt-6 mb-4 relative">
                                <div class="w-24 h-24 rounded-full gradient-silver p-1 shadow-lg">
                                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-3xl font-black text-gray-400 uppercase">
                                        {{ substr($laporan[1]->nama, 0, 2) }}
                                    </div>
                                </div>
                                <div class="absolute -bottom-2 -right-2 bg-gray-100 rounded-full p-1.5 border-2 border-white shadow-sm text-sm">🥈</div>
                            </div>

                            <h3 class="font-bold text-gray-800 text-lg text-center line-clamp-1 w-full">{{ $laporan[1]->nama }}</h3>
                            <p class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-0.5 rounded-full mb-4 mt-1">{{ $laporan[1]->kelas }}</p>
                            
                            <div class="w-full text-center px-4">
                                <div class="text-4xl font-black text-gray-700">{{ $laporan[1]->skor }}<span class="text-lg text-gray-400">%</span></div>
                                <div class="text-[10px] text-gray-400 uppercase font-bold mt-1">Skor Disiplin</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- RANK 1 (GOLD - EMERALD THEME) --}}
                    @if(isset($laporan[0]))
                    <div class="order-1 md:order-2 w-full md:w-1/3 card-winner z-20 -mt-10 md:-mt-0 transform md:-translate-y-6">
                        <div class="bg-academic rounded-3xl shadow-2xl shadow-emerald-900/30 p-8 border border-emerald-400/30 flex flex-col items-center relative overflow-hidden">
                            
                            {{-- Efek Kilau --}}
                            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                            <div class="absolute -top-20 -right-20 w-60 h-60 bg-emerald-400 rounded-full blur-[80px] opacity-30"></div>

                            <div class="relative z-10 -mt-12 mb-6 flex flex-col items-center">
                                <div class="text-4xl mb-1 filter drop-shadow-lg">🎓</div>
                                <div class="gradient-gold text-yellow-900 text-sm font-black px-8 py-2 rounded-full shadow-lg uppercase tracking-widest border-2 border-yellow-200">
                                    Siswa Teladan
                                </div>
                            </div>

                            <div class="relative z-10 w-32 h-32 rounded-full p-1.5 gradient-gold shadow-[0_0_40px_rgba(255,215,0,0.4)] mb-5">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-5xl font-black text-emerald-900 uppercase">
                                    {{ substr($laporan[0]->nama, 0, 2) }}
                                </div>
                            </div>
                            
                            <div class="relative z-10 text-center w-full text-white px-2">
                                <h3 class="font-black text-2xl line-clamp-1 w-full mb-1 tracking-tight">{{ $laporan[0]->nama }}</h3>
                                <p class="text-emerald-200 text-sm font-bold mb-6 tracking-wide bg-white/10 inline-block px-4 py-1 rounded-full backdrop-blur-sm">
                                    Kelas {{ $laporan[0]->kelas }}
                                </p>
                                
                                <div class="bg-emerald-900/40 backdrop-blur-md rounded-2xl p-4 border border-emerald-400/20">
                                    <div class="flex items-end justify-center gap-1">
                                        <div class="text-6xl font-black text-gradient-gold drop-shadow-sm">{{ $laporan[0]->skor }}</div>
                                        <div class="text-xl font-bold text-emerald-200 mb-3">%</div>
                                    </div>
                                    <div class="w-full bg-white/10 rounded-full h-2 mt-2">
                                        <div class="bg-gradient-to-r from-yellow-300 to-yellow-500 h-2 rounded-full" style="width: {{ $laporan[0]->skor }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- RANK 3 (BRONZE) --}}
                    @if(isset($laporan[2]))
                    <div class="order-3 md:order-3 w-full md:w-1/3 card-winner">
                        <div class="bg-white rounded-3xl shadow-xl p-6 border-t-4 border-orange-300 flex flex-col items-center relative overflow-hidden">
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2">
                                <span class="bg-orange-100 text-orange-800 text-xs font-black px-4 py-1 rounded-b-lg uppercase tracking-widest shadow-sm">Rank 3</span>
                            </div>

                            <div class="mt-6 mb-4 relative">
                                <div class="w-24 h-24 rounded-full gradient-bronze p-1 shadow-lg">
                                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-3xl font-black text-gray-400 uppercase">
                                        {{ substr($laporan[2]->nama, 0, 2) }}
                                    </div>
                                </div>
                                <div class="absolute -bottom-2 -right-2 bg-orange-50 rounded-full p-1.5 border-2 border-white shadow-sm text-sm">🥉</div>
                            </div>

                            <h3 class="font-bold text-gray-800 text-lg text-center line-clamp-1 w-full">{{ $laporan[2]->nama }}</h3>
                            <p class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-0.5 rounded-full mb-4 mt-1">{{ $laporan[2]->kelas }}</p>
                            
                            <div class="w-full text-center px-4">
                                <div class="text-4xl font-black text-gray-700">{{ $laporan[2]->skor }}<span class="text-lg text-gray-400">%</span></div>
                                <div class="text-[10px] text-gray-400 uppercase font-bold mt-1">Skor Disiplin</div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- 2. TABEL DATA LENGKAP --}}
                <div class="glass-panel rounded-3xl overflow-hidden">
                    <div class="p-6 border-b border-gray-200/50 flex flex-col md:flex-row justify-between items-center gap-4 bg-white/60">
                        <div>
                            <h3 class="font-black text-gray-800 text-xl tracking-tight">Daftar Peringkat Siswa</h3>
                            <p class="text-sm text-gray-500">Analisis kehadiran dan kedisiplinan per kelas.</p>
                        </div>
                        
                        {{-- Legend --}}
                        <div class="flex gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[10px] font-bold uppercase text-gray-500">Tepat Waktu</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                                <span class="text-[10px] font-bold uppercase text-gray-500">Terlambat</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50/80 text-gray-500 uppercase text-xs font-extrabold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 text-left w-16">#</th>
                                    <th class="px-6 py-4 text-left">Siswa</th>
                                    <th class="px-6 py-4 text-left">Kelas</th>
                                    <th class="px-6 py-4 text-center w-1/3">Statistik Disiplin</th>
                                    <th class="px-6 py-4 text-right">Skor Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white/80">
                                @foreach ($laporan as $index => $row)
                                    <tr class="hover:bg-emerald-50/40 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 font-black flex items-center justify-center text-xs group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 shadow-sm flex items-center justify-center font-black text-gray-400 group-hover:scale-110 transition-transform uppercase">
                                                    {{ substr($row->nama, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800 text-sm group-hover:text-emerald-700 transition-colors">{{ $row->nama }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold border border-gray-200">
                                                {{ $row->kelas }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-middle">
                                            <div class="flex flex-col gap-1.5 max-w-xs mx-auto">
                                                {{-- Stacked Bar --}}
                                                <div class="flex h-2.5 w-full rounded-full overflow-hidden bg-gray-100 shadow-inner ring-1 ring-gray-200/50">
                                                    @php
                                                        // Mencegah pembagian nol
                                                        $total = $row->hadir > 0 ? $row->hadir : 1; 
                                                        $pTepat = ($row->tepat_waktu / $total) * 100;
                                                        $pTelat = ($row->terlambat / $total) * 100;
                                                    @endphp
                                                    <div style="width: {{ $pTepat }}%; background: #10b981;" title="Tepat Waktu"></div>
                                                    <div style="width: {{ $pTelat }}%; background: #fbbf24;" title="Terlambat"></div>
                                                </div>
                                                
                                                <div class="flex justify-between text-[10px] font-bold uppercase text-gray-400 group-hover:text-gray-600 transition-colors">
                                                    <span class="text-emerald-600">{{ $row->tepat_waktu }} Tepat</span>
                                                    <span class="text-amber-500">{{ $row->terlambat }} Telat</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            @php
                                                $scoreClass = $row->skor >= 90 ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 
                                                             ($row->skor >= 75 ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : 
                                                             'bg-red-100 text-red-700 border-red-200');
                                            @endphp
                                            <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-xl font-black text-sm border {{ $scoreClass }}">
                                                {{ $row->skor }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                {{-- Empty State Premium --}}
                <div class="glass-panel rounded-3xl p-16 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mb-6 shadow-inner border border-emerald-100 animate-pulse">
                        <svg class="w-12 h-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Belum Ada Data Siswa</h3>
                    <p class="text-gray-500 mt-2 max-w-md mx-auto text-sm leading-relaxed">
                        Tidak ada rekaman absensi yang ditemukan untuk periode bulan <strong class="text-emerald-600">{{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }}</strong>.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>