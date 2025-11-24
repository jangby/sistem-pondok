<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Buku Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('sekolah.superadmin.perpustakaan.buku.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Judul Buku --}}
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="judul" :value="__('Judul Buku')" />
                                <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" :value="old('judul')" required autofocus placeholder="Contoh: Sejarah Kebudayaan Islam" />
                                <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                            </div>

                            {{-- Penulis --}}
                            <div>
                                <x-input-label for="penulis" :value="__('Penulis')" />
                                <x-text-input id="penulis" class="block mt-1 w-full" type="text" name="penulis" :value="old('penulis')" placeholder="Nama Penulis" />
                                <x-input-error :messages="$errors->get('penulis')" class="mt-2" />
                            </div>

                            {{-- Penerbit --}}
                            <div>
                                <x-input-label for="penerbit" :value="__('Penerbit')" />
                                <x-text-input id="penerbit" class="block mt-1 w-full" type="text" name="penerbit" :value="old('penerbit')" placeholder="Nama Penerbit" />
                                <x-input-error :messages="$errors->get('penerbit')" class="mt-2" />
                            </div>

                            {{-- Tahun Terbit & ISBN --}}
                            <div>
                                <x-input-label for="tahun_terbit" :value="__('Tahun Terbit')" />
                                <x-text-input id="tahun_terbit" class="block mt-1 w-full" type="number" name="tahun_terbit" :value="old('tahun_terbit')" placeholder="2024" />
                            </div>

                            <div>
                                <x-input-label for="isbn" :value="__('ISBN')" />
                                <x-text-input id="isbn" class="block mt-1 w-full" type="text" name="isbn" :value="old('isbn')" placeholder="xxx-xxx-xxx" />
                            </div>

                            {{-- Stok & Rak --}}
                            <div>
                                <x-input-label for="stok_total" :value="__('Jumlah Stok')" />
                                <x-text-input id="stok_total" class="block mt-1 w-full" type="number" min="0" name="stok_total" :value="old('stok_total', 1)" required />
                                <p class="text-xs text-gray-500 mt-1">Jumlah fisik buku yang ada.</p>
                                <x-input-error :messages="$errors->get('stok_total')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="lokasi_rak" :value="__('Lokasi Rak')" />
                                <x-text-input id="lokasi_rak" class="block mt-1 w-full" type="text" name="lokasi_rak" :value="old('lokasi_rak')" placeholder="Contoh: Rak A-03" />
                            </div>

                            {{-- Kode Buku / Barcode --}}
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="kode_buku" :value="__('Kode Buku / Barcode (Opsional)')" />
                                <x-text-input id="kode_buku" class="block mt-1 w-full bg-gray-50" type="text" name="kode_buku" :value="old('kode_buku')" placeholder="Scan barcode di sini atau biarkan kosong untuk generate otomatis" />
                                <p class="text-xs text-gray-500 mt-1">Jika kosong, sistem akan membuatkan kode unik otomatis.</p>
                                <x-input-error :messages="$errors->get('kode_buku')" class="mt-2" />
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="deskripsi" :value="__('Deskripsi Singkat')" />
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi') }}</textarea>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('sekolah.superadmin.perpustakaan.buku.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>
                                {{ __('Simpan Buku') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>