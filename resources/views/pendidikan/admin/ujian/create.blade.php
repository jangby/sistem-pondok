<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Jadwal Ujian Baru') }}
            </h2>
            <a href="{{ route('pendidikan.admin.ujian.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    
                    <form action="{{ route('pendidikan.admin.ujian.store') }}" method="POST">
                        @csrf

                        {{-- STEP 1: KONFIGURASI UMUM --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mb-6 grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Ujian</label>
                                <select name="jenis_ujian" class="w-full rounded-lg border-gray-300 text-sm">
                                    <option value="uts">UTS (Tengah Semester)</option>
                                    <option value="uas">UAS (Akhir Semester)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Semester</label>
                                <select name="semester" class="w-full rounded-lg border-gray-300 text-sm">
                                    <option value="ganjil">Ganjil</option>
                                    <option value="genap">Genap</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tahun Ajaran</label>
                                <input type="text" name="tahun_ajaran" value="{{ date('Y') }}/{{ date('Y')+1 }}" class="w-full rounded-lg border-gray-300 text-sm">
                            </div>
                        </div>

                        {{-- STEP 2: DETAIL JADWAL --}}
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            
                            {{-- Kiri: Kelas & Mapel --}}
                            <div class="space-y-4">
                                <div>
                                    <x-input-label>Kelas (Mustawa)</x-input-label>
                                    <select name="mustawa_id" class="w-full rounded-lg border-gray-300 text-sm" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($mustawas as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label>Mata Ujian (Kitab)</x-input-label>
                                    <select name="mapel_diniyah_id" class="w-full rounded-lg border-gray-300 text-sm" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($mapels as $mp)
                                            <option value="{{ $mp->id }}">{{ $mp->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label>Kategori Tes</x-input-label>
                                    <div class="flex gap-4 mt-2">
                                        <label class="flex items-center text-sm"><input type="radio" name="kategori_tes" value="tulis" checked class="mr-2 text-emerald-600"> Tulis</label>
                                        <label class="flex items-center text-sm"><input type="radio" name="kategori_tes" value="lisan" class="mr-2 text-emerald-600"> Lisan</label>
                                        <label class="flex items-center text-sm"><input type="radio" name="kategori_tes" value="praktek" class="mr-2 text-emerald-600"> Praktek</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Kanan: Waktu & Pengawas --}}
                            <div class="space-y-4">
                                <div>
                                    <x-input-label>Tanggal Ujian</x-input-label>
                                    <input type="date" name="tanggal" class="w-full rounded-lg border-gray-300 text-sm" required>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <x-input-label>Jam Mulai</x-input-label>
                                        <input type="time" name="jam_mulai" class="w-full rounded-lg border-gray-300 text-sm" required>
                                    </div>
                                    <div>
                                        <x-input-label>Jam Selesai</x-input-label>
                                        <input type="time" name="jam_selesai" class="w-full rounded-lg border-gray-300 text-sm" required>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label>Pengawas / Penguji</x-input-label>
                                    <select name="pengawas_id" class="w-full rounded-lg border-gray-300 text-sm" required>
                                        <option value="">-- Pilih Ustadz --</option>
                                        @foreach($ustadzs as $us)
                                            <option value="{{ $us->id }}">{{ $us->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('pengawas_id')" class="mt-1" />
                                </div>
                            </div>

                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-100">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-lg">
                                Simpan Jadwal
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>