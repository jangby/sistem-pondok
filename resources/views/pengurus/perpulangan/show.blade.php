<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <script>
        function switchTab(tabName) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Remove active style from all buttons
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('text-emerald-600', 'border-emerald-600', 'bg-emerald-50');
                el.classList.add('text-gray-500', 'border-transparent');
            });

            // Show selected content
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            // Add active style to selected button
            const btn = document.getElementById('btn-' + tabName);
            btn.classList.remove('text-gray-500', 'border-transparent');
            btn.classList.add('text-emerald-600', 'border-emerald-600', 'bg-emerald-50');
        }
    </script>

    <div class="min-h-screen bg-gray-50 pb-10 font-sans">
        
        <div class="bg-white shadow-sm sticky top-0 z-20">
            <div class="flex items-center gap-3 p-4 bg-emerald-600 text-white">
                <a href="{{ route('pengurus.perpulangan.index') }}" class="p-2 bg-white/20 rounded-full hover:bg-white/30 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="font-bold text-lg truncate">{{ $event->judul }}</h1>
                    <p class="text-xs text-emerald-100 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Wajib Kembali: {{ $event->tanggal_akhir->format('d M Y') }}
                    </p>
                </div>
                <a href="{{ route('pengurus.perpulangan.scan') }}" class="bg-white text-emerald-600 p-2 rounded-xl shadow-lg active:scale-95 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-4 gap-2 p-4 border-b border-gray-100">
                <div class="text-center">
                    <span class="block text-xl font-black text-gray-800">{{ $records->count() }}</span>
                    <span class="text-[9px] text-gray-500 uppercase font-bold">Total</span>
                </div>
                <div class="text-center">
                    <span class="block text-xl font-black text-amber-500">{{ $sedang_pulang->count() }}</span>
                    <span class="text-[9px] text-gray-500 uppercase font-bold">Diluar</span>
                </div>
                <div class="text-center">
                    <span class="block text-xl font-black text-emerald-600">{{ $sudah_kembali->count() }}</span>
                    <span class="text-[9px] text-gray-500 uppercase font-bold">Kembali</span>
                </div>
                <div class="text-center relative">
                    <span class="block text-xl font-black text-rose-500">{{ $terlambat->count() }}</span>
                    <span class="text-[9px] text-gray-500 uppercase font-bold text-rose-500">Telat</span>
                </div>
            </div>

            <div class="flex overflow-x-auto px-4 pb-0 gap-2 no-scrollbar border-b border-gray-200">
                <button onclick="switchTab('belum')" id="btn-belum" class="tab-btn whitespace-nowrap pb-3 pt-2 px-3 border-b-2 border-emerald-600 text-emerald-600 bg-emerald-50 text-xs font-bold transition">
                    Belum Jalan ({{ $belum_pulang->count() }})
                </button>
                <button onclick="switchTab('sedang')" id="btn-sedang" class="tab-btn whitespace-nowrap pb-3 pt-2 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-xs font-bold transition">
                    Sedang Pulang ({{ $sedang_pulang->count() }})
                </button>
                <button onclick="switchTab('kembali')" id="btn-kembali" class="tab-btn whitespace-nowrap pb-3 pt-2 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-xs font-bold transition">
                    Sudah Kembali ({{ $sudah_kembali->count() }})
                </button>
                <button onclick="switchTab('terlambat')" id="btn-terlambat" class="tab-btn whitespace-nowrap pb-3 pt-2 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-xs font-bold transition">
                    Terlambat ({{ $terlambat->count() }})
                </button>
            </div>
        </div>

        <div class="p-4">
            
            <div id="tab-belum" class="tab-content space-y-3">
                @forelse($belum_pulang as $rec)
                    <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold">
                            {{ substr($rec->santri->full_name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm">{{ $rec->santri->full_name }}</h4>
                            <p class="text-[10px] text-gray-500">{{ $rec->santri->mustawa->nama ?? '-' }} | {{ $rec->santri->asrama->nama_asrama ?? '-' }}</p>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">Di Pondok</span>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm py-10">Tidak ada data.</p>
                @endforelse
            </div>

            <div id="tab-sedang" class="tab-content space-y-3 hidden">
                @forelse($sedang_pulang as $rec)
                    <div class="bg-white p-3 rounded-xl border-l-4 border-amber-400 shadow-sm flex items-center gap-3 relative">
                        @if(now()->greaterThan($event->tanggal_akhir))
                            <div class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full animate-ping"></div>
                        @endif

                        <div class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 text-xs font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm">{{ $rec->santri->full_name }}</h4>
                            <div class="flex items-center gap-2 text-[10px] text-gray-500">
                                <span>Keluar: {{ $rec->waktu_keluar ? $rec->waktu_keluar->format('d M H:i') : '-' }}</span>
                            </div>
                        </div>
                         <a href="https://wa.me/{{ $rec->santri->no_hp_ortu ?? '' }}?text=Assalamualaikum, mohon info apakah ananda {{ $rec->santri->full_name }} sudah sampai rumah?" target="_blank" class="p-2 bg-green-50 text-green-600 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.592 2.654-.694c.93.513 1.698.809 2.795.81h.002c3.178 0 5.765-2.587 5.765-5.766.001-3.176-2.587-5.765-5.756-5.795zm6.873 9.132c-.143-.238-.853-1.636-1.196-1.815-.343-.179-.699-.267-1.055.268-.357.534-1.393 1.745-1.713 2.102-.32.357-.642.392-1.212.107-.571-.285-2.408-.887-4.588-2.829-1.699-1.513-2.846-3.383-3.167-3.953-.32-.571-.034-.879.251-1.163.26-.258.571-.678.855-1.018.286-.339.393-.57.571-.928.179-.357.089-.678-.053-.928-.143-.25-1.069-2.586-1.463-3.543-.386-.94-.78-.813-1.068-.828l-.91-.01c-.321 0-.855.125-1.319.624-.464.5-1.783 1.748-1.783 4.28 0 2.532 1.855 4.978 2.122 5.334.267.356 3.651 5.567 8.847 7.808 3.125 1.348 3.754 1.08 4.431.99.981-.13 3.016-1.231 3.444-2.421.428-1.189.428-2.212.285-2.451z"/></svg>
                        </a>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm py-10">Belum ada yang jalan.</p>
                @endforelse
            </div>

            <div id="tab-kembali" class="tab-content space-y-3 hidden">
                @forelse($sudah_kembali as $rec)
                    <div class="bg-white p-3 rounded-xl border-l-4 {{ $rec->is_late ? 'border-orange-400' : 'border-emerald-500' }} shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 {{ $rec->is_late ? 'bg-orange-50 text-orange-600' : 'bg-emerald-50 text-emerald-600' }} rounded-full flex items-center justify-center text-xs font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm">{{ $rec->santri->full_name }}</h4>
                            <div class="flex flex-col text-[10px] text-gray-500">
                                <span>Kembali: {{ $rec->waktu_kembali ? $rec->waktu_kembali->format('d M H:i') : '-' }}</span>
                                @if($rec->is_late)
                                    <span class="text-orange-500 font-bold">Terlambat Kembali</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm py-10">Belum ada yang kembali.</p>
                @endforelse
            </div>

            <div id="tab-terlambat" class="tab-content space-y-3 hidden">
                 @forelse($terlambat as $rec)
                    <div class="bg-white p-3 rounded-xl border border-rose-100 shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 font-bold">
                            !
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm">{{ $rec->santri->full_name }}</h4>
                            <p class="text-[10px] text-rose-500 font-medium">
                                @if($rec->status == 1)
                                    Masih diluar (Belum Scan Masuk)
                                @else
                                    Sudah kembali (Telat)
                                @endif
                            </p>
                        </div>
                        <a href="https://wa.me/{{ $rec->santri->no_hp_ortu ?? '' }}?text=Assalamualaikum, kami menginformasikan bahwa ananda {{ $rec->santri->full_name }} terlambat kembali ke pondok." target="_blank" class="p-2 bg-rose-50 text-rose-600 rounded-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.592 2.654-.694c.93.513 1.698.809 2.795.81h.002c3.178 0 5.765-2.587 5.765-5.766.001-3.176-2.587-5.765-5.756-5.795zm6.873 9.132c-.143-.238-.853-1.636-1.196-1.815-.343-.179-.699-.267-1.055.268-.357.534-1.393 1.745-1.713 2.102-.32.357-.642.392-1.212.107-.571-.285-2.408-.887-4.588-2.829-1.699-1.513-2.846-3.383-3.167-3.953-.32-.571-.034-.879.251-1.163.26-.258.571-.678.855-1.018.286-.339.393-.57.571-.928.179-.357.089-.678-.053-.928-.143-.25-1.069-2.586-1.463-3.543-.386-.94-.78-.813-1.068-.828l-.91-.01c-.321 0-.855.125-1.319.624-.464.5-1.783 1.748-1.783 4.28 0 2.532 1.855 4.978 2.122 5.334.267.356 3.651 5.567 8.847 7.808 3.125 1.348 3.754 1.08 4.431.99.981-.13 3.016-1.231 3.444-2.421.428-1.189.428-2.212.285-2.451z"/></svg>
                        </a>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm py-10">Tidak ada santri terlambat.</p>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>