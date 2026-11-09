<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Jadwal Ujian</h2>
            <a href="{{ route('pendidikan.admin.ujian.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-8">
                <form action="{{ route('pendidikan.admin.ujian.update', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Ujian</label>
                            <select name="jenis_ujian" class="w-full rounded-lg border-gray-300 text-sm" required>
                                @foreach(['uts' => 'UTS (Tengah Semester)', 'uas' => 'UAS (Akhir Semester)'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('jenis_ujian', $jadwal->jenis_ujian) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('jenis_ujian')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Semester</label>
                            <select name="semester" class="w-full rounded-lg border-gray-300 text-sm" required>
                                @foreach(['ganjil' => 'Ganjil', 'genap' => 'Genap'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('semester', $jadwal->semester) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('semester')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $jadwal->tahun_ajaran) }}" class="w-full rounded-lg border-gray-300 text-sm" required maxlength="20">
                            <x-input-error :messages="$errors->get('tahun_ajaran')" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="mustawa_id" value="Kelas (Mustawa)" />
                                <select id="mustawa_id" name="mustawa_id" class="w-full rounded-lg border-gray-300 text-sm" required>
                                    @foreach($mustawas as $mustawa)
                                        <option value="{{ $mustawa->id }}" @selected((string) old('mustawa_id', $jadwal->mustawa_id) === (string) $mustawa->id)>{{ $mustawa->nama }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('mustawa_id')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="mapel_diniyah_id" value="Mata Ujian (Kitab)" />
                                <select id="mapel_diniyah_id" name="mapel_diniyah_id" class="w-full rounded-lg border-gray-300 text-sm" required>
                                    @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}" @selected((string) old('mapel_diniyah_id', $jadwal->mapel_diniyah_id) === (string) $mapel->id)>{{ $mapel->nama_mapel }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('mapel_diniyah_id')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label value="Kategori Tes" />
                                <div class="flex flex-wrap gap-4 mt-2">
                                    @foreach(['tulis' => 'Tulis', 'lisan' => 'Lisan', 'praktek' => 'Praktek', 'hafalan' => 'Hafalan'] as $value => $label)
                                        <label class="flex items-center text-sm">
                                            <input type="radio" name="kategori_tes" value="{{ $value }}" @checked(old('kategori_tes', $jadwal->kategori_tes) === $value) class="mr-2 text-emerald-600">
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('kategori_tes')" class="mt-1" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="tanggal" value="Tanggal Ujian" />
                                <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', $jadwal->tanggal->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 text-sm" required>
                                <x-input-error :messages="$errors->get('tanggal')" class="mt-1" />
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <x-input-label for="jam_mulai" value="Jam Mulai" />
                                    <input id="jam_mulai" type="time" name="jam_mulai" value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}" class="w-full rounded-lg border-gray-300 text-sm" required>
                                    <x-input-error :messages="$errors->get('jam_mulai')" class="mt-1" />
                                </div>
                                <div>
                                    <x-input-label for="jam_selesai" value="Jam Selesai" />
                                    <input id="jam_selesai" type="time" name="jam_selesai" value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}" class="w-full rounded-lg border-gray-300 text-sm" required>
                                    <x-input-error :messages="$errors->get('jam_selesai')" class="mt-1" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="pengawas_id" value="Pengawas / Penguji" />
                                <select id="pengawas_id" name="pengawas_id" class="w-full rounded-lg border-gray-300 text-sm" required>
                                    @foreach($ustadzs as $ustadz)
                                        <option value="{{ $ustadz->id }}" @selected((string) old('pengawas_id', $jadwal->pengawas_id) === (string) $ustadz->id)>{{ $ustadz->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('pengawas_id')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="ruangan" value="Ruangan (opsional)" />
                                <input id="ruangan" type="text" name="ruangan" value="{{ old('ruangan', $jadwal->ruangan) }}" maxlength="255" class="w-full rounded-lg border-gray-300 text-sm">
                                <x-input-error :messages="$errors->get('ruangan')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6 mt-6 border-t border-gray-100">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
