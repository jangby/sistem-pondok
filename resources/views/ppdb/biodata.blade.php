<x-app-layout hide-nav>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Biodata Lengkap') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ tab: 'santri' }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <form action="{{ route('ppdb.biodata.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" x-model="tab">

                {{-- TABS NAVIGATION --}}
                <div class="flex overflow-x-auto space-x-2 bg-white p-2 rounded-xl shadow-sm mb-6 no-scrollbar">
                    <button type="button" @click="tab = 'santri'" 
                        :class="{ 'bg-emerald-600 text-white shadow': tab === 'santri', 'bg-gray-100 text-gray-600 hover:bg-gray-200': tab !== 'santri' }"
                        class="px-4 py-2 rounded-lg font-bold text-sm whitespace-nowrap transition-all flex-1">
                        1. Data Diri
                    </button>
                    <button type="button" @click="tab = 'alamat'" 
                        :class="{ 'bg-emerald-600 text-white shadow': tab === 'alamat', 'bg-gray-100 text-gray-600 hover:bg-gray-200': tab !== 'alamat' }"
                        class="px-4 py-2 rounded-lg font-bold text-sm whitespace-nowrap transition-all flex-1">
                        2. Alamat Domisili
                    </button>
                    <button type="button" @click="tab = 'ortu'" 
                        :class="{ 'bg-emerald-600 text-white shadow': tab === 'ortu', 'bg-gray-100 text-gray-600 hover:bg-gray-200': tab !== 'ortu' }"
                        class="px-4 py-2 rounded-lg font-bold text-sm whitespace-nowrap transition-all flex-1">
                        3. Orang Tua
                    </button>
                    <button type="button" @click="tab = 'berkas'" 
                        :class="{ 'bg-emerald-600 text-white shadow': tab === 'berkas', 'bg-gray-100 text-gray-600 hover:bg-gray-200': tab !== 'berkas' }"
                        class="px-4 py-2 rounded-lg font-bold text-sm whitespace-nowrap transition-all flex-1">
                        4. Berkas
                    </button>
                </div>

                {{-- TAB 1: DATA SANTRI --}}
                <div x-show="tab === 'santri'" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Identitas Utama</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="full_name" value="Nama Lengkap (Sesuai Akta/Ijazah)" />
                            <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name', $calonSantri->full_name)" required />
                        </div>
                        <div>
                            <x-input-label for="nik" value="NIK Santri (16 Digit)" />
                            <x-text-input id="nik" class="block mt-1 w-full" type="number" name="nik" :value="old('nik', $calonSantri->nik)" required />
                        </div>
                        <div>
                            <x-input-label for="no_kk" value="Nomor Kartu Keluarga (KK)" />
                            <x-text-input id="no_kk" class="block mt-1 w-full" type="number" name="no_kk" :value="old('no_kk', $calonSantri->no_kk)" required />
                        </div>
                        <div>
                            <x-input-label for="nisn" value="NISN (Dari Sekolah Asal)" />
                            <x-text-input id="nisn" class="block mt-1 w-full" type="number" name="nisn" :value="old('nisn', $calonSantri->nisn)" />
                        </div>
                        <div>
                            <x-input-label for="tempat_lahir" value="Tempat Lahir" />
                            <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" :value="old('tempat_lahir', $calonSantri->tempat_lahir)" required />
                        </div>
                        <div>
                            <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                            <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir', $calonSantri->tanggal_lahir ? $calonSantri->tanggal_lahir->format('Y-m-d') : '')" required />
                        </div>
                        <div>
                            <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                            <select name="jenis_kelamin" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="L" {{ $calonSantri->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $calonSantri->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="golongan_darah" value="Golongan Darah (Opsional)" />
                            <select name="golongan_darah" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="">- Pilih -</option>
                                <option value="A" {{ $calonSantri->golongan_darah == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ $calonSantri->golongan_darah == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ $calonSantri->golongan_darah == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ $calonSantri->golongan_darah == 'O' ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="riwayat_penyakit" value="Riwayat Penyakit (Jika ada)" />
                            <textarea name="riwayat_penyakit" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="2">{{ old('riwayat_penyakit', $calonSantri->riwayat_penyakit) }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="button" @click="tab = 'alamat'" class="bg-gray-800 text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-700">Lanjut</button>
                    </div>
                </div>

                {{-- TAB 2: ALAMAT RINCI (Sesuai Tabel Santris) --}}
                <div x-show="tab === 'alamat'" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-6" style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Alamat Domisili (Sesuai KK)</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="col-span-2 md:col-span-4">
                            <x-input-label for="alamat" value="Jalan / Nama Dusun / Blok" />
                            <x-text-input id="alamat" class="block mt-1 w-full" type="text" name="alamat" :value="old('alamat', $calonSantri->alamat)" placeholder="Contoh: Jl. Merpati No. 45" required />
                        </div>
                        <div>
                            <x-input-label for="rt" value="RT" />
                            <x-text-input id="rt" class="block mt-1 w-full" type="number" name="rt" :value="old('rt', $calonSantri->rt)" placeholder="001" required />
                        </div>
                        <div>
                            <x-input-label for="rw" value="RW" />
                            <x-text-input id="rw" class="block mt-1 w-full" type="number" name="rw" :value="old('rw', $calonSantri->rw)" placeholder="002" required />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="desa" value="Desa / Kelurahan" />
                            <x-text-input id="desa" class="block mt-1 w-full" type="text" name="desa" :value="old('desa', $calonSantri->desa)" required />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="kecamatan" value="Kecamatan" />
                            <x-text-input id="kecamatan" class="block mt-1 w-full" type="text" name="kecamatan" :value="old('kecamatan', $calonSantri->kecamatan)" required />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="kabupaten" value="Kabupaten / Kota" />
                            <x-text-input id="kabupaten" class="block mt-1 w-full" type="text" name="kabupaten" :value="old('kabupaten', $calonSantri->kabupaten)" required />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="provinsi" value="Provinsi" />
                            <x-text-input id="provinsi" class="block mt-1 w-full" type="text" name="provinsi" :value="old('provinsi', $calonSantri->provinsi)" required />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <x-input-label for="kode_pos" value="Kode Pos" />
                            <x-text-input id="kode_pos" class="block mt-1 w-full" type="number" name="kode_pos" :value="old('kode_pos', $calonSantri->kode_pos)" required />
                        </div>
                    </div>
                    <div class="flex justify-between mt-4">
                        <button type="button" @click="tab = 'santri'" class="text-gray-600 font-bold">Kembali</button>
                        <button type="button" @click="tab = 'ortu'" class="bg-gray-800 text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-700">Lanjut</button>
                    </div>
                </div>

                {{-- TAB 3: ORANG TUA (Data Detail) --}}
                <div x-show="tab === 'ortu'" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-6" style="display: none;">
                    
                    {{-- AYAH --}}
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <h4 class="font-bold text-blue-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Data Ayah
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Nama Ayah" />
                                <x-text-input name="nama_ayah" class="w-full mt-1" :value="old('nama_ayah', $calonSantri->nama_ayah)" required />
                            </div>
                            <div>
                                <x-input-label value="NIK Ayah" />
                                <x-text-input name="nik_ayah" type="number" class="w-full mt-1" :value="old('nik_ayah', $calonSantri->nik_ayah)" />
                            </div>
                            <div>
                                <x-input-label value="Tahun Lahir" />
                                <x-text-input name="thn_lahir_ayah" type="number" class="w-full mt-1" :value="old('thn_lahir_ayah', $calonSantri->thn_lahir_ayah)" placeholder="YYYY" />
                            </div>
                            <div>
                                <x-input-label value="Pekerjaan" />
                                <x-text-input name="pekerjaan_ayah" class="w-full mt-1" :value="old('pekerjaan_ayah', $calonSantri->pekerjaan_ayah)" />
                            </div>
                            <div>
                                <x-input-label value="Penghasilan / Bulan" />
                                <select name="penghasilan_ayah" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                    <option value="">- Pilih -</option>
                                    <option value="< 1 Jt" {{ $calonSantri->penghasilan_ayah == '< 1 Jt' ? 'selected' : '' }}>< 1 Juta</option>
                                    <option value="1-3 Jt" {{ $calonSantri->penghasilan_ayah == '1-3 Jt' ? 'selected' : '' }}>1 - 3 Juta</option>
                                    <option value="3-5 Jt" {{ $calonSantri->penghasilan_ayah == '3-5 Jt' ? 'selected' : '' }}>3 - 5 Juta</option>
                                    <option value="> 5 Jt" {{ $calonSantri->penghasilan_ayah == '> 5 Jt' ? 'selected' : '' }}>> 5 Juta</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label value="No. WhatsApp (Aktif)" />
                                <x-text-input name="no_hp_ayah" class="w-full mt-1" :value="old('no_hp_ayah', $calonSantri->no_hp_ayah)" required />
                            </div>
                        </div>
                    </div>

                    {{-- IBU --}}
                    <div class="p-4 bg-pink-50 rounded-xl border border-pink-100">
                        <h4 class="font-bold text-pink-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Data Ibu
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Nama Ibu" />
                                <x-text-input name="nama_ibu" class="w-full mt-1" :value="old('nama_ibu', $calonSantri->nama_ibu)" required />
                            </div>
                            <div>
                                <x-input-label value="NIK Ibu" />
                                <x-text-input name="nik_ibu" type="number" class="w-full mt-1" :value="old('nik_ibu', $calonSantri->nik_ibu)" />
                            </div>
                            <div>
                                <x-input-label value="Tahun Lahir" />
                                <x-text-input name="thn_lahir_ibu" type="number" class="w-full mt-1" :value="old('thn_lahir_ibu', $calonSantri->thn_lahir_ibu)" placeholder="YYYY" />
                            </div>
                            <div>
                                <x-input-label value="Pekerjaan" />
                                <x-text-input name="pekerjaan_ibu" class="w-full mt-1" :value="old('pekerjaan_ibu', $calonSantri->pekerjaan_ibu)" />
                            </div>
                            <div>
                                <x-input-label value="No. WhatsApp" />
                                <x-text-input name="no_hp_ibu" class="w-full mt-1" :value="old('no_hp_ibu', $calonSantri->no_hp_ibu)" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4">
                        <button type="button" @click="tab = 'alamat'" class="text-gray-600 font-bold">Kembali</button>
                        <button type="button" @click="tab = 'berkas'" class="bg-gray-800 text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-700">Lanjut</button>
                    </div>
                </div>

                {{-- TAB 4: BERKAS --}}
                <div x-show="tab === 'berkas'" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-6" style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Upload Dokumen (Wajib PDF)</h3>
                    
                    {{-- Alert Info --}}
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Pastikan dokumen yang diupload jelas dan terbaca. 
                                    <br>Format wajib <strong>PDF</strong> (kecuali Foto Santri). Maksimal <strong>2MB</strong> per file.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition">
                            <x-input-label value="Pas Foto Santri (Format: JPG/PNG)" class="mb-2" />
                            <input type="file" name="foto_santri" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            @if($calonSantri->foto_santri) 
                                <div class="mt-2 flex items-center text-green-600 text-xs font-bold">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Sudah Terupload
                                </div>
                            @endif
                        </div>

                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition">
                            <x-input-label value="Kartu Keluarga (KK) - PDF" class="mb-2" />
                            <input type="file" name="file_kk" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @if($calonSantri->file_kk) <p class="text-xs text-green-600 mt-2 font-bold">✓ Sudah Terupload</p> @endif
                        </div>

                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition">
                            <x-input-label value="Akta Kelahiran - PDF" class="mb-2" />
                            <input type="file" name="file_akta" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @if($calonSantri->file_akta) <p class="text-xs text-green-600 mt-2 font-bold">✓ Sudah Terupload</p> @endif
                        </div>

                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition">
                            <x-input-label value="Ijazah Terakhir - PDF" class="mb-2" />
                            <input type="file" name="file_ijazah" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @if($calonSantri->file_ijazah) <p class="text-xs text-green-600 mt-2 font-bold">✓ Sudah Terupload</p> @endif
                        </div>

                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition">
                            <x-input-label value="Surat Keterangan Lulus (SKL) - PDF" class="mb-2" />
                            <input type="file" name="file_skl" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @if($calonSantri->file_skl) <p class="text-xs text-green-600 mt-2 font-bold">✓ Sudah Terupload</p> @endif
                        </div>

                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition">
                            <x-input-label value="KTP Ayah - PDF" class="mb-2" />
                            <input type="file" name="file_ktp_ayah" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @if($calonSantri->file_ktp_ayah) <p class="text-xs text-green-600 mt-2 font-bold">✓ Sudah Terupload</p> @endif
                        </div>

                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition">
                            <x-input-label value="KTP Ibu - PDF" class="mb-2" />
                            <input type="file" name="file_ktp_ibu" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @if($calonSantri->file_ktp_ibu) <p class="text-xs text-green-600 mt-2 font-bold">✓ Sudah Terupload</p> @endif
                        </div>

                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition">
                            <x-input-label value="Kartu KIP / KIS (Opsional) - PDF" class="mb-2" />
                            <input type="file" name="file_kip" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @if($calonSantri->file_kip) <p class="text-xs text-green-600 mt-2 font-bold">✓ Sudah Terupload</p> @endif
                        </div>

                    </div>

                    <div class="flex justify-between mt-6 border-t pt-4">
                        <button type="button" @click="tab = 'ortu'" class="text-gray-600 font-bold hover:text-gray-800">Kembali</button>
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:bg-emerald-500 transition transform hover:-translate-y-0.5">
                            SIMPAN & UPLOAD
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>