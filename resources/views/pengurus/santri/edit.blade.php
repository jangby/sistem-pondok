<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-white pb-20">
        
        {{-- Header Sederhana --}}
        <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-30">
            <a href="{{ route('pengurus.santri.index') }}" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Edit Data Santri</h1>
        </div>

        <form action="{{ route('pengurus.santri.update', $santri->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- SECTION 1: DATA UTAMA --}}
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIS</label>
                        <input type="text" name="nis" value="{{ old('nis', $santri->nis) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                        <x-input-error :messages="$errors->get('nis')" />
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RFID UID</label>
                        <input type="text" name="rfid_uid" value="{{ old('rfid_uid', $santri->rfid_uid) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 bg-gray-50">
                        <x-input-error :messages="$errors->get('rfid_uid')" />
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $santri->full_name) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                </div>
            </div>

            {{-- SECTION 2: DATA AKADEMIK --}}
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kelas</label>
                    <select name="kelas_id" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih -</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id', $santri->kelas_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" value="{{ old('tahun_masuk', $santri->tahun_masuk) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="YYYY">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="active" {{ old('status', $santri->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="graduated" {{ old('status', $santri->status) == 'graduated' ? 'selected' : '' }}>Lulus</option>
                        <option value="moved" {{ old('status', $santri->status) == 'moved' ? 'selected' : '' }}>Pindah</option>
                    </select>
                </div>
            </div>

            {{-- SECTION 3: WALI --}}
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Akun Orang Tua</label>
                <select name="orang_tua_id" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                    <option value="">- Pilih Wali -</option>
                    @foreach($orangTuas as $ortu)
                        <option value="{{ $ortu->id }}" {{ old('orang_tua_id', $santri->orang_tua_id) == $ortu->id ? 'selected' : '' }}>
                            {{ $ortu->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- SECTION 4: DATA PRIBADI --}}
            <h3 class="font-bold text-gray-800">Data Pribadi</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $santri->tempat_lahir) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $santri->tanggal_lahir?->format('Y-m-d')) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="Laki-laki" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Gol. Darah</label>
                    <select name="golongan_darah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">-</option>
                        <option value="A" {{ old('golongan_darah', $santri->golongan_darah) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('golongan_darah', $santri->golongan_darah) == 'B' ? 'selected' : '' }}>B</option>
                        <option value="AB" {{ old('golongan_darah', $santri->golongan_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                        <option value="O" {{ old('golongan_darah', $santri->golongan_darah) == 'O' ? 'selected' : '' }}>O</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Riwayat Penyakit</label>
                <textarea name="riwayat_penyakit" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">{{ old('riwayat_penyakit', $santri->riwayat_penyakit) }}</textarea>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- SECTION 5: ALAMAT & DOMISILI --}}
            <h3 class="font-bold text-gray-800">Alamat & Domisili</h3>
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">{{ old('alamat', $santri->alamat) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RT</label>
                        <input type="text" name="rt" value="{{ old('rt', $santri->rt) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RW</label>
                        <input type="text" name="rw" value="{{ old('rw', $santri->rw) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ old('kode_pos', $santri->kode_pos) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kelurahan/Desa</label>
                    <input type="text" name="desa" value="{{ old('desa', $santri->desa) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $santri->kecamatan) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- SECTION 6: DATA AYAH --}}
            <h3 class="font-bold text-gray-800">Data Ayah (EMIS)</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Ayah</label>
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $santri->nama_ayah) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIK Ayah</label>
                        <input type="number" name="nik_ayah" value="{{ old('nik_ayah', $santri->nik_ayah) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tahun Lahir</label>
                        <input type="number" name="thn_lahir_ayah" value="{{ old('thn_lahir_ayah', $santri->thn_lahir_ayah) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pendidikan</label>
                        <select name="pendidikan_ayah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                            <option value="">- Pilih -</option>
                            @foreach(['SD','SMP','SMA','D3','S1','S2','S3','Tidak Sekolah'] as $edu)
                                <option value="{{ $edu }}" {{ old('pendidikan_ayah', $santri->pendidikan_ayah) == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pekerjaan</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $santri->pekerjaan_ayah) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Penghasilan / Bulan</label>
                    <select name="penghasilan_ayah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih Range -</option>
                        @foreach(['< 1 Juta', '1 - 3 Juta', '3 - 5 Juta', '> 5 Juta'] as $income)
                            <option value="{{ $income }}" {{ old('penghasilan_ayah', $santri->penghasilan_ayah) == $income ? 'selected' : '' }}>{{ $income }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            {{-- SECTION 7: DATA IBU --}}
            <h3 class="font-bold text-gray-800">Data Ibu (EMIS)</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Ibu</label>
                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $santri->nama_ibu) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIK Ibu</label>
                        <input type="number" name="nik_ibu" value="{{ old('nik_ibu', $santri->nik_ibu) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tahun Lahir</label>
                        <input type="number" name="thn_lahir_ibu" value="{{ old('thn_lahir_ibu', $santri->thn_lahir_ibu) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pendidikan</label>
                        <select name="pendidikan_ibu" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                            <option value="">- Pilih -</option>
                            @foreach(['SD','SMP','SMA','D3','S1','S2','S3','Tidak Sekolah'] as $edu)
                                <option value="{{ $edu }}" {{ old('pendidikan_ibu', $santri->pendidikan_ibu) == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pekerjaan</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $santri->pekerjaan_ibu) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Penghasilan / Bulan</label>
                    <select name="penghasilan_ibu" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih Range -</option>
                        @foreach(['< 1 Juta', '1 - 3 Juta', '3 - 5 Juta', '> 5 Juta'] as $income)
                            <option value="{{ $income }}" {{ old('penghasilan_ibu', $santri->penghasilan_ibu) == $income ? 'selected' : '' }}>{{ $income }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- TOMBOL UPDATE --}}
            <div class="pt-4">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 active:scale-95 transition">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>