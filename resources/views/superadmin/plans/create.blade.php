<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                {{-- HEADER KARTU --}}
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Buat Paket Baru</h2>
                    <a href="{{ route('superadmin.plans.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('superadmin.plans.store') }}">
                        @csrf
                        
                        <div class="max-w-3xl">
                            <div class="mb-6">
                                <x-input-label for="name" :value="__('Nama Paket')" />
                                <x-text-input id="name" class="block mt-1 w-full text-lg" type="text" name="name" :value="old('name')" required placeholder="Contoh: Paket Basic, Paket Premium" autofocus />
                                <p class="text-sm text-gray-500 mt-1">Nama yang akan muncul di tagihan pondok.</p>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="price_monthly" :value="__('Harga Bulanan (Rp)')" />
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <x-text-input id="price_monthly" class="block w-full pl-10" type="number" name="price_monthly" :value="old('price_monthly')" required placeholder="0" />
                                    </div>
                                    <x-input-error :messages="$errors->get('price_monthly')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="price_yearly" :value="__('Harga Tahunan (Rp)')" />
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <x-text-input id="price_yearly" class="block w-full pl-10" type="number" name="price_yearly" :value="old('price_yearly')" required placeholder="0" />
                                    </div>
                                    <x-input-error :messages="$errors->get('price_yearly')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-start mt-8 pt-6 border-t border-gray-100 gap-4">
                            <x-primary-button class="text-base py-2.5 px-6">
                                {{ __('Simpan Paket') }}
                            </x-primary-button>
                            <a href="{{ route('superadmin.plans.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>