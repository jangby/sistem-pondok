<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jadwal Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <form method="POST" action="{{ route('pendidikan.admin.jadwal.update', $jadwal->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        {{-- Pilihan Waktu --}}
                        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-700 mb-3">Waktu & Tanggal</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="hari" :value="__('Hari')" />
                                    <select name="hari" id="hari" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                        @foreach($hari_list as $h)
                                            <option value="{{ $h }}" {{ old('hari', $jadwal->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('hari')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="jam_mulai" :value="__('Jam Mulai')" />
                                    <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                </div>
                                <div>
                                    <x-input-label for="jam_selesai" :value="__('Jam Selesai')" />
                                    <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Pelajaran --}}
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="mustawa_id" :value="__('Kelas (Mustawa)')" />
                                <select name="mustawa_id" id="mustawa_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                    @foreach($mustawas as $m)
                                        <option value="{{ $m->id }}" {{ old('mustawa_id', $jadwal->mustawa_id) == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="mapel_diniyah_id" :value="__('Mata Pelajaran / Kitab')" />
                                <select name="mapel_diniyah_id" id="mapel_diniyah_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                    @foreach($mapels as $mp)
                                        <option value="{{ $mp->id }}" {{ old('mapel_diniyah_id', $jadwal->mapel_diniyah_id) == $mp->id ? 'selected' : '' }}>
                                            {{ $mp->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="ustadz_id" :value="__('Ustadz Pengajar')" />
                                <select name="ustadz_id" id="ustadz_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                    @foreach($ustadzs as $us)
                                        <option value="{{ $us->id }}" {{ old('ustadz_id', $jadwal->ustadz_id) == $us->id ? 'selected' : '' }}>
                                            {{ $us->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('ustadz_id')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-gray-100 pt-6">
                        <a href="{{ route('pendidikan.admin.jadwal.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                        <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>