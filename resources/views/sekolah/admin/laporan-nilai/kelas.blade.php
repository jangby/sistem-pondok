<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('sekolah.admin.kegiatan-akademik.index') }}" class="hover:text-indigo-600 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Kegiatan
                    </a>
                    <span>/</span>
                    <span>Kelola Nilai</span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                    Input Nilai Per Kelas
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. HERO SECTION (Diperjelas & Dipertajam) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden relative">
                {{-- Aksen Warna Kiri --}}
                <div class="absolute left-0 top-0 bottom-0 w-2 bg-indigo-600"></div>
                
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between gap-6 items-center">
                    <div class="w-full">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <span class="px-3 py-1 rounded-md bg-indigo-600 text-white text-xs font-bold uppercase tracking-wide">
                                {{ $kegiatan->tipe }}
                            </span>
                            <span class="flex items-center text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-md">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}
                            </span>
                        </div>
                        
                        <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight leading-tight">
                            {{ $kegiatan->nama_kegiatan }}
                        </h1>
                        
                        @if($kegiatan->keterangan)
                        <div class="mt-3 flex items-start gap-2 text-gray-600 text-sm max-w-2xl bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <svg class="w-5 h-5 text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p>{{ $kegiatan->keterangan }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Stats Box --}}
                    <div class="flex-shrink-0 flex gap-4">
                        <div class="text-center px-4 py-3 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Kelas</div>
                            <div class="text-2xl font-black text-indigo-600">{{ $kelasList->count() }}</div>
                        </div>
                        <div class="text-center px-4 py-3 bg-emerald-50 rounded-xl border border-emerald-100">
                            <div class="text-xs text-emerald-600 uppercase font-bold tracking-wider">Selesai</div>
                            <div class="text-2xl font-black text-emerald-600">{{ $kelasList->where('completion', 100)->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. SEARCH BAR --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <h3 class="text-lg font-bold text-gray-800">Daftar Kelas</h3>
                <form method="GET" class="w-full sm:w-96">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition" 
                               placeholder="Cari nama kelas (cth: 7A)...">
                    </div>
                </form>
            </div>

            {{-- 3. COMPACT LIST VIEW (Pengganti Card Grid) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Nama Kelas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/2">Status Pengisian Nilai</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($kelasList as $kelas)
                                @php
                                    $progress = $kelas->completion;
                                    // Tentukan Warna
                                    if($progress == 100) {
                                        $barColor = 'bg-emerald-500';
                                        $textColor = 'text-emerald-700';
                                        $badge = '<span class="ml-2 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">SELESAI</span>';
                                    } elseif($progress > 50) {
                                        $barColor = 'bg-blue-500';
                                        $textColor = 'text-blue-700';
                                        $badge = '';
                                    } else {
                                        $barColor = 'bg-amber-400';
                                        $textColor = 'text-amber-700';
                                        $badge = '';
                                    }
                                @endphp
                                <tr class="group hover:bg-indigo-50/30 transition-colors duration-150">
                                    {{-- Nama Kelas --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm border border-gray-200">
                                                {{ substr($kelas->nama_kelas, 0, 3) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 flex items-center">
                                                    {{ $kelas->nama_kelas }}
                                                    {!! $badge !!}
                                                </div>
                                                <div class="text-xs text-gray-500">Tingkat {{ $kelas->tingkat }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Progress Bar Panjang --}}
                                    <td class="px-6 py-4 align-middle">
                                        <div class="w-full max-w-md">
                                            <div class="flex justify-between text-xs mb-1">
                                                <span class="font-medium text-gray-500">Progress</span>
                                                <span class="font-bold {{ $textColor }}">{{ $progress }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden border border-gray-100">
                                                <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Tombol Aksi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('sekolah.admin.kegiatan-akademik.kelola.mapel', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id]) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                            Kelola Nilai
                                            <svg class="w-4 h-4 ml-2 -mr-1 text-gray-400 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-gray-500 text-sm">Tidak ada kelas yang ditemukan.</p>
                                            @if(request('search'))
                                                <a href="{{ url()->current() }}" class="mt-2 text-indigo-600 hover:underline text-xs">Reset Pencarian</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>