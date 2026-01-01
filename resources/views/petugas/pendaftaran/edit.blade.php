<x-app-layout>
    <x-slot name="navigation">
        @include('layouts.petugas-nav')
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tampilkan Error Validation --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded shadow-sm">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Terjadi Kesalahan Update:
                    </p>
                    <ul class="list-disc ml-8 text-sm mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight">Edit Data Pendaftar</h2>
                        <p class="text-sm text-blue-100 mt-1">
                            {{ $calonSantri->nama_lengkap }} ({{ $calonSantri->no_pendaftaran }})
                        </p>
                    </div>
                </div>

                <form action="{{ route('petugas.pendaftaran.update', $calonSantri->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        {{-- KOLOM KIRI --}}
                        <div class="space-y-8">
                            
                            {{-- 1. IDENTITAS --}}
                            <div>
                                <h3 class="text-blue-700 font-bold border-b-2 border-blue-100 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                                    Identitas Santri
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('nama_lengkap', $calonSantri->full_name) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">NIK (16 Digit)</label>
                                        <input type="number" name="nik" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('nik', $calonSantri->nik) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Nomor KK</label>
                                        <input type="number" name="no_kk" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('no_kk', $calonSantri->no_kk) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">NISN</label>
                                        <input type="number" name="nisn" class="w-full rounded-lg border-gray-300 mt-1" value="{{ old('nisn', $calonSantri->nisn) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="w-full rounded-lg border-gray-300 mt-1" required>
                                            <option value="L" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('tempat_lahir', $calonSantri->tempat_lahir) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('tanggal_lahir', $calonSantri->tanggal_lahir ? $calonSantri->tanggal_lahir->format('Y-m-d') : '') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Anak Ke-</label>
                                        <input type="number" name="anak_ke" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('anak_ke', $calonSantri->anak_ke) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Jml Saudara</label>
                                        <input type="number" name="jumlah_saudara" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('jumlah_saudara', $calonSantri->jumlah_saudara) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 2. ALAMAT --}}
                            <div>
                                <h3 class="text-blue-700 font-bold border-b-2 border-blue-100 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                    Alamat Domisili
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700">Alamat Jalan / Blok</label>
                                        <textarea name="alamat" rows="2" class="w-full rounded-lg border-gray-300 mt-1" required>{{ old('alamat', $calonSantri->alamat) }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">RT</label>
                                        <input type="number" name="rt" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('rt', $calonSantri->rt) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">RW</label>
                                        <input type="number" name="rw" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('rw', $calonSantri->rw) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Desa/Kelurahan</label>
                                        <input type="text" name="desa" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('desa', $calonSantri->desa) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Kecamatan</label>
                                        <input type="text" name="kecamatan" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('kecamatan', $calonSantri->kecamatan) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Kabupaten/Kota</label>
                                        <input type="text" name="kabupaten" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('kabupaten', $calonSantri->kabupaten) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Provinsi</label>
                                        <input type="text" name="provinsi" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('provinsi', $calonSantri->provinsi) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Kode Pos</label>
                                        <input type="number" name="kode_pos" class="w-full rounded-lg border-gray-300 mt-1" value="{{ old('kode_pos', $calonSantri->kode_pos) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="space-y-8">
                            
                            {{-- 3. AKADEMIK --}}
                            <div>
                                <h3 class="text-blue-700 font-bold border-b-2 border-blue-100 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                                    Data Akademik
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Asal Sekolah</label>
                                        <input type="text" name="sekolah_asal" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('sekolah_asal', $calonSantri->sekolah_asal) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Daftar ke Jenjang</label>
                                        <select name="jenjang" class="w-full rounded-lg border-gray-300 mt-1" required>
                                            <option value="SMP" {{ old('jenjang', $calonSantri->jenjang) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                            <option value="SMA" {{ old('jenjang', $calonSantri->jenjang) == 'SMA' ? 'selected' : '' }}>SMA</option>
                                            <option value="TAKHOSUS" {{ old('jenjang', $calonSantri->jenjang) == 'TAKHOSUS' ? 'selected' : '' }}>TAKHOSUS</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. ORANG TUA --}}
                            <div>
                                <h3 class="text-blue-700 font-bold border-b-2 border-blue-100 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">4</span>
                                    Data Orang Tua
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2 md:col-span-1">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">Nama Ayah</label>
                                        <input type="text" name="nama_ayah" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('nama_ayah', $calonSantri->nama_ayah) }}">
                                    </div>
                                    <div class="col-span-2 md:col-span-1">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">NIK Ayah</label>
                                        <input type="number" name="nik_ayah" class="w-full rounded-lg border-gray-300 mt-1" value="{{ old('nik_ayah', $calonSantri->nik_ayah) }}">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">Pekerjaan Ayah</label>
                                        <input type="text" name="pekerjaan_ayah" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('pekerjaan_ayah', $calonSantri->pekerjaan_ayah) }}">
                                    </div>

                                    <div class="col-span-2 md:col-span-1 mt-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">Nama Ibu</label>
                                        <input type="text" name="nama_ibu" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('nama_ibu', $calonSantri->nama_ibu) }}">
                                    </div>
                                    <div class="col-span-2 md:col-span-1 mt-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">NIK Ibu</label>
                                        <input type="number" name="nik_ibu" class="w-full rounded-lg border-gray-300 mt-1" value="{{ old('nik_ibu', $calonSantri->nik_ibu) }}">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">Pekerjaan Ibu</label>
                                        <input type="text" name="pekerjaan_ibu" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('pekerjaan_ibu', $calonSantri->pekerjaan_ibu) }}">
                                    </div>

                                    <div class="col-span-2 mt-2">
                                        <label class="block text-sm font-semibold text-gray-700">Penghasilan</label>
                                        <select name="penghasilan" class="w-full rounded-lg border-gray-300 mt-1">
                                            @php $gaji = $calonSantri->penghasilan_ortu; @endphp
                                            <option value="< 1 Juta" {{ $gaji == '< 1 Juta' ? 'selected' : '' }}>< 1 Juta</option>
                                            <option value="1 - 3 Juta" {{ $gaji == '1 - 3 Juta' ? 'selected' : '' }}>1 - 3 Juta</option>
                                            <option value="3 - 5 Juta" {{ $gaji == '3 - 5 Juta' ? 'selected' : '' }}>3 - 5 Juta</option>
                                            <option value="> 5 Juta" {{ $gaji == '> 5 Juta' ? 'selected' : '' }}>> 5 Juta</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700">No. WhatsApp</label>
                                        <input type="number" name="no_hp" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('no_hp', $calonSantri->no_hp_ayah) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 5. UPDATE BERKAS --}}
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <h3 class="text-gray-700 font-bold border-b border-gray-200 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-gray-200 text-gray-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">5</span>
                                    Update Dokumen
                                </h3>
                                <p class="text-xs text-gray-500 mb-4">Upload file baru hanya jika ingin mengubahnya.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @php
                                        $files = [
                                            'foto_santri' => 'Pas Foto Santri',
                                            'file_kk' => 'Kartu Keluarga',
                                            'file_akta' => 'Akta Kelahiran',
                                            'file_ijazah' => 'Ijazah Terakhir',
                                            'file_skl' => 'SKL',
                                            'file_ktp_ayah' => 'KTP Ayah',
                                            'file_ktp_ibu' => 'KTP Ibu',
                                            'file_kip' => 'KIP/KIS'
                                        ];
                                    @endphp

                                    @foreach($files as $key => $label)
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">{{ $label }}</label>
                                        <input type="file" name="{{ $key }}" class="block w-full text-xs text-gray-500 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-blue-50 file:text-blue-700"/>
                                        @if($calonSantri->$key)
                                            <a href="{{ asset('storage/' . $calonSantri->$key) }}" target="_blank" class="text-xs text-blue-600 hover:underline mt-1 block">
                                                ✓ Lihat File Saat Ini
                                            </a>
                                        @else
                                            <span class="text-xs text-red-400 mt-1 block">x Belum ada file</span>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="mt-8 flex justify-between items-center border-t pt-6">
                        <a href="{{ route('petugas.dashboard') }}" class="text-gray-500 hover:text-gray-900 font-bold text-sm">Batal / Kembali</a>
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-1">SIMPAN PERUBAHAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>