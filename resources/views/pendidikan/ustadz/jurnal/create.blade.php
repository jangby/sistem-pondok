<x-ustadz-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">
            {{ __('Input Setoran') }}
        </h2>
    </x-slot>

    <div class="py-4 px-4 max-w-md mx-auto pb-20">
        
        <form action="{{ route('ustadz.jurnal.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

            {{-- 1. Pilih Kelas (Auto Reload) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                <select name="mustawa_id" onchange="window.location.href='{{ route('ustadz.jurnal.create') }}?mustawa_id=' + this.value"
                    class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                    <option value="">-- Pilih Kelas Dulu --</option>
                    @foreach($mustawas as $m)
                        <option value="{{ $m->id }}" {{ $selectedMustawaId == $m->id ? 'selected' : '' }}>
                            {{ $m->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 2. Pilih Santri --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Santri</label>
                <select name="santri_id" required class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm bg-gray-50"
                    {{ $santris->isEmpty() ? 'disabled' : '' }}>
                    <option value="">
                        {{ $santris->isEmpty() ? ($selectedMustawaId ? '-- Tidak ada santri --' : '-- Pilih Kelas diatas --') : '-- Pilih Santri --' }}
                    </option>
                    @foreach($santris as $s)
                        <option value="{{ $s->id }}">{{ $s->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 space-y-5">
                
                {{-- 3. Kategori (Quran / Kitab) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kategori</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="kategori_hafalan" value="quran" class="peer sr-only" checked>
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-600 transition text-sm font-medium">
                                Al-Qur'an
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="kategori_hafalan" value="kitab" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-600 transition text-sm font-medium">
                                Kitab/Hadits
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 4. Jenis Setoran (Ziyadah / Murojaah) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Jenis Setoran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_setoran" value="ziyadah" class="peer sr-only" checked>
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-600 transition text-sm font-medium">
                                Ziyadah (Baru)
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_setoran" value="murojaah" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-600 transition text-sm font-medium">
                                Murojaah (Ulang)
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 5. Input Materi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Surat / Kitab</label>
                    <input type="text" name="materi" placeholder="Contoh: Al-Mulk" required
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari (Ayat/Hal)</label>
                        <input type="text" name="start_at" placeholder="1" 
                            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai</label>
                        <input type="text" name="end_at" placeholder="5" 
                            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                    </div>
                </div>

                {{-- 6. Predikat Kelancaran --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Hasil / Predikat</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="predikat" value="A" class="peer sr-only" checked>
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-green-500 peer-checked:text-white hover:bg-gray-50 transition">
                                <span class="block font-bold text-lg">A</span>
                                <span class="text-[10px]">Lancar</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="predikat" value="B" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-50 transition">
                                <span class="block font-bold text-lg">B</span>
                                <span class="text-[10px]">Cukup</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="predikat" value="C" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-red-500 peer-checked:text-white hover:bg-gray-50 transition">
                                <span class="block font-bold text-lg">C</span>
                                <span class="text-[10px]">Ulang</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 7. Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Tajwid perlu diperbaiki..."
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"></textarea>
                </div>

            </div>

            {{-- Tombol Simpan --}}
            <div class="mt-6">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:bg-emerald-700 transition active:scale-95">
                    Simpan Hafalan
                </button>
                <a href="{{ route('ustadz.jurnal.index') }}" class="block text-center text-gray-500 text-sm mt-4 hover:text-gray-800">
                    Batal / Kembali
                </a>
            </div>

        </form>
    </div>
</x-ustadz-layout>