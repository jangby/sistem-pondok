{{-- Tambahkan div x-data di paling luar untuk modal --}}
<div x-data="{ open: false }" @keydown.escape.window="open = false">

<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- HEADER HALAMAN --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-gray-800">Detail Tagihan</h2>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-sm font-mono font-medium">
                        #{{ $tagihan->invoice_number }}
                    </span>
                </div>
                <a href="{{ route('adminpondok.tagihan.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KOLOM UTAMA (Kiri): Rincian Tagihan --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $tagihan->jenisPembayaran->name }}</h3>
                                @if($tagihan->periode_bulan)
                                    <p class="text-sm text-gray-500">
                                        Periode: {{ \Carbon\Carbon::create(null, $tagihan->periode_bulan, 1)->format('F') }} {{ $tagihan->periode_tahun }}
                                    </p>
                                @endif
                            </div>
                            {{-- Status Badge Besar --}}
                            @if($tagihan->status == 'paid')
                                <span class="px-4 py-1.5 bg-emerald-100 text-emerald-800 rounded-full text-sm font-bold border border-emerald-200">LUNAS</span>
                            @elseif($tagihan->status == 'partial')
                                <span class="px-4 py-1.5 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold border border-yellow-200">DICICIL</span>
                            @else
                                <span class="px-4 py-1.5 bg-red-100 text-red-800 rounded-full text-sm font-bold border border-red-200">BELUM LUNAS</span>
                            @endif
                        </div>

                        <div class="p-0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Item Pembayaran</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nominal</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Sisa Tagihan</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($tagihan->tagihanDetails->sortBy('prioritas') as $item)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $item->nama_item }}
                                                @if($item->prioritas <= 1) 
                                                    <span class="ml-1 text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded">Prioritas</span> 
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm text-gray-600">
                                                Rp {{ number_format($item->nominal_item, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-bold {{ $item->sisa_tagihan_item > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                                Rp {{ number_format($item->sisa_tagihan_item, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($item->status_item == 'lunas')
                                                    <svg class="w-5 h-5 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                @else
                                                    <div class="w-2 h-2 bg-red-400 rounded-full mx-auto"></div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Data item kosong.</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-800">TOTAL TAGIHAN</td>
                                        <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                            Rp {{ number_format($tagihan->nominal_tagihan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-bold text-red-600">
                                            Rp {{ number_format($tagihan->tagihanDetails->sum('sisa_tagihan_item'), 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Anda bisa menambahkan riwayat pembayaran di sini jika ada --}}

                </div>

                {{-- KOLOM SIDEBAR (Kanan): Aksi & Info --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- KARTU AKSI PEMBAYARAN --}}
                    @php
                        $totalSisa = $tagihan->tagihanDetails->sum('sisa_tagihan_item');
                    @endphp

                    @if ($totalSisa > 0)
                        <div class="bg-white shadow-sm rounded-xl border border-emerald-200 overflow-hidden">
                            <div class="p-6 bg-emerald-600 text-center">
                                <p class="text-emerald-100 text-sm font-medium uppercase tracking-wider mb-1">Sisa Tagihan</p>
                                <h3 class="text-3xl font-bold text-white">Rp {{ number_format($totalSisa, 0, ',', '.') }}</h3>
                            </div>
                            <div class="p-6 bg-white">
                                <button @click="open = true" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-emerald-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Catat Bayar Manual
                                </button>
                                <p class="text-xs text-center text-gray-500 mt-3">
                                    Gunakan tombol ini jika wali santri membayar tunai atau transfer langsung ke rekening pondok (bukan via Payment Gateway).
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 text-center">
                            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-emerald-800">Tagihan Lunas</h3>
                            <p class="text-sm text-emerald-600 mt-1">Tidak ada tindakan yang diperlukan.</p>
                        </div>
                    @endif

                    {{-- KARTU INFO SANTRI --}}
                    <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Data Santri
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Nama:</span>
                                <span class="font-medium text-gray-900 text-right">{{ $tagihan->santri->full_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">NIS:</span>
                                <span class="font-medium text-gray-900">{{ $tagihan->santri->nis }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Wali:</span>
                                <span class="font-medium text-gray-900">{{ $tagihan->santri->orangTua->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Telp Wali:</span>
                                <span class="font-medium text-gray-900">{{ $tagihan->santri->orangTua->phone ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    
    {{-- MODAL PEMBAYARAN MANUAL --}}
    <div x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm" 
         style="display: none;">
        
        <div x-show="open" @click.outside="open = false" 
             class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all sm:w-full sm:max-w-lg">
            
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Catat Pembayaran Manual</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('adminpondok.tagihan.pay-manual', $tagihan->id) }}">
                @csrf
                <div class="p-6 space-y-4">
                    
                    <div class="bg-yellow-50 p-3 rounded-lg text-sm text-yellow-800 mb-4">
                        <p><strong>Perhatian:</strong> Fitur ini untuk mencatat pembayaran yang diterima secara <strong>Offline/Tunai</strong>. Tagihan akan otomatis terupdate.</p>
                    </div>

                    <div>
                        <x-input-label for="nominal_bayar" :value="__('Jumlah yang Dibayar (Rp)')" />
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <x-text-input id="nominal_bayar" class="block w-full pl-10 text-lg font-bold text-emerald-600" type="number" name="nominal_bayar" :value="old('nominal_bayar', $totalSisa)" max="{{ $totalSisa }}" required autofocus />
                        </div>
                        <x-input-error :messages="$errors->get('nominal_bayar')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label for="catatan" :value="__('Metode / Catatan')" />
                        <x-text-input id="catatan" class="block mt-1 w-full" type="text" name="catatan" :value="old('catatan', 'Tunai')" required placeholder="Contoh: Tunai, Transfer ke BSI" />
                        <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <x-primary-button>
                        Simpan Pembayaran
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
</div> {{-- Penutup div x-data --}}