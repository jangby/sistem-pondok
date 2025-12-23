<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24 font-sans">
        <div class="bg-emerald-600 pt-8 pb-12 px-6 rounded-b-[35px] shadow-lg">
            <div class="flex items-center gap-3 mt-2">
                <a href="{{ route('pengurus.perpulangan.index') }}" class="text-white hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-white">Cetak Kartu</h1>
                    <p class="text-emerald-100 text-xs">{{ $event->judul }}</p>
                </div>
            </div>
        </div>

        <div class="px-5 -mt-6 relative z-10">
            
            <div class="bg-white rounded-2xl shadow-lg p-5 border border-gray-100 mb-4">
                <form action="{{ route('pengurus.perpulangan.pilih-santri', $event->id) }}" method="GET">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Pilih Kelas Pesantren (Mustawa)</label>
                    <div class="flex gap-2">
                        <select name="mustawa_id" class="w-full rounded-xl border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-200" required>
                            <option value="">-- Pilih Mustawa --</option>
                            @foreach($mustawas as $m)
                                <option value="{{ $m->id }}" {{ request('mustawa_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama }} {{-- Sesuaikan kolom nama di DB --}}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-emerald-600 text-white px-4 rounded-xl font-bold shadow-md hover:bg-emerald-700">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            @if(request('mustawa_id') && $santris->count() > 0)
            <form action="{{ route('pengurus.perpulangan.cetak', $event->id) }}" method="POST" target="_blank">
                @csrf
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700">Daftar Santri</h3>
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="checkAll" class="rounded text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-xs text-gray-600">Pilih Semua</span>
                        </label>
                    </div>

                    <div class="divide-y divide-gray-100 max-h-[400px] overflow-y-auto">
                        @foreach($santris as $santri)
                        <label class="flex items-center gap-3 p-4 hover:bg-emerald-50 cursor-pointer transition">
                            <input type="checkbox" name="santri_ids[]" value="{{ $santri->id }}" class="item-checkbox rounded text-emerald-600 focus:ring-emerald-500 w-5 h-5">
                            
                            <div class="flex-1">
                                <p class="font-bold text-gray-800 text-sm">{{ $santri->full_name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $santri->nis }} | 
                                    {{ $santri->mustawa->nama ?? 'Mustawa ?' }} | 
                                    {{ $santri->desa ?? '-' }}
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-100">
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Kartu
                        </button>
                    </div>
                </div>
            </form>
            @elseif(request('mustawa_id'))
                <div class="text-center py-10 bg-white rounded-2xl shadow border border-gray-100">
                    <p class="text-gray-500 text-sm">Tidak ada santri di mustawa ini.</p>
                </div>
            @endif
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.getElementById('checkAll')?.addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
    @endpush
</x-app-layout>