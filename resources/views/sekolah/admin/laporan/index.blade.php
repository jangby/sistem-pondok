<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- CARD 1: LAPORAN GURU --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold text-indigo-600 mb-4 border-b pb-2">Laporan Absensi Guru</h3>
                        
                        <form action="{{ route('sekolah.admin.laporan.cetak') }}" method="POST" target="_blank">
                            @csrf
                            <div class="mb-4">
                                <x-input-label :value="__('Jenis Ledger')" />
                                <select name="jenis_laporan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="guru_sekolah">1. Kehadiran Sekolah (Masuk/Pulang)</option>
                                    <option value="guru_pelajaran">2. Kehadiran Mengajar (Per Jam)</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-input-label :value="__('Bulan')" />
                                    <select name="bulan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}" @selected($i == now()->month)>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <x-input-label :value="__('Tahun')" />
                                    <select name="tahun" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        @for($y=now()->year; $y>=2023; $y--)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak PDF
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- CARD 2: LAPORAN SISWA --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold text-teal-600 mb-4 border-b pb-2">Laporan Absensi Siswa</h3>
                        
                        <form action="{{ route('sekolah.admin.laporan.cetak') }}" method="POST" target="_blank" x-data="{ jenis: 'siswa_sekolah' }">
                            @csrf
                            <div class="mb-4">
                                <x-input-label :value="__('Jenis Ledger')" />
                                <select name="jenis_laporan" x-model="jenis" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                                    <option value="siswa_sekolah">1. Kehadiran Sekolah (Masuk/Pulang)</option>
                                    <option value="siswa_pelajaran">2. Kehadiran Pelajaran (Per Mapel)</option>
                                </select>
                            </div>

                            <div class="mb-4 p-3 bg-gray-50 rounded-md border border-gray-200">
                                {{-- Untuk Siswa Sekolah (Gerbang) --}}
                                <div x-show="jenis == 'siswa_sekolah'">
                                    <x-input-label :value="__('Filter Kelas (Opsional)')" />
                                    <select name="kelas_id_sekolah" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelasList as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Untuk Siswa Pelajaran (Wajib Kelas) --}}
                                <div x-show="jenis == 'siswa_pelajaran'">
                                    <div class="mb-3">
                                        <x-input-label :value="__('Pilih Kelas (Wajib)')" />
                                        <select name="kelas_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            <option value="">-- Pilih --</option>
                                            @foreach($kelasList as $kelas)
                                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label :value="__('Filter Mata Pelajaran (Opsional)')" />
                                        <select name="mapel_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            <option value="">Semua Mapel</option>
                                            @foreach($mapelList as $mapel)
                                                <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-input-label :value="__('Bulan')" />
                                    <select name="bulan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}" @selected($i == now()->month)>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <x-input-label :value="__('Tahun')" />
                                    <select name="tahun" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        @for($y=now()->year; $y>=2023; $y--)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button class="bg-teal-600 hover:bg-teal-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak PDF
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>