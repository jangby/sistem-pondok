<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-white pb-20">
        
        {{-- Header Sederhana --}}
        <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-30">
            <a href="{{ route('pengurus.santri.index') }}" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Tambah Santri</h1>
        </div>

        <form action="{{ route('pengurus.santri.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- SECTION 1: DATA UTAMA --}}
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIS</label>
                        <input type="text" name="nis" value="{{ old('nis') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                        <x-input-error :messages="$errors->get('nis')" />
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RFID UID (Tap Kartu)</label>
                        <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 bg-gray-50" placeholder="Tap Kartu Disini...">
                        <x-input-error :messages="$errors->get('rfid_uid')" />
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                </div>
            </div>

            {{-- SECTION 2: DATA AKADEMIK --}}
            <div class="grid grid-cols-3 gap-4"> {{-- Ubah grid-cols-2 jadi grid-cols-3 --}}
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kelas</label>
                    <select name="kelas_id" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih -</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="YYYY">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="active">Aktif</option>
                        <option value="graduated">Lulus</option>
                        <option value="moved">Pindah</option>
                    </select>
                </div>
            </div>

            {{-- SECTION 3: WALI --}}
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Akun Orang Tua (Penautan)</label>
                <select name="orang_tua_id" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                    <option value="">- Pilih Akun Wali -</option>
                    @foreach($orangTuas as $ortu)
                        <option value="{{ $ortu->id }}">{{ $ortu->name }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-400 mt-1">Akun ini digunakan untuk login aplikasi wali santri.</p>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- SECTION 4: DATA PRIBADI DETAIL --}}
            <h3 class="font-bold text-gray-800">Data Pribadi</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Gol. Darah</label>
                    <select name="golongan_darah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">-</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Riwayat Penyakit</label>
                <textarea name="riwayat_penyakit" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="Contoh: Asma, Alergi Udang"></textarea>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- SECTION 5: ALAMAT & DOMISILI (BARU) --}}
            <h3 class="font-bold text-gray-800">Alamat & Domisili</h3>
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="Nama Jalan, Gg, No Rumah">{{ old('alamat') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RT</label>
                        <input type="text" name="rt" value="{{ old('rt') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RW</label>
                        <input type="text" name="rw" value="{{ old('rw') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kelurahan/Desa</label>
                    <input type="text" name="desa" value="{{ old('desa') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- SECTION 6: DATA AYAH (EMIS) (BARU) --}}
            <h3 class="font-bold text-gray-800">Data Ayah (EMIS)</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Ayah</label>
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIK Ayah</label>
                        <input type="number" name="nik_ayah" value="{{ old('nik_ayah') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tahun Lahir</label>
                        <input type="number" name="thn_lahir_ayah" placeholder="Contoh: 1980" value="{{ old('thn_lahir_ayah') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pendidikan</label>
                        <select name="pendidikan_ayah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                            <option value="">- Pilih -</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pekerjaan</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Penghasilan / Bulan</label>
                    <select name="penghasilan_ayah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih Range -</option>
                        <option value="< 1 Juta">< 1.000.000</option>
                        <option value="1 - 3 Juta">1.000.000 - 3.000.000</option>
                        <option value="3 - 5 Juta">3.000.000 - 5.000.000</option>
                        <option value="> 5 Juta">> 5.000.000</option>
                    </select>
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- SECTION 7: DATA IBU (EMIS) (BARU) --}}
            <h3 class="font-bold text-gray-800">Data Ibu (EMIS)</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Ibu</label>
                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIK Ibu</label>
                        <input type="number" name="nik_ibu" value="{{ old('nik_ibu') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tahun Lahir</label>
                        <input type="number" name="thn_lahir_ibu" placeholder="Contoh: 1985" value="{{ old('thn_lahir_ibu') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pendidikan</label>
                        <select name="pendidikan_ibu" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                            <option value="">- Pilih -</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pekerjaan</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Penghasilan / Bulan</label>
                    <select name="penghasilan_ibu" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih Range -</option>
                        <option value="< 1 Juta">< 1.000.000</option>
                        <option value="1 - 3 Juta">1.000.000 - 3.000.000</option>
                        <option value="3 - 5 Juta">3.000.000 - 5.000.000</option>
                        <option value="> 5 Juta">> 5.000.000</option>
                    </select>
                </div>
            </div>

            {{-- TOMBOL SIMPAN --}}
            <div class="pt-4">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 active:scale-95 transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>