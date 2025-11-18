<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-white pb-20">
        
        {{-- Header --}}
        <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-30">
            <a href="{{ route('pengurus.uks.show', $uks->id) }}" class="text-gray-500 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Update Data</h1>
        </div>

        <div class="bg-gray-50 px-6 py-4">
            <p class="text-xs font-bold text-gray-500 uppercase">Santri</p>
            <h2 class="text-lg font-bold text-gray-800">{{ $uks->santri->full_name }}</h2>
        </div>

        <form action="{{ route('pengurus.uks.update', $uks->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Keluhan Utama</label>
                <textarea name="keluhan" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500" required>{{ old('keluhan', $uks->keluhan) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Tindakan / Obat</label>
                <textarea name="tindakan" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-red-500 focus:border-red-500">{{ old('tindakan', $uks->tindakan) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">Update Status</label>
                <div class="grid grid-cols-2 gap-3">
                    
                    {{-- Opsi Status --}}
                    @foreach([
                        'sakit_ringan' => ['color' => 'yellow', 'label' => 'Sakit Ringan'],
                        'dirawat_di_asrama' => ['color' => 'orange', 'label' => 'Rawat Asrama'],
                        'rujuk_rs' => ['color' => 'red', 'label' => 'Rujuk RS'],
                        'sembuh' => ['color' => 'green', 'label' => 'Sembuh']
                    ] as $val => $opt)
                        <label class="cursor-pointer group">
                            <input type="radio" name="status" value="{{ $val }}" class="peer sr-only" {{ $uks->status == $val ? 'checked' : '' }}>
                            <div class="p-3 rounded-xl border border-gray-200 bg-white peer-checked:bg-{{ $opt['color'] }}-50 peer-checked:border-{{ $opt['color'] }}-500 text-center transition relative overflow-hidden">
                                <span class="block font-bold text-gray-600 peer-checked:text-{{ $opt['color'] }}-700 text-sm">{{ $opt['label'] }}</span>
                                {{-- Indikator Centang --}}
                                <div class="absolute top-2 right-2 hidden peer-checked:block">
                                    <svg class="w-4 h-4 text-{{ $opt['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        </label>
                    @endforeach

                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-200 active:scale-95 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</x-app-layout>