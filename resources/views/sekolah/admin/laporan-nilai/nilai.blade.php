<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('sekolah.admin.kegiatan-akademik.kelola.kelas', $kegiatan->id) }}" class="hover:text-indigo-600 transition">Kelas {{ $kelas->nama_kelas }}</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <a href="{{ route('sekolah.admin.kegiatan-akademik.kelola.mapel', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id]) }}" class="hover:text-indigo-600 transition">Mapel</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span>Nilai</span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                    Detail Nilai Siswa
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. HERO & STATS --}}
            @php
                // Hitung Statistik Sederhana di View
                $nilaiCollection = $existingNilai->filter(fn($val) => !is_null($val));
                $countTerisi = $nilaiCollection->count();
                $countTotal = $santris->count();
                $rataRata = $countTerisi > 0 ? round($nilaiCollection->avg(), 1) : 0;
                $tertinggi = $countTerisi > 0 ? $nilaiCollection->max() : 0;
                $terendah = $countTerisi > 0 ? $nilaiCollection->min() : 0;
                
                $persenSelesai = $countTotal > 0 ? round(($countTerisi / $countTotal) * 100) : 0;
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Info Mapel --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-indigo-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full blur-2xl -mr-6 -mt-6"></div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Mata Pelajaran</h3>
                    <div class="text-2xl font-black text-gray-900">{{ $mapel->nama_mapel }}</div>
                    <div class="text-sm text-gray-500 mt-1 font-mono">{{ $mapel->kode_mapel ?? '-' }}</div>
                    <div class="mt-4 inline-flex items-center px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">
                        {{ $kegiatan->nama_kegiatan }}
                    </div>
                </div>

                {{-- Stats Progress --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Progress Input</h3>
                        <div class="flex items-end gap-2">
                            <div class="text-3xl font-black text-gray-800">{{ $persenSelesai }}<span class="text-lg text-gray-400">%</span></div>
                            <div class="text-sm text-gray-500 mb-1.5">{{ $countTerisi }} dari {{ $countTotal }} Siswa</div>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-3">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-1000" style="width: {{ $persenSelesai }}%"></div>
                    </div>
                </div>

                {{-- Stats Nilai --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Statistik Kelas</h3>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-100">
                            <div class="text-[10px] text-emerald-600 font-bold uppercase">Rata-rata</div>
                            <div class="text-lg font-black text-emerald-700">{{ $rataRata }}</div>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="text-[10px] text-blue-600 font-bold uppercase">Tertinggi</div>
                            <div class="text-lg font-black text-blue-700">{{ $tertinggi }}</div>
                        </div>
                        <div class="p-2 bg-rose-50 rounded-lg border border-rose-100">
                            <div class="text-[10px] text-rose-600 font-bold uppercase">Terendah</div>
                            <div class="text-lg font-black text-rose-700">{{ $terendah }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. TOOLBAR (Search & Actions) --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <form method="GET" class="w-full md:w-96 relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition" 
                           placeholder="Cari siswa...">
                </form>

                <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    {{-- Tombol Cetak --}}
                    <div class="flex bg-white rounded-xl shadow-sm border border-gray-200 p-1">
                        <a href="{{ route('sekolah.admin.kegiatan-akademik.cetak.daftar-hadir', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id]) }}" target="_blank" class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Absensi
                        </a>
                        <div class="w-px bg-gray-200 my-1"></div>
                        <a href="{{ route('sekolah.admin.kegiatan-akademik.cetak.format-nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id]) }}" target="_blank" class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Format
                        </a>
                        <div class="w-px bg-gray-200 my-1"></div>
                        <a href="{{ route('sekolah.admin.kegiatan-akademik.cetak.nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id]) }}" target="_blank" class="px-3 py-1.5 text-xs font-bold text-indigo-600 hover:bg-indigo-50 rounded-lg transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Ledger
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3. STUDENT LIST TABLE --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Status</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($santris as $index => $santri)
                                @php
                                    $nilai = $existingNilai[$santri->id] ?? null;
                                    $isMissing = is_null($nilai);
                                    
                                    // Visual Nilai
                                    $nilaiClass = 'text-gray-800';
                                    $bgNilai = 'bg-gray-50 border-gray-200';
                                    
                                    if (!$isMissing) {
                                        if ($nilai >= 85) {
                                            $nilaiClass = 'text-emerald-600';
                                            $bgNilai = 'bg-emerald-50 border-emerald-100';
                                        } elseif ($nilai < 70) {
                                            $nilaiClass = 'text-rose-600';
                                            $bgNilai = 'bg-rose-50 border-rose-100';
                                        } else {
                                            $nilaiClass = 'text-blue-600';
                                            $bgNilai = 'bg-blue-50 border-blue-100';
                                        }
                                    }

                                    // Avatar Initials
                                    $initials = collect(explode(' ', $santri->full_name))->take(2)->map(fn($s) => substr($s, 0, 1))->join('');
                                    $colors = ['bg-blue-100 text-blue-600', 'bg-purple-100 text-purple-600', 'bg-orange-100 text-orange-600', 'bg-teal-100 text-teal-600'];
                                    $avatarColor = $colors[$santri->id % count($colors)];
                                @endphp
                                <tr class="group hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-9 w-9 rounded-full {{ $avatarColor }} flex items-center justify-center text-xs font-bold border border-white shadow-sm">
                                                {{ $initials }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $santri->full_name }}</div>
                                                <div class="text-xs text-gray-500 font-mono">{{ $santri->nis }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($isMissing)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                                Kosong
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Terisi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-10 rounded-lg border {{ $bgNilai }}">
                                            <span class="text-lg font-black {{ $nilaiClass }}">
                                                {{ $isMissing ? '-' : number_format($nilai, 0) }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <p>Tidak ada siswa ditemukan.</p>
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