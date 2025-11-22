<x-app-layout hide-nav>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Setoran') }}
        </h2>
    </x-slot>

    <style>
        nav.bg-white.border-b { display: none !important; }
        .min-h-screen { background-color: #f3f4f6; }
    </style>

    <div class="py-6 px-4 max-w-md mx-auto pb-24">
        
        {{-- Flash Message Error --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm">
                <p class="font-bold text-xs uppercase mb-1">Ada Kesalahan Input</p>
                <ul class="list-disc list-inside text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ustadz.jurnal.store') }}" method="POST">
            @csrf
            {{-- Default tanggal hari ini --}}
            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 space-y-5 mb-5">
                {{-- 1. Pilih Kelas --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Kelas</label>
                    <select name="mustawa_id" onchange="window.location.href='{{ route('ustadz.jurnal.create') }}?mustawa_id=' + this.value"
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                        <option value="">-- Pilih Kelas Dulu --</option>
                        @foreach($mustawas as $m)
                            <option value="{{ $m->id }}" {{ $selectedMustawaId == $m->id ? 'selected' : '' }}>
                                {{ $m->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 2. Pilih Santri --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Santri</label>
                    <select name="santri_id" required class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm bg-gray-50"
                        {{ $santris->isEmpty() ? 'disabled' : '' }}>
                        <option value="">
                            {{ $santris->isEmpty() ? ($selectedMustawaId ? '-- Tidak ada data santri aktif --' : '-- Pilih Kelas diatas --') : '-- Pilih Santri --' }}
                        </option>
                        @foreach($santris as $s)
                            {{-- Cek support nama_lengkap atau full_name --}}
                            <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->full_name ?? $s->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 space-y-6">
                
                {{-- 3. Kategori --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kategori</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="kategori_hafalan" value="quran" class="peer sr-only" {{ old('kategori_hafalan', 'quran') == 'quran' ? 'checked' : '' }}>
                            <div class="text-center py-2.5 px-2 border rounded-lg peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 hover:bg-gray-50 transition text-sm font-medium bg-white text-gray-600">
                                Al-Qur'an
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="kategori_hafalan" value="kitab" class="peer sr-only" {{ old('kategori_hafalan') == 'kitab' ? 'checked' : '' }}>
                            <div class="text-center py-2.5 px-2 border rounded-lg peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 hover:bg-gray-50 transition text-sm font-medium bg-white text-gray-600">
                                Kitab/Hadits
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 4. Jenis Setoran --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Jenis Setoran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_setoran" value="ziyadah" class="peer sr-only" {{ old('jenis_setoran', 'ziyadah') == 'ziyadah' ? 'checked' : '' }}>
                            <div class="text-center py-2.5 px-2 border rounded-lg peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-50 transition text-sm font-medium bg-white text-gray-600">
                                Ziyadah <span class="text-[10px] opacity-80">(Baru)</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_setoran" value="murojaah" class="peer sr-only" {{ old('jenis_setoran') == 'murojaah' ? 'checked' : '' }}>
                            <div class="text-center py-2.5 px-2 border rounded-lg peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-600 hover:bg-gray-50 transition text-sm font-medium bg-white text-gray-600">
                                Murojaah <span class="text-[10px] opacity-80">(Ulang)</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 5. Input Materi --}}
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="mb-3">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Surat / Kitab</label>
                        <input type="text" name="materi" value="{{ old('materi') }}" placeholder="Contoh: Al-Mulk / Arbain Nawawi" required
                            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Mulai (Ayat/Hal)</label>
                            <input type="text" name="start_at" value="{{ old('start_at') }}" placeholder="1" 
                                class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Sampai (Ayat/Hal)</label>
                            <input type="text" name="end_at" value="{{ old('end_at') }}" placeholder="5" 
                                class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                        </div>
                    </div>
                </div>

                {{-- 6. Predikat --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Hasil / Predikat</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="predikat" value="A" class="peer sr-only" {{ old('predikat', 'A') == 'A' ? 'checked' : '' }}>
                            <div class="text-center py-3 border rounded-lg peer-checked:bg-green-500 peer-checked:text-white hover:bg-gray-50 transition bg-white">
                                <span class="block font-bold text-lg leading-none">A</span>
                                <span class="text-[10px] uppercase tracking-wider">Lancar</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="predikat" value="B" class="peer sr-only" {{ old('predikat') == 'B' ? 'checked' : '' }}>
                            <div class="text-center py-3 border rounded-lg peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-50 transition bg-white">
                                <span class="block font-bold text-lg leading-none">B</span>
                                <span class="text-[10px] uppercase tracking-wider">Cukup</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="predikat" value="C" class="peer sr-only" {{ old('predikat') == 'C' ? 'checked' : '' }}>
                            <div class="text-center py-3 border rounded-lg peer-checked:bg-red-500 peer-checked:text-white hover:bg-gray-50 transition bg-white">
                                <span class="block font-bold text-lg leading-none">C</span>
                                <span class="text-[10px] uppercase tracking-wider">Ulang</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 7. Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Tulis catatan perkembangan..."
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">{{ old('catatan') }}</textarea>
                </div>

            </div>

            {{-- Tombol Simpan --}}
            <div class="mt-6 space-y-3">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:bg-emerald-700 transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Hafalan
                </button>
                <a href="{{ route('ustadz.jurnal.index') }}" class="block w-full py-3.5 text-center text-gray-600 font-medium rounded-xl border border-gray-300 hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</x-app-layout>