<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Buku: ') }} {{ $buku->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('sekolah.superadmin.perpustakaan.buku.update', $buku->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Judul Buku --}}
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="judul" :value="__('Judul Buku')" />
                                <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" :value="old('judul', $buku->judul)" required />
                                <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                            </div>

                            {{-- Penulis --}}
                            <div>
                                <x-input-label for="penulis" :value="__('Penulis')" />
                                <x-text-input id="penulis" class="block mt-1 w-full" type="text" name="penulis" :value="old('penulis', $buku->penulis)" />
                            </div>

                            {{-- Penerbit --}}
                            <div>
                                <x-input-label for="penerbit" :value="__('Penerbit')" />
                                <x-text-input id="penerbit" class="block mt-1 w-full" type="text" name="penerbit" :value="old('penerbit', $buku->penerbit)" />
                            </div>

                            {{-- Tahun Terbit & ISBN --}}
                            <div>
                                <x-input-label for="tahun_terbit" :value="__('Tahun Terbit')" />
                                <x-text-input id="tahun_terbit" class="block mt-1 w-full" type="number" name="tahun_terbit" :value="old('tahun_terbit', $buku->tahun_terbit)" />
                            </div>

                            <div>
                                <x-input-label for="isbn" :value="__('ISBN')" />
                                <x-text-input id="isbn" class="block mt-1 w-full" type="text" name="isbn" :value="old('isbn', $buku->isbn)" />
                            </div>

                            {{-- Stok & Rak --}}
                            <div>
                                <x-input-label for="stok_total" :value="__('Jumlah Stok Total')" />
                                <x-text-input id="stok_total" class="block mt-1 w-full" type="number" min="0" name="stok_total" :value="old('stok_total', $buku->stok_total)" required />
                                <p class="text-xs text-blue-600 mt-1">Stok Tersedia saat ini: <b>{{ $buku->stok_tersedia }}</b></p>
                                <x-input-error :messages="$errors->get('stok_total')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="lokasi_rak" :value="__('Lokasi Rak')" />
                                <x-text-input id="lokasi_rak" class="block mt-1 w-full" type="text" name="lokasi_rak" :value="old('lokasi_rak', $buku->lokasi_rak)" />
                            </div>

                            {{-- Kode Buku / Barcode --}}
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="kode_buku" :value="__('Kode Buku / Barcode')" />
                                <x-text-input id="kode_buku" class="block mt-1 w-full bg-gray-100" type="text" name="kode_buku" :value="old('kode_buku', $buku->kode_buku)" required />
                                <p class="text-xs text-gray-500 mt-1">Kode unik untuk scan.</p>
                                <x-input-error :messages="$errors->get('kode_buku')" class="mt-2" />
                            </div>

                             {{-- Deskripsi --}}
                             <div class="col-span-1 md:col-span-2">
                                <x-input-label for="deskripsi" :value="__('Deskripsi Singkat')" />
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                            </div>

                        </div>

                        <div class="flex items-center justify-between mt-8 pt-4 border-t">
                            {{-- Tombol Hapus (Optional, hati-hati pakai ini) --}}
                            <div x-data="">
                                <button type="button" x-on:click="$dispatch('open-modal', 'confirm-book-deletion')" class="text-red-600 text-sm hover:underline">
                                    {{ __('Hapus Buku Ini') }}
                                </button>
                            </div>

                            <div class="flex items-center">
                                <a href="{{ route('sekolah.superadmin.perpustakaan.buku.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                                <x-primary-button>
                                    {{ __('Update Buku') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    {{-- Modal Konfirmasi Hapus --}}
                    <x-modal name="confirm-book-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="POST" action="{{ route('sekolah.superadmin.perpustakaan.buku.destroy', $buku->id) }}" class="p-6">
                            @csrf
                            @method('DELETE')

                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Apakah Anda yakin ingin menghapus buku ini?') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Data buku akan dihapus. Pastikan tidak ada transaksi peminjaman aktif yang terkait dengan buku ini.') }}
                            </p>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Batal') }}
                                </x-secondary-button>

                                <x-danger-button class="ms-3">
                                    {{ __('Hapus Buku') }}
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>