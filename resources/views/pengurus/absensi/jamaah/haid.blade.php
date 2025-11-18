<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24" x-data="{ showAdd: false }">
        
        {{-- Header Pink --}}
        <div class="bg-pink-500 pt-6 pb-6 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('pengurus.absensi.jamaah') }}" class="bg-white/20 p-2 rounded-xl text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
                    <h1 class="text-xl font-bold text-white">Data Haid</h1>
                </div>
                <button @click="showAdd = true" class="bg-white text-pink-600 px-4 py-2 rounded-xl text-xs font-bold shadow-sm active:scale-95 transition">+ Input Baru</button>
            </div>
        </div>

        {{-- List Sedang Haid --}}
        <div class="px-5 mt-6">
            <h3 class="font-bold text-gray-800 mb-3">Sedang Berhalangan</h3>
            
            <div class="space-y-3">
                @forelse($activeHaid as $data)
                    <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-pink-400 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $data->santri->full_name }}</p>
                            <p class="text-[10px] text-gray-400 uppercase">{{ $data->santri->kelas->nama_kelas ?? '-' }}</p>
                            <p class="text-xs text-pink-500 mt-1">Mulai: {{ $data->tgl_mulai->format('d M Y') }}</p>
                        </div>
                        <form action="{{ route('pengurus.absensi.jamaah.haid.finish', $data->id) }}" method="POST" onsubmit="return confirm('Tandai sudah suci/selesai?')">
                            @csrf @method('PUT')
                            <button class="px-3 py-1.5 bg-pink-50 text-pink-600 rounded-lg text-xs font-bold hover:bg-pink-100 transition">
                                Selesai
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-xs bg-white rounded-2xl border border-dashed border-gray-200">
                        Tidak ada santri yang sedang haid.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL TAMBAH --}}
        <div x-show="showAdd" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showAdd = false"></div>
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 p-6 shadow-2xl">
                <h3 class="font-bold text-lg mb-4 text-gray-800">Input Haid Baru</h3>
                
                <form action="{{ route('pengurus.absensi.jamaah.haid.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Santriwati</label>
                        <select name="santri_id" class="w-full rounded-xl border-gray-200 focus:ring-pink-500" required>
                            <option value="">-- Pilih Nama --</option>
                            @foreach($santriPutri as $s)
                                <option value="{{ $s->id }}">{{ $s->full_name }} ({{ $s->kelas->nama_kelas ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan (Opsional)</label>
                        <input type="text" name="catatan" class="w-full rounded-xl border-gray-200 focus:ring-pink-500">
                    </div>
                    <button type="submit" class="w-full bg-pink-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-pink-200">Simpan</button>
                </form>
            </div>
        </div>

    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>