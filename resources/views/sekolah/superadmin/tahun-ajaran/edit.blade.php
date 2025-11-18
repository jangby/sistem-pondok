<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tahun Ajaran: ') }} {{ $tahunAjaran->nama_tahun_ajaran }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('sekolah.superadmin.tahun-ajaran.update', $tahunAjaran->id) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="nama_tahun_ajaran" :value="__('Nama Tahun Ajaran')" />
                            <x-text-input id="nama_tahun_ajaran" class="block mt-1 w-full" type="text" name="nama_tahun_ajaran" :value="old('nama_tahun_ajaran', $tahunAjaran->nama_tahun_ajaran)" required autofocus placeholder="Cth: 2025/2026 Ganjil" />
                            <x-input-error :messages="$errors->get('nama_tahun_ajaran')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                            <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="date" name="tanggal_mulai" :value="old('tanggal_mulai', $tahunAjaran->tanggal_mulai)" required />
                            <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tanggal_selesai" :value="__('Tanggal Selesai')" />
                            <x-text-input id="tanggal_selesai" class="block mt-1 w-full" type="date" name="tanggal_selesai" :value="old('tanggal_selesai', $tahunAjaran->tanggal_selesai)" required />
                            <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                        </div>
                        
                        {{-- Checkbox 'is_active' bisa ditambahkan di sini jika perlu, tapi logika tombol di 'index' lebih aman --}}

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