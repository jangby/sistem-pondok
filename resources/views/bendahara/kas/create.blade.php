<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('bendahara.kas.index') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Catat Pengeluaran</h1>
            </div>
        </div>

        {{-- 2. FORM KARTU --}}
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <form method="POST" action="{{ route('bendahara.kas.store') }}">
                    @csrf
                    
                    <div class="mb-5">
                        <x-input-label for="deskripsi" :value="__('Untuk Keperluan Apa?')" />
                        <x-text-input id="deskripsi" class="block mt-1 w-full" type="text" name="deskripsi" :value="old('deskripsi')" required autofocus placeholder="Cth: Beli ATK, Bayar Listrik" />
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <x-input-label for="nominal" :value="__('Nominal Pengeluaran (Rp)')" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">Rp</span>
                            </div>
                            <x-text-input id="nominal" class="block w-full pl-10 text-lg font-bold text-red-600" type="number" name="nominal" :value="old('nominal')" required placeholder="0" />
                        </div>
                        <x-input-error :messages="$errors->get('nominal')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="tanggal_transaksi" :value="__('Tanggal')" />
                        <x-text-input id="tanggal_transaksi" class="block mt-1 w-full" type="date" name="tanggal_transaksi" :value="old('tanggal_transaksi', date('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('tanggal_transaksi')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-red-600 text-white font-bold rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        Simpan Pengeluaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.bendahara-nav')
</x-app-layout>