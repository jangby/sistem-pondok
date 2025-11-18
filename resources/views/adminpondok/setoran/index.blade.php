<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- HEADER & NAVIGASI --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Laporan Siap Setor</h2>
                    <p class="text-sm text-gray-500">Dana yang telah diterima dari santri dan siap disetorkan ke Bendahara.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('adminpondok.setoran.history') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat Setoran
                    </a>
                    <a href="{{ route('adminpondok.setoran.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        + Buat Setoran Baru
                    </a>
                </div>
            </div>

            {{-- FILTER & TOTAL --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- KOLOM KIRI: Filter --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <h3 class="font-semibold text-gray-700">Filter Transaksi</h3>
                        </div>
                        <div class="p-6">
                            <form method="GET" action="{{ route('adminpondok.setoran.index') }}">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <x-input-label for="tanggal_mulai" :value="__('Dari Tanggal')" />
                                        <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="date" name="tanggal_mulai" :value="request('tanggal_mulai')" />
                                    </div>
                                    <div>
                                        <x-input-label for="tanggal_selesai" :value="__('Sampai Tanggal')" />
                                        <x-text-input id="tanggal_selesai" class="block mt-1 w-full" type="date" name="tanggal_selesai" :value="request('tanggal_selesai')" />
                                    </div>
                                    <div class="flex items-end">
                                        <x-secondary-button type="submit" class="w-full justify-center py-2.5">
                                            Terapkan
                                        </x-secondary-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Grand Total --}}
                <div class="lg:col-span-1">
                    <div class="bg-emerald-600 rounded-xl shadow-lg p-6 text-white h-full flex flex-col justify-center relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-emerald-100 text-sm font-medium uppercase tracking-wider">Total Siap Setor</p>
                            <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($totalSiapSetor, 0, ',', '.') }}</h3>
                            <p class="text-xs text-emerald-200 mt-2">Sesuai rentang tanggal yang dipilih.</p>
                        </div>
                        {{-- Dekorasi --}}
                        <div class="absolute right-0 top-0 -mr-6 -mt-6 text-emerald-500 opacity-30">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONTEN UTAMA: Ringkasan & Tabel --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- RINGKASAN ITEM (Kiri) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 h-full">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-bold text-gray-800">Rincian Pemasukan</h3>
                        </div>
                        <div class="p-0">
                            <ul class="divide-y divide-gray-100">
                                @forelse ($summaryPerItem as $item)
                                    <li class="px-6 py-3 flex justify-between items-center hover:bg-gray-50 transition">
                                        <span class="text-sm text-gray-600">{{ $item->nama_item }}</span>
                                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($item->total_terkumpul, 0, ',', '.') }}</span>
                                    </li>
                                @empty
                                    <li class="px-6 py-8 text-center text-gray-500 text-sm">Tidak ada data pemasukan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- DAFTAR TRANSAKSI (Kanan) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-bold text-gray-800">Daftar Transaksi</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Santri / Wali</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Metode</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($transaksis as $transaksi)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $transaksi->tanggal_bayar->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $transaksi->tagihan->santri->full_name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $transaksi->orangTua->name ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 capitalize">
                                                    {{ str_replace('_', ' ', $transaksi->metode_pembayaran) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                                Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form id="cancel-form-{{ $transaksi->id }}" action="{{ route('adminpondok.transaksi.cancel', $transaksi->id) }}" method="POST">
                                                    @csrf
                                                    <button type="button" 
                                                            onclick="confirmCancel('{{ $transaksi->id }}', 'Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}')" 
                                                            class="text-red-600 hover:text-red-900 text-xs font-semibold bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                                Tidak ada transaksi yang belum disetor.
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
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmCancel(id, nominal) {
            Swal.fire({
                title: 'Batalkan Transaksi?',
                text: `Anda akan membatalkan penerimaan uang sebesar ${nominal}. Status tagihan santri akan kembali menjadi 'Belum Lunas'.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Jangan'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-form-' + id).submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>