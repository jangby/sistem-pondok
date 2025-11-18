<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('adminpondok.santris.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Tambah Santri Baru</h2>
                    <p class="text-sm text-gray-500">Lengkapi data diri santri di bawah ini.</p>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('adminpondok.santris.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI: Data Pribadi --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-emerald-700 border-b border-emerald-100 pb-2">Data Pribadi</h3>
                                
                                <div>
                                    <x-input-label for="nis" :value="__('Nomor Induk Santri (NIS)')" />
                                    <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis" :value="old('nis')" required autofocus placeholder="Contoh: 2024001" />
                                    <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="full_name" :value="__('Nama Lengkap')" />
                                    <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name')" required placeholder="Sesuai Akta Kelahiran" />
                                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                    <div class="mt-2 flex gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-emerald-600 focus:ring-emerald-500 border-gray-300" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }} required>
                                            <span class="ml-2">Laki-laki</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-emerald-600 focus:ring-emerald-500 border-gray-300" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}>
                                            <span class="ml-2">Perempuan</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Data Akademik & Wali --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-emerald-700 border-b border-emerald-100 pb-2">Data Akademik & Wali</h3>

                                <div>
                                    <x-input-label for="kelas_id" :value="__('Kelas')" />
                                    <select name="kelas_id" id="kelas_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($daftarKelas as $kelas)
                                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->nama_kelas }} ({{ $kelas->tingkat }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="orang_tua_id" :value="__('Orang Tua / Wali')" />
                                    <select name="orang_tua_id" id="orang_tua_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                        <option value="">-- Pilih Orang Tua --</option>
                                        @foreach ($orangTuas as $ortu)
                                            <option value="{{ $ortu->id }}" {{ old('orang_tua_id') == $ortu->id ? 'selected' : '' }}>
                                                {{ $ortu->name }} (Telp: {{ $ortu->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Belum ada data wali? <a href="{{ route('adminpondok.orang-tuas.create') }}" class="text-emerald-600 hover:underline">Tambah Wali Baru</a></p>
                                    <x-input-error :messages="$errors->get('orang_tua_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="status" :value="__('Status Santri')" />
                                    <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Lulus</option>
                                        <option value="moved" {{ old('status') == 'moved' ? 'selected' : '' }}>Pindah / Keluar</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('adminpondok.santris.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-2.5">
                                {{ __('Simpan Data Santri') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>