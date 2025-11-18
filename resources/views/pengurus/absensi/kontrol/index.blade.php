<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kontrol Pusat Kehadiran') }}
        </h2>
    </x-slot>

    <div class="py-8 pb-32 bg-gray-50 min-h-screen" x-data="{ activeTab: 'laporan' }">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
            
            {{-- ERROR ALERT --}}
            @if($error ?? false)
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-sm flex items-center" role="alert">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <p class="font-bold">Perhatian</p>
                        <p class="text-sm">{{ $error }}</p>
                    </div>
                </div>
            @endif

            {{-- FILTER CARD --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
                <div class="flex justify-between items-center mb-4 border-b border-gray-50 pb-2 flex-wrap gap-3">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <div class="bg-emerald-100 p-1.5 rounded-lg text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </div>
                        Filter Data Absensi
                    </h3>
                    
                    {{-- Tab Switcher --}}
                    <div class="flex bg-gray-100 p-1 rounded-xl">
                        <button @click="activeTab = 'laporan'" 
                            :class="activeTab === 'laporan' ? 'bg-white text-red-600 shadow-sm ring-1 ring-gray-200' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-2 text-sm font-bold rounded-lg transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Laporan Pelanggaran
                        </button>
                        <button @click="activeTab = 'ledger'" 
                            :class="activeTab === 'ledger' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-gray-200' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-2 text-sm font-bold rounded-lg transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Ledger Lengkap
                        </button>
                    </div>
                </div>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                    
                    {{-- Row 1: Waktu & Kategori --}}
                    <div class="md:col-span-2 grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-xl border-gray-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 flex justify-between">
                            Kategori Absensi <span class="text-[10px] text-red-500 normal-case" x-show="$el.closest('form').querySelector('[name=start_date]').value !== $el.closest('form').querySelector('[name=end_date]').value">(Wajib jika > 1 hari)</span>
                        </label>
                        <select name="kategori" class="w-full rounded-xl border-gray-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
                            <option value="">-- Semua (Hanya Mode Harian) --</option>
                            @foreach($listKategori as $key => $label)
                                <option value="{{ $key }}" {{ $kategoriFilter == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Row 2: Filter Data Santri --}}
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1">Kelas</label>
                        <select name="kelas_id" class="w-full rounded-xl border-gray-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
                            <option value="">Semua</option>
                            @foreach($daftarKelas as $k)
                                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1">Putra / Putri</label>
                        <select name="gender" class="w-full rounded-xl border-gray-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
                            <option value="">Semua</option>
                            <option value="Laki-laki" {{ $gender == 'Laki-laki' ? 'selected' : '' }}>Putra</option>
                            <option value="Perempuan" {{ $gender == 'Perempuan' ? 'selected' : '' }}>Putri</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1">Cari Nama / NIS</label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama..." class="w-full rounded-xl border-gray-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
                    </div>

                    {{-- Tombol Action --}}
                    <div class="flex gap-2 md:col-span-1">
                        <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white p-2.5 rounded-xl transition shadow-lg shadow-emerald-200 flex justify-center items-center gap-2" title="Tampilkan Data">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <span class="text-xs font-bold">Cari</span>
                        </button>

                        <button type="submit" formaction="{{ route('pengurus.absensi.kontrol.pdf') }}" formtarget="_blank" class="bg-white border border-gray-200 text-red-600 p-2.5 rounded-xl hover:bg-red-50 transition flex justify-center items-center shadow-sm" title="Cetak PDF">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- SUMMARY INFO --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-4">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                        @if($isRentang)
                            Tren Kehadiran: 
                            <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-sm">{{ $listKategori[$kategoriFilter] ?? '-' }}</span>
                            <span class="text-sm text-gray-500 font-normal">({{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</span>
                        @else
                            Detail Harian: <span class="text-emerald-600">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</span>
                        @endif
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Menampilkan <span class="font-bold text-gray-700">{{ count($ledger) }}</span> Data Santri
                    </p>
                </div>
                
                {{-- Legend Ringkas --}}
                <div class="flex flex-wrap gap-3 text-[10px] mt-2 md:mt-0">
                    <div class="flex items-center gap-1 bg-white px-2 py-1 rounded border border-gray-200"><span class="w-2 h-2 bg-green-500 rounded-full"></span> Hadir</div>
                    <div class="flex items-center gap-1 bg-white px-2 py-1 rounded border border-gray-200"><span class="w-2 h-2 bg-red-500 rounded-full"></span> Alpha</div>
                    <div class="flex items-center gap-1 bg-white px-2 py-1 rounded border border-gray-200"><span class="w-2 h-2 bg-yellow-400 rounded-full"></span> Sakit</div>
                    <div class="flex items-center gap-1 bg-white px-2 py-1 rounded border border-gray-200"><span class="w-2 h-2 bg-blue-400 rounded-full"></span> Izin</div>
                    <div class="flex items-center gap-1 bg-white px-2 py-1 rounded border border-gray-200"><span class="w-2 h-2 bg-gray-300 rounded-full"></span> TM</div>
                </div>
            </div>

            {{-- TAB 1: LAPORAN PELANGGARAN (GRID) --}}
            <div x-show="activeTab === 'laporan'" class="space-y-6" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg flex items-start shadow-sm">
                    <svg class="h-5 w-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div class="text-sm text-yellow-800">
                        <p class="font-bold">Laporan Ketidakhadiran (Alpha)</p>
                        <p class="opacity-80">Daftar santri di bawah ini tercatat <strong>TIDAK HADIR</strong> tanpa keterangan (Sakit/Izin) pada kategori wajib.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pelanggaran as $key => $data)
                        @if(count($data['santri']) > 0)
                            <div class="bg-white border border-red-100 rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition hover:border-red-200">
                                <div class="bg-red-50/50 px-5 py-3 border-b border-red-100 flex justify-between items-center">
                                    <h3 class="font-bold text-red-700 text-xs uppercase tracking-wide">{{ $data['label'] }}</h3>
                                    <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-lg">{{ count($data['santri']) }} Orang</span>
                                </div>
                                <ul class="divide-y divide-gray-50 max-h-64 overflow-y-auto custom-scrollbar">
                                    @foreach($data['santri'] as $s)
                                        <li class="px-5 py-3 text-sm flex justify-between items-center hover:bg-gray-50 group transition">
                                            <span class="font-medium text-gray-700 group-hover:text-red-600 transition">{{ $s->full_name }}</span>
                                            <span class="text-[10px] text-gray-500 font-mono bg-gray-100 px-1.5 py-0.5 rounded">{{ $s->kelas->nama_kelas ?? '' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Empty State Pelanggaran --}}
                @php $totalPelanggaran = collect($pelanggaran)->sum(fn($d) => count($d['santri'])); @endphp
                @if($totalPelanggaran == 0)
                    <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-emerald-200">
                        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Nihil Pelanggaran</h3>
                        <p class="text-gray-400 text-sm mt-1">Semua santri hadir atau memiliki izin yang sah hari ini.</p>
                    </div>
                @endif
            </div>

            {{-- TAB 2: LEDGER LENGKAP (TABLE) --}}
            <div x-show="activeTab === 'ledger'" style="display: none;"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4 text-left font-bold text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-20 w-56 shadow-[4px_0_10px_-5px_rgba(0,0,0,0.05)] border-r border-gray-200">
                                        Nama Santri
                                    </th>
                                    <th class="px-3 py-4 text-center font-bold text-gray-500 uppercase tracking-wider w-24 border-r border-gray-200">
                                        Kelas
                                    </th>
                                    
                                    @foreach($headerKolom as $key => $label)
                                        <th class="px-2 py-4 text-center font-bold text-gray-500 uppercase tracking-wider border-r border-gray-100 min-w-[80px]">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse($ledger as $row)
                                    <tr class="hover:bg-gray-50 transition group">
                                        {{-- Sticky Name Column --}}
                                        <td class="px-4 py-3 font-semibold text-gray-700 whitespace-nowrap sticky left-0 bg-white group-hover:bg-gray-50 shadow-[4px_0_10px_-5px_rgba(0,0,0,0.05)] border-r border-gray-200 z-10">
                                            {{ $row['nama'] }}
                                        </td>
                                        <td class="px-3 py-3 text-center text-gray-500 border-r border-gray-200">
                                            {{ $row['kelas'] }}
                                        </td>
                                        
                                        @foreach($headerKolom as $key => $label)
                                            @php 
                                                $val = $row['data'][$key] ?? ['status' => '-', 'class' => '', 'text' => '-'];
                                                $status = is_string($val) ? $val : $val['status'];
                                            @endphp

                                            <td class="px-2 py-3 text-center border-r border-gray-50 relative">
                                                @if($isRentang)
                                                    <div class="w-full h-6 rounded {{ $val['class'] }} flex items-center justify-center font-bold text-[10px] mx-auto" title="{{ $val['text'] }}" style="max-width: 80%;">
                                                        {{ $val['status'] }}
                                                    </div>
                                                @else
                                                    @if($status == 'H')
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-50 text-green-600 font-extrabold text-[10px]">✓</span>
                                                    @elseif($status == 'A')
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-50 text-red-600 font-extrabold text-[10px]">A</span>
                                                    @elseif($status == 'S')
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-50 text-yellow-600 font-extrabold text-[10px]">S</span>
                                                    @elseif($status == 'I')
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-50 text-blue-600 font-extrabold text-[10px]">I</span>
                                                    @elseif($status == 'TM')
                                                        <span class="text-[9px] text-gray-300 font-bold">TM</span>
                                                    @else
                                                        <span class="text-gray-200 text-lg">•</span>
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($headerKolom) + 2 }}" class="text-center py-12 text-gray-400 italic bg-gray-50">
                                            Tidak ada data santri yang sesuai filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Legend Tambahan TM --}}
                <div class="mt-4 bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex flex-wrap gap-6 text-xs text-gray-600">
                    <div class="font-bold uppercase text-gray-400 mr-2">Keterangan:</div>
                    @if($isRentang)
                        <div class="flex items-center gap-2"><span class="w-4 h-4 bg-green-100 rounded border border-green-200 block"></span> Hadir Full</div>
                        <div class="flex items-center gap-2"><span class="w-4 h-4 bg-orange-100 rounded border border-orange-200 block"></span> Parsial</div>
                        <div class="flex items-center gap-2"><span class="w-4 h-4 bg-red-100 rounded border border-red-200 block"></span> Alpha</div>
                    @else
                        <div class="flex items-center gap-1"><span class="w-5 h-5 bg-green-100 rounded-full inline-flex items-center justify-center text-green-700 font-bold text-[10px]">✓</span> Hadir</div>
                        <div class="flex items-center gap-1"><span class="w-5 h-5 bg-red-100 rounded-full inline-flex items-center justify-center text-red-700 font-bold text-[10px]">A</span> Alpha</div>
                    @endif
                    <div class="flex items-center gap-2"><span class="w-4 h-4 bg-yellow-100 rounded border border-yellow-200 block"></span> Sakit</div>
                    <div class="flex items-center gap-2"><span class="w-4 h-4 bg-blue-100 rounded border border-blue-200 block"></span> Izin</div>
                    <div class="flex items-center gap-2"><span class="w-4 h-4 bg-gray-100 rounded border border-gray-200 block"></span> TM (Tidak Mengikuti)</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>