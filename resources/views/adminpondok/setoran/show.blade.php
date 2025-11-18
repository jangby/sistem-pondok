<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        {{-- Ubah max-w-4xl menjadi max-w-7xl agar memenuhi layar --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER & NAV --}}
            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-2">
                    <a href="{{ route('adminpondok.setoran.history') }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h2 class="text-xl font-bold text-gray-800">Detail Setoran #{{ $setoran->id }}</h2>
                </div>
                <a href="{{ route('adminpondok.setoran.pdf', $setoran->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak PDF
                </a>
            </div>

            {{-- STATUS CARD (Akan melebar penuh) --}}
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 md:flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide font-medium mb-1">Total Disetor</p>
                        <p class="text-4xl font-bold text-emerald-600">Rp {{ number_format($setoran->total_setoran, 0, ',', '.') }}</p>
                        <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $setoran->tanggal_setoran }}
                            </span>
                            <span class="flex items-center gap-1 capitalize">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                {{ str_replace('_', ' ', $setoran->kategori_setoran) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-6 md:mt-0 text-right">
                        @if ($setoran->dikonfirmasi_pada)
                            <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-4 inline-block text-left">
                                <p class="text-xs text-emerald-600 font-bold uppercase">Status</p>
                                <p class="text-lg font-bold text-emerald-800">Terkonfirmasi</p>
                                <p class="text-xs text-emerald-600 mt-1">Oleh: {{ $setoran->bendaharaPenerima->name ?? 'Bendahara' }}</p>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4 inline-block text-left">
                                <p class="text-xs text-yellow-600 font-bold uppercase">Status</p>
                                <p class="text-lg font-bold text-yellow-800">Menunggu Konfirmasi</p>
                                <p class="text-xs text-yellow-600 mt-1">Harap hubungi bendahara.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-sm text-gray-600">
                    <strong>Catatan:</strong> {{ $setoran->catatan ?? '-' }}
                </div>
            </div>

            {{-- RINCIAN ITEM (Akan melebar penuh) --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Rincian Pemasukan</h3>
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse ($summaryPerItem as $item)
                        <li class="px-6 py-3 flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-700">{{ $item->nama_item }}</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($item->total_terkumpul, 0, ',', '.') }}</span>
                        </li>
                    @empty
                        <li class="px-6 py-6 text-center text-gray-500">Tidak ada rincian item.</li>
                    @endforelse
                </ul>
            </div>

            {{-- RINCIAN SISWA (Grid 2 Kolom akan lebih lebar) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- PUTRA --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 h-full">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-800">Santri Putra ({{ $santriPutra->count() }})</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <table class="min-w-full">
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($santriPutra as $santri)
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-2 font-bold text-sm text-gray-800">{{ $santri['nama'] }}</td>
                                        <td class="px-6 py-2 text-right font-bold text-sm text-gray-800">Rp {{ number_format($santri['total'], 0, ',', '.') }}</td>
                                    </tr>
                                    @foreach($santri['rincian'] as $transaksi)
                                        <tr>
                                            <td class="px-6 py-1 pl-10 text-xs text-gray-500">
                                                {{ $transaksi->tagihan->jenisPembayaran->name }}
                                            </td>
                                            <td class="px-6 py-1 text-right text-xs text-gray-500">
                                                {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr><td colspan="2" class="px-6 py-4 text-center text-gray-500 text-sm">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- PUTRI --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 h-full">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-800">Santri Putri ({{ $santriPutri->count() }})</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <table class="min-w-full">
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($santriPutri as $santri)
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-2 font-bold text-sm text-gray-800">{{ $santri['nama'] }}</td>
                                        <td class="px-6 py-2 text-right font-bold text-sm text-gray-800">Rp {{ number_format($santri['total'], 0, ',', '.') }}</td>
                                    </tr>
                                    @foreach($santri['rincian'] as $transaksi)
                                        <tr>
                                            <td class="px-6 py-1 pl-10 text-xs text-gray-500">
                                                {{ $transaksi->tagihan->jenisPembayaran->name }}
                                            </td>
                                            <td class="px-6 py-1 text-right text-xs text-gray-500">
                                                {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr><td colspan="2" class="px-6 py-4 text-center text-gray-500 text-sm">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>