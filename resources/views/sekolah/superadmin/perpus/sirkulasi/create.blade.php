<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Peminjaman Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('sekolah.superadmin.perpustakaan.sirkulasi.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="kode_santri" :value="__('Scan Kartu Santri / NIS')" />
                                <x-text-input id="kode_santri" class="block mt-1 w-full text-lg" type="text" name="kode_santri" :value="old('kode_santri')" required autofocus placeholder="Scan QR..." />
                                <p class="text-xs text-gray-500 mt-1">Pastikan kursor aktif di sini saat scan kartu.</p>
                                <x-input-error :messages="$errors->get('kode_santri')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="kode_buku" :value="__('Scan Barcode Buku')" />
                                <x-text-input id="kode_buku" class="block mt-1 w-full text-lg" type="text" name="kode_buku" :value="old('kode_buku')" required placeholder="Scan Barcode..." />
                                <x-input-error :messages="$errors->get('kode_buku')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="tgl_wajib_kembali" :value="__('Tanggal Wajib Kembali')" />
                            <x-text-input id="tgl_wajib_kembali" class="block mt-1 w-full md:w-1/2" type="date" name="tgl_wajib_kembali" :value="$defaultKembali" required />
                            <x-input-error :messages="$errors->get('tgl_wajib_kembali')" class="mt-2" />
                        </div>

                        <div class="mt-8 flex justify-end">
                            <x-primary-button class="ml-3">
                                {{ __('Proses Peminjaman') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>