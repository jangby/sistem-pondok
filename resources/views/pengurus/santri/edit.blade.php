<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-white pb-20">
        <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-30">
            <a href="{{ route('pengurus.santri.index') }}" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Edit Santri</h1>
        </div>

        <form action="{{ route('pengurus.santri.update', $santri->id) }}" method="POST" class="p-6 space-y-6">
            @csrf @method('PUT')

            {{-- Bagian Atas: NIS & RFID --}}
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIS</label>
                        <input type="text" name="nis" value="{{ old('nis', $santri->nis) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RFID UID</label>
                        <input type="text" name="rfid_uid" value="{{ old('rfid_uid', $santri->rfid_uid) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 bg-gray-50" placeholder="Tap Kartu...">
                    </div>
                </div>
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $santri->full_name) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kelas</label>
                    <select name="kelas_id" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih -</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $santri->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="active" {{ $santri->status == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="graduated" {{ $santri->status == 'graduated' ? 'selected' : '' }}>Lulus</option>
                        <option value="moved" {{ $santri->status == 'moved' ? 'selected' : '' }}>Pindah</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Orang Tua</label>
                <select name="orang_tua_id" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                    @foreach($orangTuas as $ortu)
                        <option value="{{ $ortu->id }}" {{ $santri->orang_tua_id == $ortu->id ? 'selected' : '' }}>{{ $ortu->name }}</option>
                    @endforeach
                </select>
            </div>

            <h3 class="font-bold text-gray-800 pt-4 border-t border-gray-100">Data Detail</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $santri->tempat_lahir) }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $santri->tanggal_lahir ? $santri->tanggal_lahir->format('Y-m-d') : '') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="Laki-laki" {{ $santri->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $santri->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Gol. Darah</label>
                    <select name="golongan_darah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">-</option>
                        <option value="A" {{ $santri->golongan_darah == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ $santri->golongan_darah == 'B' ? 'selected' : '' }}>B</option>
                        <option value="AB" {{ $santri->golongan_darah == 'AB' ? 'selected' : '' }}>AB</option>
                        <option value="O" {{ $santri->golongan_darah == 'O' ? 'selected' : '' }}>O</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Riwayat Penyakit</label>
                <textarea name="riwayat_penyakit" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">{{ old('riwayat_penyakit', $santri->riwayat_penyakit) }}</textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 active:scale-95 transition">
                    Update Data
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-dashed border-gray-200">
            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M19 19h-5m-9-6h5a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zM6 21v-2a1 1 0 011-1h10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"></path></svg>
                Kartu Digital (QR Code)
            </h3>

            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div class="bg-white p-2 rounded-lg shadow-sm">
                    {{-- Tampilkan QR Code Menggunakan API Gratis (Tanpa install library berat) --}}
                    @if($santri->qrcode_token)
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $santri->qrcode_token }}" alt="QR Code" class="w-20 h-20">
                    @else
                        <div class="w-20 h-20 bg-gray-100 flex items-center justify-center text-xs text-gray-400 text-center">Belum Ada</div>
                    @endif
                </div>
                
                <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-2">QR Code ini digunakan untuk absensi dan perizinan.</p>
                    
                    <form action="{{ route('pengurus.santri.regenerate-qr', $santri->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('QR Code lama tidak akan bisa dipakai lagi. Lanjutkan?')" class="text-xs bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-lg font-bold shadow-sm hover:bg-gray-50 active:scale-95 transition">
                            Generate Ulang QR
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>