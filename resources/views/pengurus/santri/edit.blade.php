<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- HEADER FIXED --}}
    <div class="fixed top-0 w-full z-40 bg-emerald-600 shadow-md">
        <div class="flex items-center gap-4 px-5 py-4 text-white">
            <a href="{{ route('pengurus.santri.index') }}" class="p-2 -ml-2 rounded-full hover:bg-white/20 transition active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold tracking-wide">Edit Data Lengkap</h1>
                <p class="text-[10px] opacity-80 leading-none">Perbarui seluruh informasi santri</p>
            </div>
        </div>
        {{-- Dekorasi Curve Bawah --}}
        <div class="h-6 bg-emerald-600 rounded-b-[30px] absolute -bottom-3 w-full -z-10"></div>
    </div>

    {{-- CONTAINER FORM --}}
    <div class="min-h-screen bg-gray-50 pt-24 pb-32 px-5">
        
        {{-- ALERT ERROR (PENTING: Agar ketahuan jika ada validasi gagal) --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl shadow-sm animate-pulse">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm leading-5 font-medium text-red-800">
                            Gagal Menyimpan! Periksa inputan berikut:
                        </h3>
                        <div class="mt-2 text-xs text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('pengurus.santri.update', $santri->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- 1. KARTU DATA PRIBADI --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                <h2 class="text-emerald-700 font-bold text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Pribadi
                </h2>

                <div class="space-y-4">
                    {{-- Edit Foto --}}
                    <div class="flex flex-col items-center mb-4">
                        <div class="relative group">
                            <label for="foto" class="w-24 h-24 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-emerald-400 transition overflow-hidden shadow-sm">
                                @if($santri->foto)
                                    <img src="{{ asset('storage/' . $santri->foto) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                                <input type="file" name="foto" id="foto" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <span class="text-[10px] text-gray-400 mt-2">Ketuk gambar untuk ganti</span>
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', $santri->full_name) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 p-3" required>
                    </div>

                    {{-- Identitas Utama (NIS, NISN, NIK) --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">NIS <span class="text-red-500">*</span></label>
                            <input type="number" name="nis" value="{{ old('nis', $santri->nis) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 p-3" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">NISN</label>
                            <input type="number" name="nisn" value="{{ old('nisn', $santri->nisn) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 p-3">
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">NIK (16 Digit)</label>
                            <input type="number" name="nik" value="{{ old('nik', $santri->nik) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 p-3" placeholder="Sesuai KK">
                        </div>
                    </div>

                    {{-- KK & Gender --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">No. KK</label>
                            <input type="number" name="no_kk" value="{{ old('no_kk', $santri->no_kk) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 p-3">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 p-3 h-[48px]">
                                <option value="Laki-laki" {{ in_array(old('jenis_kelamin', $santri->jenis_kelamin), ['L', 'male', 'Laki-laki']) ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ in_array(old('jenis_kelamin', $santri->jenis_kelamin), ['P', 'female', 'Perempuan']) ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    {{-- TTL --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $santri->tempat_lahir) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 p-3">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $santri->tanggal_lahir ? $santri->tanggal_lahir->format('Y-m-d') : '') }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 p-3 text-gray-600">
                        </div>
                    </div>

                    {{-- Detail Tambahan (Anak Ke, Saudara, Gol Darah) --}}
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Anak Ke-</label>
                            <input type="number" name="anak_ke" value="{{ old('anak_ke', $santri->anak_ke) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-center">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Jml Sdr</label>
                            <input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara', $santri->jumlah_saudara) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-center">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Gol. Darah</label>
                            <select name="golongan_darah" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                                <option value="-">-</option>
                                @foreach(['A', 'B', 'AB', 'O'] as $gol)
                                    <option value="{{ $gol }}" {{ old('golongan_darah', $santri->golongan_darah) == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Riwayat Penyakit</label>
                        <textarea name="riwayat_penyakit" rows="2" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 p-3" placeholder="Contoh: Asma, Alergi Dingin...">{{ old('riwayat_penyakit', $santri->riwayat_penyakit) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. KARTU AKADEMIK --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                <h2 class="text-blue-600 font-bold text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Penempatan
                </h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Status</label>
                            <select name="status" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                                <option value="active" {{ $santri->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="graduated" {{ $santri->status == 'graduated' ? 'selected' : '' }}>Lulus</option>
                                <option value="moved" {{ $santri->status == 'moved' ? 'selected' : '' }}>Pindah</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" value="{{ old('tahun_masuk', $santri->tahun_masuk) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Asrama</label>
                        <select name="asrama_id" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                            <option value="">Pilih Asrama</option>
                            @foreach(\App\Models\Asrama::all() as $asrama)
                                <option value="{{ $asrama->id }}" {{ $santri->asrama_id == $asrama->id ? 'selected' : '' }}>
                                    {{ $asrama->nama_asrama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Kelas Formal</label>
                        <select name="kelas_id" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                            <option value="">Pilih Kelas</option>
                            @foreach(\App\Models\Kelas::all() as $kelas)
                                <option value="{{ $kelas->id }}" {{ $santri->kelas_id == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 3. KARTU ALAMAT --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-orange-400"></div>
                <h2 class="text-orange-500 font-bold text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Alamat Lengkap
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Alamat Jalan / Dusun</label>
                        <textarea name="alamat" rows="2" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-orange-400 p-3">{{ old('alamat', $santri->alamat) }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">RT</label>
                            <input type="text" name="rt" value="{{ old('rt', $santri->rt) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-center">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">RW</label>
                            <input type="text" name="rw" value="{{ old('rw', $santri->rw) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-center">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Kode Pos</label>
                            <input type="number" name="kode_pos" value="{{ old('kode_pos', $santri->kode_pos) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl p-3 text-center">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Desa/Kel</label>
                            <input type="text" name="desa" value="{{ old('desa', $santri->desa) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $santri->kecamatan) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl p-3">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. KARTU ORANG TUA (TABBED) --}}
            <div x-data="{ tab: 'ayah' }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex bg-gray-100 p-1">
                    <button type="button" @click="tab = 'ayah'" 
                        class="flex-1 py-3 text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2"
                        :class="tab === 'ayah' ? 'bg-white shadow text-blue-600' : 'text-gray-400 hover:text-gray-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Data Ayah
                    </button>
                    <button type="button" @click="tab = 'ibu'" 
                        class="flex-1 py-3 text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2"
                        :class="tab === 'ibu' ? 'bg-white shadow text-pink-500' : 'text-gray-400 hover:text-gray-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Data Ibu
                    </button>
                </div>

                <div class="p-5">
                    {{-- FORM AYAH --}}
                    <div x-show="tab === 'ayah'" class="space-y-4 animate-fade-in">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nama Ayah</label>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $santri->nama_ayah) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">NIK Ayah</label>
                                <input type="number" name="nik_ayah" value="{{ old('nik_ayah', $santri->nik_ayah) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tahun Lahir</label>
                                <input type="number" name="thn_lahir_ayah" value="{{ old('thn_lahir_ayah', $santri->thn_lahir_ayah) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Pendidikan Terakhir</label>
                            <select name="pendidikan_ayah" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                                <option value="">- Pilih -</option>
                                @foreach(['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3', 'Tidak Sekolah'] as $pdd)
                                    <option value="{{ $pdd }}" {{ old('pendidikan_ayah', $santri->pendidikan_ayah) == $pdd ? 'selected' : '' }}>{{ $pdd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Pekerjaan</label>
                            <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $santri->pekerjaan_ayah) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Penghasilan</label>
                                <select name="penghasilan_ayah" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                                    <option value="">- Pilih -</option>
                                    @foreach(['< 1 Juta', '1 - 3 Juta', '3 - 5 Juta', '> 5 Juta', 'Tidak Berpenghasilan'] as $inc)
                                        <option value="{{ $inc }}" {{ old('penghasilan_ayah', $santri->penghasilan_ayah) == $inc ? 'selected' : '' }}>{{ $inc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">No. HP (WA)</label>
                                <input type="number" name="no_hp_ayah" value="{{ old('no_hp_ayah', $santri->no_hp_ayah) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3" placeholder="08xxxx">
                            </div>
                        </div>
                    </div>

                    {{-- FORM IBU --}}
                    <div x-show="tab === 'ibu'" style="display: none;" class="space-y-4 animate-fade-in">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nama Ibu</label>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $santri->nama_ibu) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">NIK Ibu</label>
                                <input type="number" name="nik_ibu" value="{{ old('nik_ibu', $santri->nik_ibu) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tahun Lahir</label>
                                <input type="number" name="thn_lahir_ibu" value="{{ old('thn_lahir_ibu', $santri->thn_lahir_ibu) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Pendidikan Terakhir</label>
                            <select name="pendidikan_ibu" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3">
                                <option value="">- Pilih -</option>
                                @foreach(['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3', 'Tidak Sekolah'] as $pdd)
                                    <option value="{{ $pdd }}" {{ old('pendidikan_ibu', $santri->pendidikan_ibu) == $pdd ? 'selected' : '' }}>{{ $pdd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Pekerjaan</label>
                            <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $santri->pekerjaan_ibu) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Penghasilan</label>
                                <select name="penghasilan_ibu" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3">
                                    <option value="">- Pilih -</option>
                                    @foreach(['< 1 Juta', '1 - 3 Juta', '3 - 5 Juta', '> 5 Juta', 'Tidak Berpenghasilan'] as $inc)
                                        <option value="{{ $inc }}" {{ old('penghasilan_ibu', $santri->penghasilan_ibu) == $inc ? 'selected' : '' }}>{{ $inc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">No. HP (WA)</label>
                                <input type="number" name="no_hp_ibu" value="{{ old('no_hp_ibu', $santri->no_hp_ibu) }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3" placeholder="08xxxx">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. TOMBOL SIMPAN MELAYANG --}}
            <div class="fixed bottom-0 left-0 w-full p-4 bg-white border-t border-gray-200 z-50 flex justify-between shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <a href="{{ route('pengurus.santri.show', $santri->id) }}" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-100 flex items-center gap-2">
                    Batal
                </a>
                <button type="submit" class="flex-1 ml-4 bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 active:scale-95 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>