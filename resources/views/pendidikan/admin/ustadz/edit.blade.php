<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Ustadz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <form method="POST" action="{{ route('pendidikan.admin.ustadz.update', $ustadz->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-emerald-700 border-b pb-2">Data Pribadi</h3>
                            
                            <div>
                                <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
                                <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap', $ustadz->nama_lengkap)" required />
                            </div>

                            <div>
                                <x-input-label for="nip" :value="__('NIP / NIY')" />
                                <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip', $ustadz->nip)" />
                            </div>

                            <div>
                                <x-input-label for="spesialisasi" :value="__('Spesialisasi')" />
                                <x-text-input id="spesialisasi" class="block mt-1 w-full" type="text" name="spesialisasi" :value="old('spesialisasi', $ustadz->spesialisasi)" />
                            </div>

                            <div>
                                <x-input-label for="alamat" :value="__('Alamat')" />
                                <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" rows="3">{{ old('alamat', $ustadz->alamat) }}</textarea>
                            </div>

                            <div class="mt-4">
                                <label for="is_active" class="inline-flex items-center">
                                    <input id="is_active" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="is_active" value="1" {{ $ustadz->is_active ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Status Ustadz Aktif') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-emerald-700 border-b pb-2">Update Akun</h3>

                            <div>
                                <x-input-label for="no_hp" :value="__('Nomor WhatsApp')" />
                                <x-text-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp', $ustadz->no_hp)" required />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email Login')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $ustadz->user->email ?? '')" required />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="text" name="password" placeholder="Isi jika ingin ganti password" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('pendidikan.admin.ustadz.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                        <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">
                            {{ __('Perbarui Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>