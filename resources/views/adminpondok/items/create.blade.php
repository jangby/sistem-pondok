<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        {{-- Ubah max-w-2xl menjadi max-w-7xl agar lebar penuh --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                {{-- HEADER KARTU (Menyatu dengan tombol Kembali) --}}
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Tambah Item Rincian</h2>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Menambah komponen biaya untuk: <span class="font-semibold text-emerald-600">{{ $jenisPembayaran->name }}</span>
                        </p>
                    </div>
                    <a href="{{ route('adminpondok.jenis-pembayarans.show', $jenisPembayaran->id) }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                <div class="p-8 text-gray-900">
                    
                    <form method="POST" action="{{ route('adminpondok.jenis-pembayarans.items.store', $jenisPembayaran->id) }}">
                        @csrf

                        {{-- GRID LAYOUT: 2 Kolom (Kiri: Nama, Kanan: Nominal & Prioritas) --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI --}}
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="nama_item" :value="__('Nama Item')" />
                                    <x-text-input id="nama_item" class="block mt-1 w-full" type="text" name="nama_item" :value="old('nama_item')" required autofocus placeholder="Contoh: Uang Makan, Uang Listrik, Asrama Putra" />
                                    <p class="text-xs text-gray-500 mt-1">Nama komponen biaya yang akan muncul di tagihan.</p>
                                    <x-input-error :messages="$errors->get('nama_item')" class="mt-2" />
                                </div>
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="nominal" :value="__('Nominal (Rp)')" />
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <x-text-input id="nominal" class="block w-full pl-10" type="number" name="nominal" :value="old('nominal')" required placeholder="0" />
                                    </div>
                                    <x-input-error :messages="$errors->get('nominal')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="prioritas" :value="__('Urutan Prioritas')" />
                                    <x-text-input id="prioritas" class="block mt-1 w-full" type="number" name="prioritas" :value="old('prioritas', 1)" required min="1" />
                                    <p class="text-xs text-gray-500 mt-1">Item dengan prioritas lebih kecil (misal: 1) akan dilunasi terlebih dahulu saat pembayaran cicilan.</p>
                                    <x-input-error :messages="$errors->get('prioritas')" class="mt-2" />
                                </div>
                            </div>

                        </div>

                        {{-- FOOTER TOMBOL --}}
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('adminpondok.jenis-pembayarans.show', $jenisPembayaran->id) }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-2.5">
                                {{ __('Simpan Item') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>