<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pembayaran PPDB
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                
                {{-- Info Tagihan --}}
                <div class="text-center mb-8">
                    <p class="text-gray-500 text-sm">Sisa Tagihan Anda</p>
                    <h1 class="text-4xl font-extrabold text-gray-900 mt-2">
                        Rp {{ number_format($calonSantri->sisa_tagihan, 0, ',', '.') }}
                    </h1>
                </div>

                <hr class="border-gray-100 mb-6">

                {{-- FORM INPUT NOMINAL --}}
                <form action="{{ route('ppdb.payment.process') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <x-input-label for="nominal_bayar" value="Masukkan Nominal Pembayaran" />
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                            </div>
                            <input type="number" name="nominal_bayar" id="nominal_bayar" 
                                class="block w-full rounded-md border-gray-300 pl-10 focus:border-emerald-500 focus:ring-emerald-500 sm:text-lg font-bold py-3" 
                                placeholder="0"
                                min="10000"
                                max="{{ $calonSantri->sisa_tagihan }}"
                                required
                                value="{{ old('nominal_bayar', $calonSantri->sisa_tagihan) }}">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-gray-800 transition transform hover:-translate-y-0.5">
                        Lanjut ke Pembayaran &rarr;
                    </button>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Batal / Kembali</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    {{-- Script Midtrans SUDAHDIHAPUS karena kita pakai redirect --}}

</x-app-layout>