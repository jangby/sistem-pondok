<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jadwal Ujian: ') }} {{ $kegiatanAkademik->nama_kegiatan }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('sekolah.admin.kegiatan-akademik.update', $kegiatanAkademik->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                            <x-input-label :value="__('Tahun Ajaran')" />
                            <p class="text-lg font-semibold text-gray-800">{{ $kegiatanAkademik->tahunAjaran->nama_tahun_ajaran }}</p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="nama_kegiatan" :value="__('Nama Kegiatan')" />
                            <x-text-input id="nama_kegiatan" class="block mt-1 w-full" type="text" name="nama_kegiatan" :value="old('nama_kegiatan', $kegiatanAkademik->nama_kegiatan)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_kegiatan')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tipe" :value="__('Tipe Kegiatan')" />
                            <select name="tipe" id="tipe" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="UTS" @selected(old('tipe', $kegiatanAkademik->tipe) == 'UTS')>UTS (Ujian Tengah Semester)</option>
                                <option value="UAS" @selected(old('tipe', $kegiatanAkademik->tipe) == 'UAS')>UAS (Ujian Akhir Semester)</option>
                                <option value="Harian" @selected(old('tipe', $kegiatanAkademik->tipe) == 'Harian')>Harian (Cth: Ujian Harian)</option>
                                <option value="Lainnya" @selected(old('tipe', $kegiatanAkademik->tipe) == 'Lainnya')>Lainnya</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                            <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="date" name="tanggal_mulai" :value="old('tanggal_mulai', $kegiatanAkademik->tanggal_mulai)" required />
                            <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tanggal_selesai" :value="__('Tanggal Selesai')" />
                            <x-text-input id="tanggal_selesai" class="block mt-1 w-full" type="date" name="tanggal_selesai" :value="old('tanggal_selesai', $kegiatanAkademik->tanggal_selesai)" required />
                            <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                        </div>
                        
                        <div class="mt-4">
                            <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                            <textarea id="keterangan" name="keterangan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('keterangan', $kegiatanAkademik->keterangan) }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
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