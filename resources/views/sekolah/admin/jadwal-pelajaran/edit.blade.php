<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jadwal Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('sekolah.admin.jadwal-pelajaran.update', $jadwalPelajaran->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                            <x-input-label :value="__('Tahun Ajaran')" />
                            <p class="text-lg font-semibold text-gray-800">{{ $jadwalPelajaran->tahunAjaran->nama_tahun_ajaran }}</p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="kelas_id" :value="__('Kelas')" />
                            <select name="kelas_id" id="kelas_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" @selected(old('kelas_id', $jadwalPelajaran->kelas_id) == $kelas->id)>
                                        {{ $kelas->nama_kelas }} (Tingkat: {{ $kelas->tingkat }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="mata_pelajaran_id" :value="__('Mata Pelajaran')" />
                            <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mapelList as $mapel)
                                    <option value="{{ $mapel->id }}" @selected(old('mata_pelajaran_id', $jadwalPelajaran->mata_pelajaran_id) == $mapel->id)>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('mata_pelajaran_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="guru_user_id" :value="__('Guru Pengajar')" />
                            <select name="guru_user_id" id="guru_user_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($guruList as $guru)
                                    <option value="{{ $guru->id }}" @selected(old('guru_user_id', $jadwalPelajaran->guru_user_id) == $guru->id)>
                                        {{ $guru->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('guru_user_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="hari" :value="__('Hari')" />
                            <select name="hari" id="hari" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                    <option value="{{ $hari }}" @selected(old('hari', $jadwalPelajaran->hari) == $hari)>
                                        {{ $hari }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('hari')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="jam_mulai" :value="__('Jam Mulai')" />
                            <x-text-input id="jam_mulai" class="block mt-1 w-full" type="time" name="jam_mulai" :value="old('jam_mulai', $jadwalPelajaran->jam_mulai)" required />
                            <x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="jam_selesai" :value="__('Jam Selesai')" />
                            <x-text-input id="jam_selesai" class="block mt-1 w-full" type="time" name="jam_selesai" :value="old('jam_selesai', $jadwalPelajaran->jam_selesai)" required />
                            <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
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