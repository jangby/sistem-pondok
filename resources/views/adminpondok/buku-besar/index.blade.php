<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- HEADER HALAMAN --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Buku Besar Transaksi</h2>
                    <p class="text-sm text-gray-500">Jejak audit seluruh transaksi masuk (pembayaran tagihan & top-up).</p>
                </div>
            </div>

            {{-- KARTU FILTER --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <h3 class="font-semibold text-gray-700">Filter Riwayat</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('adminpondok.buku-besar.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            
                            {{-- Cari Santri --}}
                            <div>
                                <x-input-label for="select-santri" :value="__('Cari Nama / NIS Santri')" />
                                <select id="select-santri" name="santri_id" placeholder="Ketik nama..." class="block w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"></select>
                            </div>

                            {{-- Status Transaksi --}}
                            <div>
                                <x-input-label for="status" :value="__('Status Transaksi')" />
                                <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Semua (Verified & Canceled)</option>
                                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified (Berhasil)</option>
                                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled (Dibatalkan)</option>
                                </select>
                            </div>

                            {{-- Dari Tanggal --}}
                            <div>
                                <x-input-label for="tanggal_mulai" :value="__('Dari Tanggal')" />
                                <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="date" name="tanggal_mulai" :value="request('tanggal_mulai')" />
                            </div>

                            {{-- Sampai Tanggal --}}
                            <div>
                                <x-input-label for="tanggal_selesai" :value="__('Sampai Tanggal')" />
                                <x-text-input id="tanggal_selesai" class="block mt-1 w-full" type="date" name="tanggal_selesai" :value="request('tanggal_selesai')" />
                            </div>

                        </div>
                        <div class="flex items-center justify-end mt-6 gap-3">
                            <a href="{{ route('adminpondok.buku-besar.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 transition">
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Santri / Wali</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transaksis as $transaksi)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Tanggal --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $transaksi->tanggal_bayar ? $transaksi->tanggal_bayar->format('d M Y, H:i') : $transaksi->created_at->format('d M Y, H:i') }}
                                    </td>

                                    {{-- Info Santri --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $transaksi->tagihan->santri->full_name ?? ($transaksi->orangTua->name ?? 'N/A') }}
                                        </div>
                                        <div class="text-xs text-gray-500 font-mono">
                                            {{ $transaksi->tagihan->invoice_number ?? $transaksi->order_id_pondok }}
                                        </div>
                                    </td>

                                    {{-- Keterangan --}}
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $transaksi->catatan_verifikasi }}">
                                        {{ $transaksi->catatan_verifikasi ?? '-' }}
                                    </td>

                                    {{-- Nominal --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ $transaksi->status_verifikasi == 'canceled' ? 'text-gray-400 line-through' : 'text-gray-900' }}">
                                        Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($transaksi->status_verifikasi == 'verified')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Verified
                                            </span>
                                        @elseif ($transaksi->status_verifikasi == 'canceled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                Dibatalkan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 p-3 rounded-full mb-3">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                            </div>
                                            <p>Tidak ada data transaksi yang sesuai filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $transaksis->links() }}
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    {{-- Skrip Tom-Select untuk Autocomplete --}}
    <script>
        new TomSelect('#select-santri', {
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            create: false,
            load: function(query, callback) {
                if (!query.length) return callback();
                fetch('{{ route('api.search.santri') }}?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(json => {
                        callback(json);
                    }).catch(()=>{
                        callback();
                    });
            },
            options: [
                @if($selectedSantri)
                {
                    id: '{{ $selectedSantri->id }}',
                    text: '{{ $selectedSantri->full_name }} (NIS: {{ $selectedSantri->nis }})'
                }
                @endif
            ],
            items: [
                @if($selectedSantri)
                '{{ $selectedSantri->id }}'
                @endif
            ]
        });
    </script>
    @endpush
</x-app-layout>