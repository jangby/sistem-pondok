<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- HEADER & JUDUL --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Laporan Tunggakan Santri</h2>
                    <p class="text-sm text-gray-500">Pantau akumulasi tagihan santri yang belum terbayarkan.</p>
                </div>
                
                {{-- Stats Kecil (Grand Total) --}}
                <div class="bg-red-50 border border-red-100 px-6 py-3 rounded-xl flex items-center gap-4">
                    <div class="p-2 bg-red-100 rounded-full text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-red-400 uppercase tracking-wider">Total Tunggakan (Filter)</p>
                        <p class="text-xl font-bold text-red-700">Rp {{ number_format($grandTotalTunggakan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- KARTU FILTER --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <h3 class="font-semibold text-gray-700">Filter Data</h3>
                </div>
                
                <div class="p-6">
                    <form method="GET" action="{{ route('adminpondok.laporan.tunggakan') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            
                            {{-- Cari Santri --}}
                            <div>
                                <x-input-label for="select-santri" :value="__('Cari Nama / NIS')" />
                                <select id="select-santri" name="santri_id" placeholder="Ketik nama..." class="block w-full"></select>
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div>
                                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm">
                                    <option value="">Semua</option>
                                    <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            {{-- Kelas --}}
                            <div>
                                <x-input-label for="kelas" :value="__('Kelas / Asrama')" />
                                <select name="kelas" id="kelas" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm">
                                    <option value="">Semua Kelas</option>
                                    @foreach($daftarKelas as $kelas)
                                        <option value="{{ $kelas->class }}" {{ request('kelas') == $kelas->class ? 'selected' : '' }}>
                                            {{ $kelas->class }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Urutan --}}
                            <div>
                                <x-input-label for="sort" :value="__('Urutkan Berdasarkan')" />
                                <select name="sort" id="sort" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm">
                                    <option value="tunggakan_desc" {{ request('sort', 'tunggakan_desc') == 'tunggakan_desc' ? 'selected' : '' }}>Tunggakan Terbesar</option>
                                    <option value="tunggakan_asc" {{ request('sort') == 'tunggakan_asc' ? 'selected' : '' }}>Tunggakan Terkecil</option>
                                    <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama Santri (A-Z)</option>
                                </select>
                            </div>

                        </div>
                        
                        <div class="flex items-center justify-end mt-6 gap-3">
                            <a href="{{ route('adminpondok.laporan.tunggakan') }}" class="text-sm text-gray-500 hover:text-emerald-600 transition">
                                Reset Filter
                            </a>
                            <x-primary-button class="px-6 py-2.5">
                                {{ __('Terapkan Filter') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- DAFTAR SANTRI (LIST CARD) --}}
            <div class="space-y-4">
                @forelse ($santris as $santri)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:border-emerald-300 hover:shadow-md transition duration-200 group">
                        <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                            
                            {{-- Info Santri --}}
                            <div class="flex items-center gap-4 w-full md:w-auto">
                                <div class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg group-hover:bg-emerald-100 group-hover:text-emerald-600 transition-colors">
                                    {{ substr($santri->full_name, 0, 2) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $santri->full_name }}</h3>
                                    <div class="flex items-center gap-3 text-sm text-gray-500 mt-1">
                                        <span class="flex items-center gap-1 bg-gray-50 px-2 py-0.5 rounded border border-gray-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            {{ $santri->nis }}
                                        </span>
                                        <span>{{ $santri->jenis_kelamin }}</span>
                                        <span>•</span>
                                        <span>Kelas {{ $santri->class ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Info Tunggakan & Aksi --}}
                            <div class="flex flex-col md:items-end text-right w-full md:w-auto">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Sisa Tagihan</p>
                                <p class="text-2xl font-bold text-red-600 mb-2">
                                    Rp {{ number_format($santri->total_tunggakan, 0, ',', '.') }}
                                </p>
                                <a href="{{ route('adminpondok.tagihan.index', ['santri_id' => $santri->id, 'status' => 'pending']) }}" 
                                   class="inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-800 hover:underline transition">
                                    Lihat Rincian Tagihan
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 mb-4 text-emerald-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Tidak Ada Tunggakan</h3>
                        <p class="text-gray-500 mt-1">Alhamdulillah, tidak ada data santri yang menunggak sesuai filter ini.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $santris->links() }}
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // TomSelect untuk Santri
        new TomSelect('#select-santri', {
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            create: false,
            load: function(query, callback) {
                if (!query.length) return callback();
                fetch('{{ route('api.search.santri') }}?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(json => { callback(json); }).catch(()=>{ callback(); });
            },
            options: [
                @if($selectedSantri)
                { id: '{{ $selectedSantri->id }}', text: '{{ $selectedSantri->full_name }} (NIS: {{ $selectedSantri->nis }})' }
                @endif
            ],
            items: [
                @if($selectedSantri) '{{ $selectedSantri->id }}' @endif
            ]
        });
    </script>
    @endpush
</x-app-layout>