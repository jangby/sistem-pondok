<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex items-center gap-4">
            <a href="{{ route('pengurus.absensi.kegiatan') }}" class="text-gray-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
            <h1 class="font-bold text-lg text-gray-800">Kelola Kegiatan</h1>
        </div>

        <div class="p-6" x-data="{ frekuensi: 'harian', target: 'semua' }">
            
            <form action="{{ route('pengurus.absensi.kegiatan.settings.store') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" class="w-full rounded-xl border-gray-200" placeholder="Cth: Ekskul Panahan" required>
                </div>

                {{-- Waktu --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="w-full rounded-xl border-gray-200" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="w-full rounded-xl border-gray-200" required>
                    </div>
                </div>

                {{-- Frekuensi --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Frekuensi</label>
                    <select name="frekuensi" x-model="frekuensi" class="w-full rounded-xl border-gray-200">
                        <option value="harian">Setiap Hari</option>
                        <option value="mingguan">Mingguan (Hari Tertentu)</option>
                        <option value="bulanan">Bulanan (Tanggal Tertentu)</option>
                    </select>

                    {{-- Pilihan Hari (Jika Mingguan) --}}
                    <div x-show="frekuensi == 'mingguan'" class="mt-3 grid grid-cols-4 gap-2">
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                            <label class="flex items-center space-x-2 bg-white p-2 rounded-lg border border-gray-200">
                                <input type="checkbox" name="detail_waktu[]" value="{{ $hari }}" class="rounded text-orange-500 focus:ring-orange-500">
                                <span class="text-xs">{{ substr($hari, 0, 3) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Peserta --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Target Peserta</label>
                    <select name="tipe_peserta" x-model="target" class="w-full rounded-xl border-gray-200">
                        <option value="semua">Semua Santri</option>
                        <option value="kelas">Kelas Tertentu</option>
                        <option value="khusus">Santri Tertentu</option>
                    </select>

                    {{-- Pilih Kelas --}}
                    <div x-show="target == 'kelas'" class="mt-3 max-h-40 overflow-y-auto border border-gray-100 rounded-xl p-2">
                        @foreach($kelas as $k)
                            <label class="flex items-center space-x-2 py-1 border-b border-gray-50 last:border-0">
                                <input type="checkbox" name="detail_peserta[]" value="{{ $k->id }}" class="rounded text-orange-500">
                                <span class="text-xs">{{ $k->nama_kelas }}</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Pilih Santri (Simple List) --}}
                    <div x-show="target == 'khusus'" class="mt-3 max-h-60 overflow-y-auto border border-gray-100 rounded-xl p-2">
                        <input type="text" placeholder="Cari nama..." class="w-full text-xs border-gray-200 rounded-lg mb-2">
                        @foreach($santris as $s)
                            <label class="flex items-center space-x-2 py-1 border-b border-gray-50">
                                <input type="checkbox" name="detail_peserta[]" value="{{ $s->id }}" class="rounded text-orange-500">
                                <span class="text-xs">{{ $s->full_name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg active:scale-95 transition">Simpan Kegiatan</button>
            </form>

            {{-- List Kegiatan --}}
            <div class="mt-10">
                <h3 class="font-bold text-gray-800 mb-4">Daftar Kegiatan</h3>
                <div class="space-y-3">
                    @foreach($kegiatans as $k)
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $k->nama_kegiatan }}</p>
                                <p class="text-[10px] text-gray-500 uppercase">
                                    {{ $k->frekuensi }} • {{ $k->jam_mulai }} - {{ $k->jam_selesai }}
                                </p>
                            </div>
                            <form action="{{ route('pengurus.absensi.kegiatan.delete', $k->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>