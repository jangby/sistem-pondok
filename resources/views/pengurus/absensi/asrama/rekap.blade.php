<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- HEADER --}}
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('pengurus.absensi.asrama') }}" class="text-gray-500 hover:text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="font-bold text-lg text-gray-800">Data Kehadiran</h1>
            </div>
            <div class="flex p-1 bg-gray-100 rounded-xl">
                <a href="{{ route('pengurus.absensi.asrama.rekap', ['mode' => 'harian']) }}" class="flex-1 text-center py-2 text-xs font-bold rounded-lg transition {{ $mode == 'harian' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500' }}">Laporan Harian</a>
                <a href="{{ route('pengurus.absensi.asrama.rekap', ['mode' => 'santri']) }}" class="flex-1 text-center py-2 text-xs font-bold rounded-lg transition {{ $mode == 'santri' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500' }}">Cari Santri</a>
            </div>
        </div>

        <div class="p-5">
            @if($mode == 'harian')
                {{-- Filter --}}
                <form method="GET" class="flex gap-2 mb-6">
                    <input type="hidden" name="mode" value="harian">
                    <input type="date" name="date" value="{{ $tanggal }}" class="w-full rounded-xl border-gray-200 text-sm" onchange="this.form.submit()">
                    <select name="sesi" class="rounded-xl border-gray-200 text-sm" onchange="this.form.submit()">
                        <option value="semua" {{ $sesi == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="asrama_pagi" {{ $sesi == 'asrama_pagi' ? 'selected' : '' }}>Pagi</option>
                        <option value="asrama_malam" {{ $sesi == 'asrama_malam' ? 'selected' : '' }}>Malam</option>
                    </select>
                </form>

                {{-- List --}}
                <div class="space-y-3">
                    @foreach($santris as $s)
                        @php
                            // PERBAIKAN DISINI:
                            // Collection filter tidak perlu query database lagi
                            $hadirPagi = $s->absensis->first(fn($item) => $item->nama_kegiatan === 'asrama_pagi');
                            $hadirMalam = $s->absensis->first(fn($item) => $item->nama_kegiatan === 'asrama_malam');
                            $isSakit = $s->riwayatKesehatan->isNotEmpty(); // Karena sudah difilter di controller
                        @endphp

                        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">{{ $s->full_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $s->nis }} • {{ $s->kelas->nama_kelas ?? '-' }}</p>
                                </div>
                                @if($isSakit)
                                    <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-1 rounded">SAKIT</span>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                @if($sesi == 'semua' || $sesi == 'asrama_pagi')
                                    <div class="flex-1 flex items-center justify-between p-2 rounded-lg {{ $hadirPagi ? 'bg-emerald-50 border border-emerald-100' : 'bg-gray-50 border border-gray-100' }}">
                                        <span class="text-[10px] font-bold text-gray-500">Pagi</span>
                                        @if($hadirPagi)
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <span class="text-[10px] text-red-400 font-bold">X</span>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($sesi == 'semua' || $sesi == 'asrama_malam')
                                    <div class="flex-1 flex items-center justify-between p-2 rounded-lg {{ $hadirMalam ? 'bg-emerald-50 border border-emerald-100' : 'bg-gray-50 border border-gray-100' }}">
                                        <span class="text-[10px] font-bold text-gray-500">Malam</span>
                                        @if($hadirMalam)
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <span class="text-[10px] text-red-400 font-bold">X</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $santris->links('pagination::tailwind') }}</div>

            @else
                {{-- MODE RIWAYAT SANTRI (Kode lama tetap oke) --}}
                {{-- ... (bagian ini sama seperti sebelumnya) ... --}}
                <form method="GET" class="mb-6">
                    <input type="hidden" name="mode" value="santri">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik Nama atau NIS..." class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 text-sm shadow-sm focus:ring-emerald-500">
                        <div class="absolute left-3 top-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </form>

                @if($santri)
                    <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-200 rounded-full flex items-center justify-center text-emerald-800 font-bold">
                            {{ substr($santri->full_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $santri->full_name }}</h3>
                            <p class="text-xs text-gray-500">{{ $santri->nis }} • {{ $santri->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                    </div>

                    <h3 class="font-bold text-gray-800 text-sm mb-3">Riwayat Kehadiran (30 Terakhir)</h3>
                    
                    <div class="space-y-2">
                        @forelse($riwayat as $log)
                            <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-gray-100">
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">{{ $log->tanggal->format('d M Y') }}</p>
                                    <p class="text-[10px] text-gray-400">
                                        Sesi: <span class="uppercase font-bold text-gray-500">{{ str_replace('asrama_', '', $log->nama_kegiatan) }}</span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded uppercase">{{ $log->status }}</span>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($log->waktu_catat)->format('H:i') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm bg-white rounded-xl border border-dashed border-gray-200">
                                Belum ada data absensi asrama.
                            </div>
                        @endforelse
                    </div>
                @elseif(request('search'))
                    <div class="text-center py-10 text-gray-400">
                        Santri tidak ditemukan. Coba kata kunci lain.
                    </div>
                @else
                    <div class="text-center py-20 opacity-50">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <p class="text-gray-500 text-sm">Cari santri untuk melihat riwayat.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>