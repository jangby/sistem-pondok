<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('bendahara.kas.index') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Edit Transaksi</h1>
            </div>
        </div>

        {{-- FORM KARTU --}}
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <form method="POST" action="{{ route('bendahara.kas.update', $kas->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-5">
                        <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                        <x-text-input id="deskripsi" class="block mt-1 w-full" type="text" name="deskripsi" :value="old('deskripsi', $kas->deskripsi)" required autofocus />
                    </div>

                    <div class="mb-5">
                        <x-input-label for="nominal" :value="__('Nominal (Rp)')" />
                        <x-text-input id="nominal" class="block mt-1 w-full text-lg font-bold text-gray-800" type="number" name="nominal" :value="old('nominal', $kas->nominal)" required />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="tanggal_transaksi" :value="__('Tanggal')" />
                        <x-text-input id="tanggal_transaksi" class="block mt-1 w-full" type="date" name="tanggal_transaksi" :value="old('tanggal_transaksi', $kas->tanggal_transaksi->format('Y-m-d'))" required />
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl shadow-lg hover:bg-emerald-700 transition active:scale-[0.98]">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.bendahara-nav')
</x-app-layout>