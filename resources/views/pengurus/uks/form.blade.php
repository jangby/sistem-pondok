<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- Header Santri Dikenali --}}
        <div class="bg-white px-6 pt-6 pb-6 rounded-b-[30px] shadow-sm border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center text-2xl font-bold text-emerald-700">
                    {{ substr($santri->full_name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Santri Ditemukan</p>
                    <h1 class="text-xl font-bold text-gray-800 leading-tight">{{ $santri->full_name }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $santri->nis }} • {{ $santri->kelas->nama_kelas ?? '-' }}</p>
                </div>
            </div>

            @if($santri->riwayat_penyakit)
                <div class="mt-4 bg-red-50 border border-red-100 rounded-xl p-3 flex gap-3 items-start">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <p class="text-xs font-bold text-red-700 uppercase">Riwayat Penyakit Bawaan:</p>
                        <p class="text-sm text-red-600 leading-tight">{{ $santri->riwayat_penyakit }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Form Input Sakit --}}
        <form action="{{ route('pengurus.uks.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="santri_id" value="{{ $santri->id }}">

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Keluhan Utama</label>
                <textarea name="keluhan" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500" placeholder="Contoh: Demam tinggi, pusing, batuk..." required autofocus></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Tindakan / Obat</label>
                <textarea name="tindakan" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500" placeholder="Contoh: Paracetamol 500mg, istirahat..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status Saat Ini</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="sakit_ringan" class="peer sr-only" checked>
                        <div class="p-3 rounded-xl border border-gray-200 bg-white peer-checked:bg-yellow-50 peer-checked:border-yellow-500 text-center transition">
                            <span class="block font-bold text-gray-700 peer-checked:text-yellow-700">Sakit Ringan</span>
                            <span class="text-[10px] text-gray-400">Boleh Pulang/Kelas</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="dirawat_di_asrama" class="peer sr-only">
                        <div class="p-3 rounded-xl border border-gray-200 bg-white peer-checked:bg-orange-50 peer-checked:border-orange-500 text-center transition">
                            <span class="block font-bold text-gray-700 peer-checked:text-orange-700">Rawat Asrama</span>
                            <span class="text-[10px] text-gray-400">Bedrest UKS</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="rujuk_rs" class="peer sr-only">
                        <div class="p-3 rounded-xl border border-gray-200 bg-white peer-checked:bg-red-50 peer-checked:border-red-500 text-center transition">
                            <span class="block font-bold text-gray-700 peer-checked:text-red-700">Rujuk RS/Dokter</span>
                            <span class="text-[10px] text-gray-400">Butuh Medis Lanjut</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-200 active:scale-95 transition">
                    Simpan Data
                </button>
                <a href="{{ route('pengurus.uks.scan') }}" class="block text-center text-gray-500 text-sm mt-4 py-2">Batal</a>
            </div>
        </form>

    </div>
</x-app-layout>