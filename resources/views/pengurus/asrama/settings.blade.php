<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- PERBAIKAN: Padding Bawah Besar --}}
    <div class="min-h-screen bg-gray-50 pb-40">
        
        {{-- Header --}}
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex items-center gap-4 shadow-sm">
            <a href="{{ route('pengurus.asrama.show', $asrama->id) }}" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="font-bold text-lg text-gray-800">Pengaturan Asrama</h1>
        </div>

        <div class="p-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                
                <form action="{{ route('pengurus.asrama.update', $asrama->id) }}" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Nama Asrama</label>
                        <input type="text" name="nama_asrama" value="{{ $asrama->nama_asrama }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                    </div>

                    {{-- PERBAIKAN: DROPDOWN KETUA ASRAMA --}}
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Ketua Asrama (Pilih Santri)</label>
                        <div class="relative">
                            <select name="ketua_asrama" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 appearance-none">
                                <option value="{{ $asrama->ketua_asrama }}">{{ $asrama->ketua_asrama }} (Saat Ini)</option>
                                <option disabled>--- Pilih Baru ---</option>
                                @foreach($calonKetua as $santri)
                                    <option value="{{ $santri->full_name }}">{{ $santri->full_name }} ({{ $santri->kelas->nama_kelas ?? '-' }})</option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-3 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Komplek</label>
                            <input type="text" name="komplek" value="{{ $asrama->komplek }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Kapasitas</label>
                            <input type="number" name="kapasitas" value="{{ $asrama->kapasitas }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 active:scale-95 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

                {{-- Zona Bahaya --}}
                <div class="mt-10 pt-6 border-t border-dashed border-gray-200">
                    <h3 class="text-xs font-bold text-red-500 uppercase mb-3">Zona Bahaya</h3>
                    <form action="{{ route('pengurus.asrama.destroy', $asrama->id) }}" method="POST" onsubmit="return confirm('Yakin hapus asrama ini? Data santri di dalamnya akan dikeluarkan (tidak terhapus).')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-red-50 text-red-600 border border-red-100 font-bold py-3 rounded-xl hover:bg-red-100 transition active:scale-95">
                            Hapus Asrama
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>