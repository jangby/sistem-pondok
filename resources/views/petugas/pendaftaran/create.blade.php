<x-app-layout>
    <x-slot name="navigation">
        @include('layouts.petugas-nav')
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded shadow-sm">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Terjadi Kesalahan Input:
                    </p>
                    <ul class="list-disc ml-8 text-sm mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight">Formulir Pendaftaran Lengkap</h2>
                        <p class="text-sm text-emerald-100 mt-1 flex items-center gap-2">
                            <span class="bg-white/20 px-2 py-0.5 rounded text-xs">Offline / Walk-in</span>
                            <span>Gelombang: {{ $gelombang->nama_gelombang ?? 'Aktif' }}</span>
                        </p>
                    </div>
                </div>

                <form action="{{ route('petugas.pendaftaran.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- KOLOM KIRI --}}
                        <div class="space-y-8">
                            {{-- 1. IDENTITAS --}}
                            <div>
                                <h3 class="text-emerald-700 font-bold border-b-2 border-emerald-100 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                                    Identitas Santri
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('nama_lengkap') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">NIK (16 Digit)</label>
                                        <input type="number" name="nik" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('nik') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Nomor KK</label>
                                        <input type="number" name="no_kk" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('no_kk') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">NISN</label>
                                        <input type="number" name="nisn" class="w-full rounded-lg border-gray-300 mt-1" value="{{ old('nisn') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="w-full rounded-lg border-gray-300 mt-1" required>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('tempat_lahir') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('tanggal_lahir') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Anak Ke-</label>
                                        <input type="number" name="anak_ke" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('anak_ke') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Jml Saudara</label>
                                        <input type="number" name="jumlah_saudara" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('jumlah_saudara') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 2. ALAMAT --}}
                            <div>
                                <h3 class="text-emerald-700 font-bold border-b-2 border-emerald-100 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                    Alamat Domisili
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700">Alamat Jalan / Blok</label>
                                        <textarea name="alamat" rows="2" class="w-full rounded-lg border-gray-300 mt-1" required>{{ old('alamat') }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">RT</label>
                                        <input type="number" name="rt" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('rt') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">RW</label>
                                        <input type="number" name="rw" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('rw') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Desa/Kelurahan</label>
                                        <input type="text" name="desa" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('desa') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Kecamatan</label>
                                        <input type="text" name="kecamatan" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('kecamatan') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Kabupaten/Kota</label>
                                        <input type="text" name="kabupaten" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('kabupaten') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Provinsi</label>
                                        <input type="text" name="provinsi" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('provinsi') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Kode Pos</label>
                                        <input type="number" name="kode_pos" class="w-full rounded-lg border-gray-300 mt-1" value="{{ old('kode_pos') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="space-y-8">
                            {{-- 3. AKADEMIK --}}
                            <div>
                                <h3 class="text-emerald-700 font-bold border-b-2 border-emerald-100 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                                    Data Akademik
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Asal Sekolah</label>
                                        <input type="text" name="sekolah_asal" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('sekolah_asal') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Daftar ke Jenjang</label>
                                        <select name="jenjang" class="w-full rounded-lg border-gray-300 mt-1 font-bold text-emerald-800" required>
                                            <option value="">-- Pilih Jenjang --</option>
                                            <option value="SMP" {{ old('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                            <option value="SMA" {{ old('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                            <option value="TAKHOSUS" {{ old('jenjang') == 'TAKHOSUS' ? 'selected' : '' }}>TAKHOSUS</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. DATA ORANG TUA --}}
                            <div>
                                <h3 class="text-emerald-700 font-bold border-b-2 border-emerald-100 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">4</span>
                                    Data Orang Tua
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2 md:col-span-1">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">Nama Ayah</label>
                                        <input type="text" name="nama_ayah" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('nama_ayah') }}">
                                    </div>
                                    <div class="col-span-2 md:col-span-1">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">NIK Ayah</label>
                                        <input type="number" name="nik_ayah" class="w-full rounded-lg border-gray-300 mt-1" value="{{ old('nik_ayah') }}">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">Pekerjaan Ayah</label>
                                        <input type="text" name="pekerjaan_ayah" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('pekerjaan_ayah') }}">
                                    </div>

                                    <div class="col-span-2 md:col-span-1 mt-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">Nama Ibu</label>
                                        <input type="text" name="nama_ibu" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('nama_ibu') }}">
                                    </div>
                                    <div class="col-span-2 md:col-span-1 mt-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">NIK Ibu</label>
                                        <input type="number" name="nik_ibu" class="w-full rounded-lg border-gray-300 mt-1" value="{{ old('nik_ibu') }}">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase">Pekerjaan Ibu</label>
                                        <input type="text" name="pekerjaan_ibu" class="w-full rounded-lg border-gray-300 mt-1" required value="{{ old('pekerjaan_ibu') }}">
                                    </div>

                                    <div class="col-span-2 mt-2">
                                        <label class="block text-sm font-semibold text-gray-700">Penghasilan Bulanan</label>
                                        <select name="penghasilan" class="w-full rounded-lg border-gray-300 mt-1">
                                            <option value="< 1 Juta">< 1 Juta</option>
                                            <option value="1 - 3 Juta">1 - 3 Juta</option>
                                            <option value="3 - 5 Juta">3 - 5 Juta</option>
                                            <option value="> 5 Juta">> 5 Juta</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nomor WhatsApp</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-gray-500 font-bold">+62</span>
                                            <input type="number" name="no_hp" class="w-full pl-12 rounded-lg border-gray-300 mt-1" required value="{{ old('no_hp') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 5. UPLOAD BERKAS --}}
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <h3 class="text-gray-700 font-bold border-b border-gray-200 pb-2 mb-4 flex items-center gap-2">
                                    <span class="bg-gray-200 text-gray-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">5</span>
                                    Upload Dokumen (Opsional)
                                </h3>
                                <p class="text-xs text-gray-500 mb-4">Maksimal 2MB per file. Pas Foto (JPG/PNG), Lainnya (PDF).</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">Pas Foto Santri</label>
                                        <input type="file" name="foto_santri" class="block w-full text-xs text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">Kartu Keluarga (KK)</label>
                                        <input type="file" name="file_kk" class="block w-full text-xs text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">Akta Kelahiran</label>
                                        <input type="file" name="file_akta" class="block w-full text-xs text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">Ijazah Terakhir</label>
                                        <input type="file" name="file_ijazah" class="block w-full text-xs text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">SKL (Surat Ket. Lulus)</label>
                                        <input type="file" name="file_skl" class="block w-full text-xs text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">KTP Ayah</label>
                                        <input type="file" name="file_ktp_ayah" class="block w-full text-xs text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">KTP Ibu</label>
                                        <input type="file" name="file_ktp_ibu" class="block w-full text-xs text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">KIP/KIS (Jika Ada)</label>
                                        <input type="file" name="file_kip" class="block w-full text-xs text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="mt-8 flex justify-between items-center border-t pt-6">
                        <a href="{{ route('petugas.dashboard') }}" class="text-gray-500 hover:text-gray-900 font-bold text-sm">Batal / Kembali</a>
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-emerald-700 shadow-lg transition transform hover:-translate-y-1">SIMPAN DATA LENGKAP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>