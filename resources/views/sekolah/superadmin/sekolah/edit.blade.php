<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Unit Sekolah: ') }} {{ $sekolah->nama_sekolah }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('sekolah.superadmin.sekolah.update', $sekolah->id) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="nama_sekolah" :value="__('Nama Unit Sekolah')" />
                            <x-text-input id="nama_sekolah" class="block mt-1 w-full" type="text" name="nama_sekolah" :value="old('nama_sekolah', $sekolah->nama_sekolah)" required autofocus placeholder="Cth: MTS Al-Hidayah" />
                            <x-input-error :messages="$errors->get('nama_sekolah')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tingkat" :value="__('Tingkat')" />
                            <select name="tingkat" id="tingkat" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="MI" @selected(old('tingkat', $sekolah->tingkat) == 'MI')>MI (Madrasah Ibtidaiyah)</option>
                                <option value="MTS" @selected(old('tingkat', $sekolah->tingkat) == 'MTS')>MTS (Madrasah Tsanawiyah)</option>
                                <option value="MA" @selected(old('tingkat', $sekolah->tingkat) == 'MA')>MA (Madrasah Aliyah)</option>
                                <option value="SD" @selected(old('tingkat', $sekolah->tingkat) == 'SD')>SD (Sekolah Dasar)</option>
                                <option value="SMP" @selected(old('tingkat', $sekolah->tingkat) == 'SMP')>SMP (Sekolah Menengah Pertama)</option>
                                <option value="SMA" @selected(old('tingkat', $sekolah->tingkat) == 'SMA')>SMA (Sekolah Menengah Atas)</option>
                                <option value="SMK" @selected(old('tingkat', $sekolah->tingkat) == 'SMK')>SMK (Sekolah Menengah Kejuruan)</option>
                                <option value="Lainnya" @selected(old('tingkat', $sekolah->tingkat) == 'Lainnya')>Lainnya</option>
                            </select>
                            <x-input-error :messages="$errors->get('tingkat')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="kepala_sekolah" :value="__('Nama Kepala Sekolah (Opsional)')" />
                            <x-text-input id="kepala_sekolah" class="block mt-1 w-full" type="text" name="kepala_sekolah" :value="old('kepala_sekolah', $sekolah->kepala_sekolah)" />
                            <x-input-error :messages="$errors->get('kepala_sekolah')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>