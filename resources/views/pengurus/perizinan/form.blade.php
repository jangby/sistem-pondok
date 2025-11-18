<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20" x-data="{ tipe: 'keluar_sebentar' }">
        {{-- Header Santri (Sama kayak UKS) --}}
        <div class="bg-white px-6 py-6 rounded-b-[30px] shadow-sm border-b border-gray-100">
            <h1 class="text-xl font-bold text-gray-800">Catat Perizinan</h1>
            <p class="text-sm text-gray-500">{{ $santri->full_name }} ({{ $santri->nis }})</p>
        </div>

        <form action="{{ route('pengurus.perizinan.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="santri_id" value="{{ $santri->id }}">

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Izin</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_izin" value="keluar_sebentar" x-model="tipe" class="peer sr-only">
                        <div class="p-3 rounded-xl border border-gray-200 bg-white peer-checked:bg-blue-50 peer-checked:border-blue-500 text-center transition">
                            <span class="block font-bold text-sm text-gray-700 peer-checked:text-blue-700">Keluar Sebentar</span>
                            <span class="text-[10px] text-gray-400">Hitungan Jam</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_izin" value="pulang" x-model="tipe" class="peer sr-only">
                        <div class="p-3 rounded-xl border border-gray-200 bg-white peer-checked:bg-purple-50 peer-checked:border-purple-500 text-center transition">
                            <span class="block font-bold text-sm text-gray-700 peer-checked:text-purple-700">Pulang</span>
                            <span class="text-[10px] text-gray-400">Menginap / Hari</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Input Dinamis --}}
            <div x-show="tipe == 'keluar_sebentar'">
                <label class="block text-sm font-bold text-gray-700 mb-2">Durasi Izin (Jam)</label>
                <select name="durasi_jam" class="w-full rounded-xl border-gray-300">
                    <option value="1">1 Jam</option>
                    <option value="3">3 Jam</option>
                    <option value="6">6 Jam (Setengah Hari)</option>
                    <option value="12">12 Jam (Sampai Malam)</option>
                </select>
            </div>

            <div x-show="tipe == 'pulang'" style="display: none;">
                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Kembali</label>
                <input type="date" name="tgl_kembali" class="w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Alasan</label>
                <textarea name="alasan" rows="2" class="w-full rounded-xl border-gray-300" required placeholder="Cth: Beli keperluan, Acara keluarga..."></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg active:scale-95 transition">
                    Catat Izin
                </button>
            </div>
        </form>
    </div>
</x-app-layout>