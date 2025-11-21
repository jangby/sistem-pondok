<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Data Mustawa') }}
            </h2>
            <a href="{{ route('pendidikan.admin.mustawa.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-8 text-gray-900">
                    
                    <form method="POST" action="{{ route('pendidikan.admin.mustawa.update', $mustawa->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="col-span-2">
                                <x-input-label for="nama" :value="__('Nama Kelas')" />
                                <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama', $mustawa->nama)" required />
                                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tingkat" :value="__('Tingkat (Level)')" />
                                <select name="tingkat" id="tingkat" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('tingkat', $mustawa->tingkat) == $i ? 'selected' : '' }}>Level {{ $i }}</option>
                                    @endfor
                                    <option value="7" {{ old('tingkat', $mustawa->tingkat) == 7 ? 'selected' : '' }}>Takhosus</option>
                                </select>
                                <x-input-error :messages="$errors->get('tingkat')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="tahun_ajaran" :value="__('Tahun Ajaran')" />
                                <x-text-input id="tahun_ajaran" class="block mt-1 w-full" type="text" name="tahun_ajaran" :value="old('tahun_ajaran', $mustawa->tahun_ajaran)" required />
                                <x-input-error :messages="$errors->get('tahun_ajaran')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="gender" :value="__('Kategori Gender')" />
                                <select name="gender" id="gender" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="putra" {{ old('gender', $mustawa->gender) == 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                                    <option value="putri" {{ old('gender', $mustawa->gender) == 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                                    <option value="campuran" {{ old('gender', $mustawa->gender) == 'campuran' ? 'selected' : '' }}>Campuran</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="wali_ustadz_id" :value="__('Wali Kelas (Ustadz)')" />
                            <select name="wali_ustadz_id" id="wali_ustadz_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach ($ustadzs as $ustadz)
                                    <option value="{{ $ustadz->id }}" {{ old('wali_ustadz_id', $mustawa->wali_ustadz_id) == $ustadz->id ? 'selected' : '' }}>
                                        {{ $ustadz->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('wali_ustadz_id')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="is_active" value="1" {{ old('is_active', $mustawa->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Status Kelas Aktif') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <x-primary-button class="ml-3 bg-emerald-600 hover:bg-emerald-700">
                                {{ __('Perbarui Data') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>