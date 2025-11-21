<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                    Monitoring Guru & Staff
                </h2>
                <div class="flex items-center gap-3 mt-1 text-sm font-medium text-gray-500">
                    <span class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-green-100 text-green-700 border border-green-200">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-500 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-600"></span>
                        </span>
                        Live Attendance
                    </span>
                    <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                {{-- JAM DIGITAL --}}
                <div class="hidden lg:block text-right mr-4">
                    <div id="digital-clock" class="text-3xl font-black text-gray-800 font-mono leading-none tracking-tight">00:00:00</div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Waktu Server</div>
                </div>

                {{-- ACTIONS GROUP --}}
                <div class="flex items-center gap-2">
                    {{-- Tombol Laporan Kinerja (DIKEMBALIKAN) --}}
                    <a href="{{ route('sekolah.admin.kinerja.guru') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition-all h-10">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan
                    </a>

                    {{-- Tab Switcher --}}
                    <div class="bg-white p-1 rounded-xl border border-gray-200 shadow-sm flex h-10 items-center">
                        <span class="px-4 py-1.5 text-sm font-bold text-white bg-indigo-600 rounded-lg shadow-sm cursor-default">
                            Guru
                        </span>
                        <a href="{{ route('sekolah.admin.monitoring.siswa') }}" class="px-4 py-1.5 text-sm font-medium text-gray-500 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-all">
                            Siswa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('error') || isset($error))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg flex items-center gap-3 shadow-sm">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="font-bold text-red-800">Konfigurasi Belum Lengkap</p>
                        <p class="text-sm text-red-600">{{ session('error') ?? $error }}</p>
                    </div>
                </div>
            @elseif($isHariLibur || !$isHariKerja)
                {{-- TAMPILAN LIBUR --}}
                <div class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl shadow-sm border border-gray-200 text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))]"></div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-orange-50 text-orange-500 rounded-3xl flex items-center justify-center mb-6 mx-auto shadow-sm border border-orange-100 rotate-3">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-800">Hari Libur</h3>
                        <p class="text-gray-500 mt-2 font-medium">
                            @if($isHariLibur)
                                Hari ini tercatat sebagai hari libur nasional/sekolah.
                            @else
                                Hari ini ({{ $namaHariIni }}) bukan hari kerja aktif.
                            @endif
                        </p>
                    </div>
                </div>
            @else
                
                @php
                    $totalGuru = count($daftarHadir) + count($daftarBelumHadir);
                @endphp

                {{-- 1. KPI CARDS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    
                    {{-- Card: Total --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-between h-32 relative overflow-hidden group hover:border-indigo-300 transition-colors">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Guru</p>
                                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $totalGuru }}</h3>
                            </div>
                            <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        </div>
                        <div class="relative z-10 w-full bg-gray-100 h-1.5 rounded-full mt-auto overflow-hidden">
                            <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>

                    {{-- Card: Hadir --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-between h-32 relative overflow-hidden group hover:border-emerald-300 transition-colors">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Hadir</p>
                                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $kpi_hadir }}</h3>
                            </div>
                            <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        @php $persenHadir = $totalGuru > 0 ? ($kpi_hadir / $totalGuru) * 100 : 0; @endphp
                        <div class="relative z-10 w-full bg-gray-100 h-1.5 rounded-full mt-auto overflow-hidden">
                            <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $persenHadir }}%"></div>
                        </div>
                    </div>

                    {{-- Card: Terlambat --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-between h-32 relative overflow-hidden group hover:border-amber-300 transition-colors">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-amber-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Terlambat</p>
                                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $kpi_terlambat }}</h3>
                            </div>
                            <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        @php $persenTelat = $totalGuru > 0 ? ($kpi_terlambat / $totalGuru) * 100 : 0; @endphp
                        <div class="relative z-10 w-full bg-gray-100 h-1.5 rounded-full mt-auto overflow-hidden">
                            <div class="bg-amber-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $persenTelat }}%"></div>
                        </div>
                    </div>

                    {{-- Card: Belum Hadir --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-between h-32 relative overflow-hidden group hover:border-rose-300 transition-colors">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-rose-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold text-rose-600 uppercase tracking-wider">Belum Hadir</p>
                                <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $kpi_alpa }}</h3>
                            </div>
                            <div class="p-2 bg-rose-100 text-rose-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            </div>
                        </div>
                        @php $persenAlpa = $totalGuru > 0 ? ($kpi_alpa / $totalGuru) * 100 : 0; @endphp
                        <div class="relative z-10 w-full bg-gray-100 h-1.5 rounded-full mt-auto overflow-hidden">
                            <div class="bg-rose-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $persenAlpa }}%"></div>
                        </div>
                    </div>
                </div>
                
                {{-- ALERT JAM KOSONG --}}
                @if($kpi_jam_kosong > 0)
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-4 text-white shadow-lg flex flex-col md:flex-row items-center justify-between animate-pulse-slow">
                    <div class="flex items-center gap-4 mb-3 md:mb-0">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-xl tracking-tight">PERHATIAN: {{ $kpi_jam_kosong }} Jam Kosong!</h4>
                            <p class="text-indigo-100 text-sm opacity-90">Terdeteksi kelas tanpa kehadiran guru saat ini. Segera tindak lanjuti.</p>
                        </div>
                    </div>
                    <a href="{{ route('sekolah.admin.guru-pengganti.index') }}" class="whitespace-nowrap bg-white text-indigo-700 px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-indigo-50 transition shadow-sm hover:shadow-md">
                        Cari Guru Pengganti &rarr;
                    </a>
                </div>
                @endif

                {{-- 2. SPLIT VIEW (33% List - 66% Timeline) --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[650px]">
                    
                    {{-- KIRI: BELUM HADIR --}}
                    <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col overflow-hidden h-full">
                        {{-- Header --}}
                        <div class="p-4 border-b border-gray-100 bg-gray-50/50 space-y-3">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-sm shadow-rose-200"></span>
                                    Belum Hadir
                                </h3>
                                <span class="bg-gray-200 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-md">{{ count($daftarBelumHadir) }}</span>
                            </div>
                            
                            {{-- Search --}}
                            <div class="relative">
                                <input type="text" id="search-guru" placeholder="Cari nama guru..." 
                                    class="w-full pl-9 pr-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm transition-all placeholder-gray-400">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>

                        {{-- List --}}
                        <div class="overflow-y-auto flex-1 p-3 space-y-2 bg-white" id="list-belum-hadir">
                            @forelse ($daftarBelumHadir as $item)
                                @php
                                    // Polymorphic check: $item bisa berupa AbsensiGuru (sakit/izin) atau User (alpa)
                                    $isAbsenRecord = $item instanceof \App\Models\Sekolah\AbsensiGuru;
                                    $guru = $isAbsenRecord ? $item->guru : $item;
                                    $status = $isAbsenRecord ? $item->status : 'alpa';
                                    
                                    $bgItem = match($status) {
                                        'sakit' => 'bg-yellow-50 border-yellow-100 hover:border-yellow-300',
                                        'izin' => 'bg-blue-50 border-blue-100 hover:border-blue-300',
                                        default => 'bg-white border-gray-100 hover:border-rose-200 hover:shadow-sm'
                                    };
                                    $textBadge = match($status) {
                                        'sakit' => 'text-yellow-700 bg-yellow-100 border border-yellow-200',
                                        'izin' => 'text-blue-700 bg-blue-100 border border-blue-200',
                                        default => 'text-rose-700 bg-rose-50 border border-rose-100'
                                    };
                                    $inisial = substr($guru->name, 0, 1);
                                @endphp
                                <div class="guru-item flex items-center justify-between p-3 rounded-xl border {{ $bgItem }} transition-all duration-200 cursor-default group" data-nama="{{ strtolower($guru->name) }}">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-sm font-bold border border-gray-200 group-hover:bg-white group-hover:shadow-sm transition-all">
                                            {{ $inisial }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-gray-800 text-sm truncate leading-tight group-hover:text-indigo-700">{{ $guru->name }}</div>
                                            @if($isAbsenRecord && $item->keterangan)
                                                <div class="text-[10px] text-gray-500 truncate mt-0.5 italic">"{{ $item->keterangan }}"</div>
                                            @else
                                                <div class="text-[10px] text-gray-400 truncate mt-0.5">Belum Absen</div>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="flex-shrink-0 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide {{ $textBadge }}">
                                        {{ $status }}
                                    </span>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center h-full text-gray-400 py-10">
                                    <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Semua guru sudah hadir!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- KANAN: TIMELINE (2/3) --}}
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col overflow-hidden h-full relative">
                        {{-- Header --}}
                        <div class="p-4 border-b border-gray-100 bg-emerald-50/50 flex justify-between items-center z-10">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                Riwayat Kehadiran
                            </h3>
                            <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200">
                                {{ count($daftarHadir) }} Guru
                            </span>
                        </div>

                        {{-- Timeline List --}}
                        <div class="overflow-y-auto flex-1 p-6 relative bg-white">
                            {{-- Garis Timeline --}}
                            <div class="absolute left-[27px] top-6 bottom-0 w-px bg-gray-200 z-0"></div>

                            @php 
                                $sortedHadir = collect($daftarHadir)->sortByDesc('jam_masuk');
                            @endphp

                            @forelse ($sortedHadir as $index => $absen)
                                @php
                                    $jam = \Carbon\Carbon::parse($absen->jam_masuk);
                                    $telat = $absen->jam_masuk > $settings->batas_telat;
                                    
                                    $dotColor = $telat ? 'bg-amber-500 ring-amber-100' : 'bg-emerald-500 ring-emerald-100';
                                    $cardBorder = $index === 0 ? ($telat ? 'border-amber-200 ring-2 ring-amber-50' : 'border-emerald-200 ring-2 ring-emerald-50') : 'border-gray-200';
                                    $animasi = $index === 0 ? 'animate-pulse-slow' : '';
                                @endphp
                                
                                <div class="relative pl-10 mb-5 z-10 group">
                                    {{-- Dot --}}
                                    <div class="absolute left-0 top-3 w-3.5 h-3.5 rounded-full {{ $dotColor }} ring-4 border-2 border-white"></div>
                                    
                                    {{-- Card --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 bg-white border {{ $cardBorder }} rounded-xl shadow-sm hover:shadow-md transition-all {{ $animasi }}">
                                        <div class="flex items-center gap-4">
                                            {{-- Avatar --}}
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-blue-100 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100 text-sm shadow-sm">
                                                {{ substr($absen->guru->name, 0, 1) }}
                                            </div>
                                            
                                            <div>
                                                <div class="font-bold text-gray-800 text-sm">{{ $absen->guru->name }}</div>
                                                <div class="text-xs text-gray-500 flex items-center gap-2">
                                                    <span class="font-mono">{{ $absen->guru->email }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 sm:mt-0 flex items-center gap-4 justify-end pl-14 sm:pl-0">
                                            <div class="text-right">
                                                <div class="font-mono text-lg font-black text-gray-800 leading-none tracking-tight">
                                                    {{ $jam->format('H:i') }}
                                                </div>
                                                <div class="text-[10px] text-gray-400 uppercase font-bold mt-0.5">Waktu Masuk</div>
                                            </div>
                                            
                                            @if($telat)
                                                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-100 uppercase tracking-wide">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Terlambat
                                                </div>
                                            @else
                                                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-100 uppercase tracking-wide">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Tepat Waktu
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center h-full text-gray-400 mt-10">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 animate-pulse">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-lg font-medium text-gray-500">Menunggu data kehadiran...</p>
                                    <p class="text-sm text-gray-400">Data akan muncul otomatis secara real-time.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // 1. Auto Refresh (60 Detik)
        setTimeout(function(){ window.location.reload(1); }, 60000);

        // 2. Jam Digital
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-GB', { hour12: false });
            const clockEl = document.getElementById('digital-clock');
            if(clockEl) clockEl.innerText = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 3. Live Search Guru
        const searchInput = document.getElementById('search-guru');
        if(searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                const items = document.querySelectorAll('.guru-item');
                
                items.forEach(item => {
                    const name = item.getAttribute('data-nama');
                    if(name.includes(term)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        }
    </script>
    <style>
        @keyframes pulse-slow {
            0%, 100% { box-shadow: 0 0 0 0px rgba(16, 185, 129, 0); border-color: #d1fae5; }
            50% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.1); border-color: #10b981; }
        }
        .animate-pulse-slow { animation: pulse-slow 3s infinite; }
    </style>
    @endpush
</x-app-layout>