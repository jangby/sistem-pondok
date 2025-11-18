<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfigurasi Verifikasi Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Pengaturan Jam & Hari Kerja
                    </h3>
                    
                    <form method="POST" action="{{ route('sekolah.admin.konfigurasi.settings.store') }}">
                        @csrf
                        @php
                            $settings = $absensiSettings; // Ambil dari controller
                            $hariKerja = $settings->hari_kerja ?? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; // Default
                        @endphp
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <x-input-label for="jam_masuk" :value="__('Jam Masuk')" />
                                <x-text-input id="jam_masuk" class="block mt-1 w-full" type="time" name="jam_masuk" :value="old('jam_masuk', $settings->jam_masuk ?? '06:30')" required />
                                {{-- TAMBAHKAN INI --}}
                                <x-input-error :messages="$errors->get('jam_masuk')" class="mt-2" /> 
                            </div>
                            <div>
                                <x-input-label for="batas_telat" :value="__('Batas Telat')" />
                                <x-text-input id="batas_telat" class="block mt-1 w-full" type="time" name="batas_telat" :value="old('batas_telat', $settings->batas_telat ?? '07:00')" required />
                                {{-- TAMBAHKAN INI --}}
                                <x-input-error :messages="$errors->get('batas_telat')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="jam_pulang_awal" :value="__('Jam Pulang Awal')" />
                                <x-text-input id="jam_pulang_awal" class="block mt-1 w-full" type="time" name="jam_pulang_awal" :value="old('jam_pulang_awal', $settings->jam_pulang_awal ?? '14:00')" required />
                                {{-- TAMBAHKAN INI --}}
                                <x-input-error :messages="$errors->get('jam_pulang_awal')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="jam_pulang_akhir" :value="__('Batas Akhir Pulang')" />
                                <x-text-input id="jam_pulang_akhir" class="block mt-1 w-full" type="time" name="jam_pulang_akhir" :value="old('jam_pulang_akhir', $settings->jam_pulang_akhir ?? '17:00')" required />
                                {{-- TAMBAHKAN INI --}}
                                <x-input-error :messages="$errors->get('jam_pulang_akhir')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <x-input-label :value="__('Pilih Hari Kerja (Hari Libur Mingguan)')" />
                            <div class="grid grid-cols-4 md:grid-cols-7 gap-4 mt-2">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <label class="flex items-center">
                                    <input type="checkbox" name="hari_kerja[]" value="{{ $hari }}"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           @checked(in_array($hari, $hariKerja))>
                                    <span class="ms-2 text-sm text-gray-600">{{ $hari }}</span>
                                </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('hari_kerja')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan Pengaturan Jam & Hari') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-blue-800">Buka Kios Kode Absensi</h4>
                                <p class="text-sm text-blue-700 mt-1">Buka halaman ini di tablet atau monitor di lobi sekolah.</p>
                            </div>
                            <a href="{{ route('sekolah.admin.konfigurasi.kios.show') }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                Buka Kios
                            </a>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Manajemen Hari Libur Nasional
                        </h3>
                        <form method="POST" action="{{ route('sekolah.admin.konfigurasi.hari-libur.store') }}" class="mb-6 p-4 border rounded-md">
                            @csrf
                            <div>
                                <x-input-label for="tanggal" :value="__('Tanggal Libur')" />
                                <x-text-input id="tanggal" class="block mt-1 w-full" type="date" name="tanggal" :value="old('tanggal')" required />
                                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                            </div>
                            <div class="mt-4">
                                <x-input-label for="keterangan" :value="__('Keterangan')" />
                                <x-text-input id="keterangan" class="block mt-1 w-full" type="text" name="keterangan" :value="old('keterangan')" required placeholder="Cth: Hari Kemerdekaan RI" />
                                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('+ Tambah Libur') }}
                                </x-primary-button>
                            </div>
                        </form>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($hariLiburList as $libur)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($libur->tanggal)->format('d M Y') }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $libur->keterangan }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <form action="{{ route('sekolah.admin.konfigurasi.hari-libur.destroy', $libur->id) }}" method="POST" onsubmit="return confirm('Yakin hapus hari libur ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500">Belum ada hari libur.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Jaringan WiFi Terdaftar
                        </h3>
                        <form method="POST" action="{{ route('sekolah.admin.konfigurasi.wifi.store') }}" class="mb-6 p-4 border rounded-md">
                            @csrf
                            <div>
                                <x-input-label for="nama_wifi_ssid" :value="__('Nama WiFi (SSID)')" />
                                <x-text-input id="nama_wifi_ssid" class="block mt-1 w-full" type="text" name="nama_wifi_ssid" :value="old('nama_wifi_ssid')" required />
                                <x-input-error :messages="$errors->get('nama_wifi_ssid')" class="mt-2" />
                            </div>
                            <div class="mt-4">
                                <x-input-label for="bssid" :value="__('BSSID (Opsional)')" />
                                <x-text-input id="bssid" class="block mt-1 w-full" type="text" name="bssid" :value="old('bssid')" placeholder="Cth: 00:1A:2B:3C:4D:5E" />
                                <x-input-error :messages="$errors->get('bssid')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('+ Tambah WiFi') }}
                                </x-primary-button>
                            </div>
                        </form>
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- ... (Tabel WiFi dari langkah sebelumnya ... --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">SSID</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($wifiList as $wifi)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ $wifi->nama_wifi_ssid }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <form action="{{ route('sekolah.admin.konfigurasi.wifi.destroy', $wifi->id) }}" method="POST" onsubmit="return confirm('Yakin hapus WiFi ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="px-4 py-2 text-center text-sm text-gray-500">Belum ada WiFi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Lokasi Geofence Terdaftar
                        </h3>
                        <form method="POST" action="{{ route('sekolah.admin.konfigurasi.geofence.store') }}" class="mb-6 p-4 border rounded-md">
                            @csrf
                            <div>
                                <x-input-label for="nama_lokasi" :value="__('Nama Lokasi')" />
                                <x-text-input id="nama_lokasi" class="block mt-1 w-full" type="text" name="nama_lokasi" :value="old('nama_lokasi')" required placeholder="Cth: Gedung MTS" />
                                {{-- TAMBAHKAN INI --}}
                                <x-input-error :messages="$errors->get('nama_lokasi')" class="mt-2" />
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="latitude" :value="__('Latitude')" />
                                    <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude')" required placeholder="-6.9175" />
                                    {{-- TAMBAHKAN INI --}}
                                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="longitude" :value="__('Longitude')" />
                                    <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude')" required placeholder="107.6191" />
                                    {{-- TAMBAHKAN INI --}}
                                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-input-label for="radius" :value="__('Radius (dalam meter)')" />
                                <x-text-input id="radius" class="block mt-1 w-full" type="number" name="radius" :value="old('radius', 50)" required />
                                {{-- TAMBAHKAN INI --}}
                                <x-input-error :messages="$errors->get('radius')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('+ Tambah Lokasi') }}
                                </x-primary-button>
                            </div>
                        </form>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($geofenceList as $lokasi)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ $lokasi->nama_lokasi }} ({{ $lokasi->radius }}m)</td>
                                    <td class="px-4 py-2 text-right">
                                        <form action="{{ route('sekolah.admin.konfigurasi.geofence.destroy', $lokasi->id) }}" method="POST" onsubmit="return confirm('Yakin hapus lokasi ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="px-4 py-2 text-center text-sm text-gray-500">Belum ada lokasi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
</x-app-layout>