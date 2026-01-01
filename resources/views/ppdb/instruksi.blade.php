<x-app-layout hide-nav>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Instruksi Pembayaran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto px-4">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-6 text-center bg-gray-50 border-b">
                    <p class="text-sm text-gray-500 mb-2">Total Pembayaran</p>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Rp {{ number_format($transaksi->gross_amount + $transaksi->biaya_admin, 0, ',', '.') }}
                    </h1>
                    <span class="inline-block mt-2 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase tracking-wide">
                        Menunggu Pembayaran
                    </span>
                </div>

                <div class="p-6">
                    @if(in_array($transaksi->payment_type, ['bca_va', 'bni_va', 'mandiri_bill']))
                        <div class="text-center">
                            <p class="text-gray-600 mb-2">Nomor Virtual Account:</p>
                            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl flex justify-between items-center">
                                <span class="font-mono text-2xl font-bold text-emerald-700 tracking-wider">
                                    {{ $transaksi->payment_code }}
                                </span>
                                <button onclick="navigator.clipboard.writeText('{{ $transaksi->payment_code }}')" class="text-emerald-600 hover:text-emerald-800 text-sm font-bold">
                                    Salin
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-4">Silakan transfer ke nomor di atas sebelum 24 jam.</p>
                        </div>
                    
                    @elseif($transaksi->payment_type == 'qris')
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Scan QRIS di bawah ini:</p>
                            <div class="inline-block p-2 border rounded-lg">
                                <img src="{{ $transaksi->payment_url }}" alt="QRIS Code" class="w-64 h-64">
                            </div>
                            <p class="text-xs text-gray-400 mt-4">Dapat discan menggunakan GoPay, OVO, Dana, LinkAja, dll.</p>
                        </div>
                    @endif
                </div>

                <div class="p-4 bg-gray-50 border-t text-center">
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-900 font-medium">
                        &larr; Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>