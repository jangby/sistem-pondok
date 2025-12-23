<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('text-emerald-600', 'border-emerald-600', 'bg-emerald-50');
                el.classList.add('text-gray-500', 'border-transparent');
            });
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            const btn = document.getElementById('btn-' + tabName);
            btn.classList.remove('text-gray-500', 'border-transparent');
            btn.classList.add('text-emerald-600', 'border-emerald-600', 'bg-emerald-50');
        }
    </script>

    <div class="min-h-screen bg-gray-50 pb-10 font-sans">
        
        <div class="bg-white shadow-sm sticky top-0 z-30">
            <div class="flex items-center gap-3 p-4 bg-emerald-600 text-white">
                <a href="{{ route('pengurus.perpulangan.index') }}" class="p-2 bg-white/20 rounded-full hover:bg-white/30 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="font-bold text-lg truncate">{{ $event->judul }}</h1>
                    <p class="text-[10px] text-emerald-100 flex items-center gap-1">
                        Batas Kembali: {{ $event->tanggal_akhir->format('d M Y') }}
                    </p>
                </div>
            </div>

            <div class="bg-emerald-50 px-4 py-2 border-b border-emerald-100">
                <form action="{{ route('pengurus.perpulangan.show', $event->id) }}" method="GET" class="flex gap-2">
                    <div class="relative flex-1">
                        <select name="mustawa_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-200 py-2 pl-2 pr-8 bg-white">
                            <option value="">-- Tampilkan Semua Kelas --</option>
                            @foreach($mustawas as $m)
                                <option value="{{ $m->id }}" {{ request('mustawa_id') == $m->id ? 'selected' : '' }}>
                                    Kelas {{ $m->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(request('mustawa_id'))
                        <a href="{{ route('pengurus.perpulangan.show', $event->id) }}" class="bg-gray-200 text-gray-600 px-3 py-2 rounded-lg text-xs font-bold flex items-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="grid grid-cols-4 gap-2 p-4 border-b border-gray-100 bg-white">
                <div class="text-center">
                    <span class="block text-lg font-black text-gray-800">{{ $records->count() }}</span>
                    <span class="text-[8px] text-gray-500 uppercase font-bold">Total Data</span>
                </div>
                <div class="text-center">
                    <span class="block text-lg font-black text-amber-500">{{ $sedang_pulang->count() }}</span>
                    <span class="text-[8px] text-gray-500 uppercase font-bold">Diluar</span>
                </div>
                <div class="text-center">
                    <span class="block text-lg font-black text-emerald-600">{{ $sudah_kembali->count() }}</span>
                    <span class="text-[8px] text-gray-500 uppercase font-bold">Kembali</span>
                </div>
                <div class="text-center relative">
                    <span class="block text-lg font-black text-rose-500">{{ $terlambat->count() }}</span>
                    <span class="text-[8px] text-gray-500 uppercase font-bold text-rose-500">Telat</span>
                </div>
            </div>

            <div class="flex overflow-x-auto px-4 pb-0 gap-2 no-scrollbar border-b border-gray-200 bg-white">
                <button onclick="switchTab('belum')" id="btn-belum" class="tab-btn whitespace-nowrap pb-3 pt-2 px-3 border-b-2 border-emerald-600 text-emerald-600 bg-emerald-50 text-xs font-bold transition">
                    Belum Jalan
                </button>
                <button onclick="switchTab('sedang')" id="btn-sedang" class="tab-btn whitespace-nowrap pb-3 pt-2 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-xs font-bold transition">
                    Sedang Pulang
                </button>
                <button onclick="switchTab('kembali')" id="btn-kembali" class="tab-btn whitespace-nowrap pb-3 pt-2 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-xs font-bold transition">
                    Sudah Kembali
                </button>
                <button onclick="switchTab('terlambat')" id="btn-terlambat" class="tab-btn whitespace-nowrap pb-3 pt-2 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-xs font-bold transition">
                    Terlambat
                </button>
            </div>
        </div>

        <div class="p-4 min-h-[50vh]">
            
            <div id="tab-belum" class="tab-content space-y-3">
                @forelse($belum_pulang as $rec)
                    <div class="bg-white p-3 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3">
                        <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold flex-shrink-0">
                            {{ substr($rec->santri->full_name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 text-sm truncate">{{ $rec->santri->full_name }}</h4>
                            <p class="text-[10px] text-gray-500 truncate">{{ $rec->santri->mustawa->nama ?? '-' }} • {{ $rec->santri->asrama->nama_asrama ?? '-' }}</p>
                        </div>
                        <span class="text-[9px] font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">Di Pondok</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                        <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-xs">Tidak ada data (sesuai filter).</p>
                    </div>
                @endforelse
            </div>

            <div id="tab-sedang" class="tab-content space-y-3 hidden">
                @forelse($sedang_pulang as $rec)
                    <div class="bg-white p-3 rounded-xl border-l-4 border-amber-400 shadow-sm flex items-center gap-3 relative">
                        @if(now()->greaterThan($event->tanggal_akhir))
                            <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full"></span>
                        @endif

                        <div class="w-9 h-9 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 text-xs font-bold flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 text-sm truncate">{{ $rec->santri->full_name }}</h4>
                            <div class="flex items-center gap-2 text-[10px] text-gray-500">
                                <span>{{ $rec->santri->mustawa->nama ?? '' }} • Keluar: {{ $rec->waktu_keluar ? $rec->waktu_keluar->format('d/m H:i') : '-' }}</span>
                            </div>
                        </div>
                         <a href="https://wa.me/{{ $rec->santri->no_hp_ortu ?? '' }}?text=Assalamualaikum, mohon info apakah ananda {{ $rec->santri->full_name }} sudah sampai rumah?" target="_blank" class="w-8 h-8 flex items-center justify-center bg-green-50 text-green-600 rounded-lg active:scale-95 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.592 2.654-.694c.93.513 1.698.809 2.795.81h.002c3.178 0 5.765-2.587 5.765-5.766.001-3.176-2.587-5.765-5.756-5.795zm6.873 9.132c-.143-.238-.853-1.636-1.196-1.815-.343-.179-.699-.267-1.055.268-.357.534-1.393 1.745-1.713 2.102-.32.357-.642.392-1.212.107-.571-.285-2.408-.887-4.588-2.829-1.699-1.513-2.846-3.383-3.167-3.953-.32-.571-.034-.879.251-1.163.26-.258.571-.678.855-1.018.286-.339.393-.57.571-.928.179-.357.089-.678-.053-.928-.143-.25-1.069-2.586-1.463-3.543-.386-.94-.78-.813-1.068-.828l-.91-.01c-.321 0-.855.125-1.319.624-.464.5-1.783 1.748-1.783 4.28 0 2.532 1.855 4.978 2.122 5.334.267.356 3.651 5.567 8.847 7.808 3.125 1.348 3.754 1.08 4.431.99.981-.13 3.016-1.231 3.444-2.421.428-1.189.428-2.212.285-2.451z"/></svg>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-xs">Tidak ada data.</div>
                @endforelse
            </div>

            <div id="tab-kembali" class="tab-content space-y-3 hidden">
                @forelse($sudah_kembali as $rec)
                    <div class="bg-white p-3 rounded-xl border-l-4 {{ $rec->is_late ? 'border-orange-400' : 'border-emerald-500' }} shadow-sm flex items-center gap-3">
                        <div class="w-9 h-9 {{ $rec->is_late ? 'bg-orange-50 text-orange-600' : 'bg-emerald-50 text-emerald-600' }} rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 text-sm truncate">{{ $rec->santri->full_name }}</h4>
                            <div class="flex flex-col text-[10px] text-gray-500">
                                <span>{{ $rec->santri->mustawa->nama ?? '' }} • Kembali: {{ $rec->waktu_kembali ? $rec->waktu_kembali->format('d/m H:i') : '-' }}</span>
                                @if($rec->is_late)
                                    <span class="text-orange-500 font-bold mt-0.5">Terlambat</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-xs">Tidak ada data.</div>
                @endforelse
            </div>

            <div id="tab-terlambat" class="tab-content space-y-3 hidden">
                 @forelse($terlambat as $rec)
                    <div class="bg-white p-3 rounded-xl border border-rose-100 shadow-sm flex items-center gap-3">
                        <div class="w-9 h-9 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 font-bold text-sm flex-shrink-0">!</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 text-sm truncate">{{ $rec->santri->full_name }}</h4>
                            <p class="text-[10px] text-rose-500 font-medium truncate">
                                {{ $rec->santri->mustawa->nama ?? '' }} • 
                                @if($rec->status == 1) Belum Kembali @else Telat @endif
                            </p>
                        </div>
                        <a href="https://wa.me/{{ $rec->santri->no_hp_ortu ?? '' }}?text=Assalamualaikum, kami menginformasikan bahwa ananda {{ $rec->santri->full_name }} terlambat kembali ke pondok." target="_blank" class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg active:scale-95 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.592 2.654-.694c.93.513 1.698.809 2.795.81h.002c3.178 0 5.765-2.587 5.765-5.766.001-3.176-2.587-5.765-5.756-5.795zm6.873 9.132c-.143-.238-.853-1.636-1.196-1.815-.343-.179-.699-.267-1.055.268-.357.534-1.393 1.745-1.713 2.102-.32.357-.642.392-1.212.107-.571-.285-2.408-.887-4.588-2.829-1.699-1.513-2.846-3.383-3.167-3.953-.32-.571-.034-.879.251-1.163.26-.258.571-.678.855-1.018.286-.339.393-.57.571-.928.179-.357.089-.678-.053-.928-.143-.25-1.069-2.586-1.463-3.543-.386-.94-.78-.813-1.068-.828l-.91-.01c-.321 0-.855.125-1.319.624-.464.5-1.783 1.748-1.783 4.28 0 2.532 1.855 4.978 2.122 5.334.267.356 3.651 5.567 8.847 7.808 3.125 1.348 3.754 1.08 4.431.99.981-.13 3.016-1.231 3.444-2.421.428-1.189.428-2.212.285-2.451z"/></svg>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-xs">Tidak ada santri terlambat.</div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>