<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Akun Guru Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('sekolah.superadmin.guru.store') }}">
                        @csrf

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Data Akun Login</h3>
                        
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap (beserta gelar)')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <hr class="my-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Data Profil Guru & Penugasan</h3>

                        <div class="mt-4">
                            <x-input-label for="nip" :value="__('NIP / No. Pegawai (Opsional)')" />
                            <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip')" />
                            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="telepon" :value="__('Nomor Telepon / WA (Wajib untuk Notifikasi)')" />
                            <x-text-input id="telepon" class="block mt-1 w-full" type="number" name="telepon" :value="old('telepon')" required placeholder="Cth: 628123456789" />
                            <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500">Awali dengan 62 (bukan 0). Contoh: 628123456789</p>
                        </div>
                        
                        <div class="mt-4">
                            <x-input-label for="alamat" :value="__('Alamat (Opsional)')" />
                            <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('alamat') }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label :value="__('Tugaskan ke Unit Sekolah (Bisa pilih lebih dari satu)')" />
                            <div class="space-y-2 mt-2">
                                @foreach($sekolahs as $sekolah)
                                    <label class="flex items-center">
                                        {{-- 
                                          - Nama diubah menjadi 'sekolah_ids[]' (sebuah array)
                                          - @checked() ditambahkan untuk mengingat pilihan lama jika validasi gagal
                                        --}}
                                        <input type="checkbox" name="sekolah_ids[]" value="{{ $sekolah->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               @checked(is_array(old('sekolah_ids')) && in_array($sekolah->id, old('sekolah_ids')))>
                                        
                                        <span class="ms-2 text-sm text-gray-600">{{ $sekolah->nama_sekolah }} ({{ $sekolah->tingkat }})</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('sekolah_ids')" class="mt-2" />
                            <x-input-error :messages="$errors->get('sekolah_ids.*')" class="mt-2" />
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>