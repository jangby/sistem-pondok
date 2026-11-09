<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- HEADER DENGAN STYLE NATIVE APP --}}
    <div class="fixed top-0 w-full z-40 bg-emerald-600 shadow-md">
        <div class="flex items-center gap-4 px-5 py-4 text-white">
            <a href="{{ route('pengurus.santri.index') }}" class="p-2 -ml-2 rounded-full hover:bg-white/20 transition active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold tracking-wide">Input Data Santri</h1>
        </div>
        {{-- Dekorasi Curve Bawah --}}
        <div class="h-6 bg-emerald-600 rounded-b-[30px] absolute -bottom-3 w-full -z-10"></div>
    </div>

    {{-- CONTAINER FORM (Padding top disesuaikan dengan fixed header) --}}
    <div class="min-h-screen bg-gray-50 pt-24 pb-32 px-5">
        
        <form action="{{ route('pengurus.santri.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- 1. KARTU DATA PRIBADI --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                <h2 class="text-emerald-700 font-bold text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Pribadi
                </h2>

                <div class="space-y-4">
                    {{-- Upload Foto --}}
                    <div class="flex justify-center mb-4">
                        <div class="relative">
                            <label for="foto" class="w-24 h-24 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-emerald-400 transition overflow-hidden group">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-[10px] text-gray-400 mt-1">Upload Foto</span>
                                <input type="file" name="foto" id="foto" class="hidden" accept="image/*">
                            </label>
                        </div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all p-3 font-medium" placeholder="Contoh: Ahmad Abdullah" required>
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- NIS & NIK (Grid) --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">NIS <span class="text-red-500">*</span></label>
                            <input type="number" name="nis" value="{{ old('nis') }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 p-3" placeholder="Nomor Induk" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">NIK (16 Digit)</label>
                            <input type="number" name="nik" value="{{ old('nik') }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 p-3" placeholder="Sesuai KK">
                        </div>
                    </div>

                    {{-- No KK & Jenis Kelamin --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">No. KK</label>
                            <input type="number" name="no_kk" value="{{ old('no_kk') }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 p-3" placeholder="Nomor KK">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 p-3 h-[46px]">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    {{-- TTL --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 p-3" placeholder="Kota Lahir">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 p-3 text-gray-600">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. KARTU AKADEMIK & ASRAMA --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                <h2 class="text-blue-600 font-bold text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Penempatan Santri
                </h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Status</label>
                            <select name="status" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                                <option value="active" selected>Aktif</option>
                                <option value="graduated">Lulus</option>
                                <option value="moved">Pindah</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" value="{{ date('Y') }}" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                        </div>
                    </div>

                    {{-- Asrama & Kelas --}}
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Asrama</label>
                        <select name="asrama_id" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                            <option value="">Pilih Asrama</option>
                            @foreach(\App\Models\Asrama::all() as $asrama)
                                <option value="{{ $asrama->id }}">{{ $asrama->nama_asrama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Kelas Formal</label>
                        <select name="kelas_id" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                            <option value="">Pilih Kelas</option>
                            @foreach(\App\Models\Kelas::all() as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 3. KARTU ALAMAT DOMISILI --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-orange-400"></div>
                <h2 class="text-orange-500 font-bold text-sm uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Alamat Lengkap
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Alamat Jalan / Dusun</label>
                        <textarea name="alamat" rows="2" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-orange-400 p-3" placeholder="Nama jalan, gang, nomor rumah..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">RT</label>
                            <input type="text" name="rt" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-orange-400 p-3 text-center" placeholder="001">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">RW</label>
                            <input type="text" name="rw" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-orange-400 p-3 text-center" placeholder="002">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Kode Pos</label>
                            <input type="number" name="kode_pos" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-orange-400 p-3 text-center" placeholder="46123">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Desa/Kel</label>
                            <input type="text" name="desa" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-orange-400 p-3">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Kecamatan</label>
                            <input type="text" name="kecamatan" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-orange-400 p-3">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. KARTU ORANG TUA (ACCORDION STYLE) --}}
            <div x-data="{ tab: 'ayah' }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Header Tabs --}}
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
                            <input type="text" name="nama_ayah" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3" placeholder="Nama Lengkap Ayah">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">NIK Ayah</label>
                                <input type="number" name="nik_ayah" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tahun Lahir</label>
                                <input type="number" name="thn_lahir_ayah" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3" placeholder="YYYY">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Pekerjaan</label>
                            <input type="text" name="pekerjaan_ayah" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 p-3">
                        </div>
                    </div>

                    {{-- FORM IBU --}}
                    <div x-show="tab === 'ibu'" style="display: none;" class="space-y-4 animate-fade-in">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3" placeholder="Nama Lengkap Ibu">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">NIK Ibu</label>
                                <input type="number" name="nik_ibu" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tahun Lahir</label>
                                <input type="number" name="thn_lahir_ibu" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3" placeholder="YYYY">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Pekerjaan</label>
                            <input type="text" name="pekerjaan_ibu" class="mt-1 w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-pink-500 p-3">
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL SIMPAN FLOATING --}}
            <div class="fixed bottom-0 left-0 w-full p-4 bg-white border-t border-gray-200 z-50 flex items-center justify-between shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <a href="{{ route('pengurus.santri.index') }}" class="text-gray-500 font-bold text-sm px-4">Batal</a>
                <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-95 transition w-2/3 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Data
                </button>
            </div>

        </form>
    </div>

    <style>
        /* Animasi halus untuk tab */
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>