<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- HEADER HALAMAN --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Manajemen Tagihan</h2>
                    <p class="text-sm text-gray-500">Pantau status pembayaran dan kelola tagihan santri.</p>
                </div>
                {{-- Tombol Generate ada di menu dropdown, tapi bisa juga ditambah di sini jika mau --}}
                <a href="{{ route('adminpondok.tagihan.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Generate Tagihan
                </a>
            </div>
            
            {{-- KARTU FILTER --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <h3 class="font-semibold text-gray-700">Filter Pencarian</h3>
                </div>
                
                <div class="p-6">
                    <form method="GET" action="{{ route('adminpondok.tagihan.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            
                            {{-- Cari Santri --}}
                            <div>
                                <x-input-label for="select-santri" :value="__('Cari Santri (Nama / NIS)')" />
                                <select id="select-santri" name="santri_id" placeholder="Ketik nama..." class="block w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"></select>
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

                            {{-- Tipe Tagihan --}}
                            <div>
                                <x-input-label for="tipe_tagihan" :value="__('Tipe Tagihan')" />
                                <select name="tipe_tagihan" id="tipe_tagihan" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm">
                                    <option value="">Semua Tipe</option>
                                    <option value="bulanan" {{ request('tipe_tagihan') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="semesteran" {{ request('tipe_tagihan') == 'semesteran' ? 'selected' : '' }}>Semesteran</option>
                                    <option value="tahunan" {{ request('tipe_tagihan') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                    <option value="sekali_bayar" {{ request('tipe_tagihan') == 'sekali_bayar' ? 'selected' : '' }}>Sekali Bayar</option>
                                </select>
                            </div>

                            {{-- Status Pembayaran --}}
                            <div>
                                <x-input-label for="status" :value="__('Status Pembayaran')" />
                                <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Lunas</option>
                                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Dicicil</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                                </select>
                            </div>

                        </div>
                        
                        <div class="flex items-center justify-end mt-6 gap-3">
                            <a href="{{ route('adminpondok.tagihan.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 transition">
                                Reset Filter
                            </a>
                            <x-primary-button class="px-6 py-2.5">
                                {{ __('Terapkan Filter') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Santri</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Tagihan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                <th scope="col" class="relative px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($tagihans as $tagihan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-600">
                                            #{{ $tagihan->invoice_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $tagihan->santri->full_name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $tagihan->jenisPembayaran->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        Rp {{ number_format($tagihan->nominal_tagihan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($tagihan->status == 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Lunas
                                            </span>
                                        @elseif($tagihan->status == 'partial')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                Dicicil
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                Belum Lunas
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($tagihan->due_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-3">
                                            <a href="{{ route('adminpondok.tagihan.show', $tagihan->id) }}" class="text-emerald-600 hover:text-emerald-900 font-semibold">
                                                Lihat
                                            </a>
                                            @if($tagihan->status == 'pending')
                                                <button type="button" 
                                                        onclick="confirmDelete('{{ $tagihan->id }}', '{{ $tagihan->invoice_number }}')" 
                                                        class="text-red-400 hover:text-red-600" title="Batalkan Tagihan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                                <form id="delete-form-{{ $tagihan->id }}" action="{{ route('adminpondok.tagihan.destroy', $tagihan->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada data tagihan yang sesuai filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $tagihans->links() }}
                </div>
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

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Batalkan Tagihan?',
                text: `Anda akan menghapus tagihan #${name} secara permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>