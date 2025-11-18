<x-app-layout>
    {{-- Header dihapus agar tampilan lebih bersih --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- JUDUL HALAMAN --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Laporan Rekonsiliasi Midtrans</h2>
                    <p class="text-sm text-gray-500">Pantau pemasukan kotor (bruto), fee admin, dan pendapatan bersih (netto) pondok.</p>
                </div>
            </div>

            {{-- KARTU FILTER --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <h3 class="font-semibold text-gray-800">Filter Laporan</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('superadmin.midtrans.report') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            
                            {{-- Pilih Pondok --}}
                            <div>
                                <x-input-label for="pondok_id" :value="__('Pondok Pesantren')" />
                                <select name="pondok_id" id="pondok_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm">
                                    <option value="">Semua Pondok</option>
                                    @foreach($pondoks as $pondok)
                                        <option value="{{ $pondok->id }}" {{ request('pondok_id') == $pondok->id ? 'selected' : '' }}>
                                            {{ $pondok->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div>
                                <x-input-label for="tanggal_mulai" :value="__('Dari Tanggal')" />
                                <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="date" name="tanggal_mulai" :value="request('tanggal_mulai')" />
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div>
                                <x-input-label for="tanggal_selesai" :value="__('Sampai Tanggal')" />
                                <x-text-input id="tanggal_selesai" class="block mt-1 w-full" type="date" name="tanggal_selesai" :value="request('tanggal_selesai')" />
                            </div>
                            
                            {{-- Tombol Aksi --}}
                            <div class="flex items-end gap-3">
                                <x-primary-button class="w-full justify-center py-2.5 text-base">
                                    {{ __('Terapkan') }}
                                </x-primary-button>
                                <a href="{{ route('superadmin.midtrans.report') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABEL RINGKASAN --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Ringkasan Pemasukan per Pondok</h3>
                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-md">Sesuai Filter</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pondok</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Bruto</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Fee Admin</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-emerald-700 uppercase tracking-wider bg-emerald-50">Total Netto</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($laporanPerPondok as $laporan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $laporan->nama_pondok }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-gray-600">
                                        Rp {{ number_format($laporan->total_bruto, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-red-500 font-medium bg-red-50 px-2 py-0.5 rounded text-xs">
                                            + Rp {{ number_format($laporan->total_biaya_admin, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-emerald-700 bg-emerald-50/50">
                                        Rp {{ number_format($laporan->total_netto, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p>Tidak ada data transaksi yang sesuai dengan filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        {{-- FOOTER GRAND TOTAL --}}
                        @if($laporanPerPondok->count() > 0)
                        <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-200">
                            <tr>
                                <td class="px-6 py-4 text-left text-gray-900">GRAND TOTAL</td>
                                <td class="px-6 py-4 text-right text-gray-900">Rp {{ number_format($laporanPerPondok->sum('total_bruto'), 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right text-red-600">Rp {{ number_format($laporanPerPondok->sum('total_biaya_admin'), 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right text-emerald-700 bg-emerald-100">Rp {{ number_format($laporanPerPondok->sum('total_netto'), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- TABEL RINCIAN TRANSAKSI --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Rincian Transaksi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pondok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Santri / Wali</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Netto (Milik Pondok)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transaksis as $transaksi)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $transaksi->tanggal_bayar->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $transaksi->pondok->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-800">{{ $transaksi->tagihan->santri->full_name ?? 'N/A' }}</span>
                                            <span class="text-xs text-gray-500">{{ $transaksi->orangTua->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-emerald-600">
                                        Rp {{ number_format($transaksi->total_bayar - $transaksi->biaya_admin, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada data rincian transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                @if($transaksis->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $transaksis->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>