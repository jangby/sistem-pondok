<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- PERBAIKAN: pb-40 agar konten paling bawah tidak tertutup menu navigasi --}}
    <div class="min-h-screen bg-gray-50 pb-40" x-data="{ showAdd: false }">
        
        {{-- Header --}}
        <div class="bg-emerald-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-1">
                <h1 class="text-xl font-bold text-white">Asrama {{ $gender }}</h1>
                <a href="{{ route('pengurus.asrama.index') }}" class="bg-white/20 p-2 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
            <p class="text-emerald-100 text-xs">Daftar gedung asrama aktif.</p>
        </div>

        {{-- List Card --}}
        <div class="px-5 -mt-8 relative z-20 space-y-3">
            @forelse($asramas as $a)
                <a href="{{ route('pengurus.asrama.show', $a->id) }}" class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-emerald-200">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">{{ $a->nama_asrama }}</h3>
                            <p class="text-xs text-gray-500">{{ $a->komplek }} • Ketua: {{ $a->ketua_asrama }}</p>
                        </div>
                        <span class="bg-emerald-50 text-emerald-700 text-[10px] px-2 py-1 rounded-lg font-bold uppercase border border-emerald-100">
                            {{ $a->jenis_kelamin == 'Laki-laki' ? 'Putra' : 'Putri' }}
                        </span>
                    </div>
                    
                    {{-- Progress Bar --}}
                    <div class="mt-3">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-400 font-medium">Terisi</span>
                            <span class="font-bold {{ $a->penghuni_count >= $a->kapasitas ? 'text-red-500' : 'text-emerald-600' }}">
                                {{ $a->penghuni_count }} / {{ $a->kapasitas }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="{{ $a->penghuni_count >= $a->kapasitas ? 'bg-red-500' : 'bg-emerald-500' }} h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ ($a->penghuni_count / $a->kapasitas) * 100 }}%"></div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-12 text-gray-400 text-sm bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="mb-1">Belum ada asrama {{ $gender }}.</p>
                    <p class="text-xs text-gray-300">Tap tombol tambah di bawah.</p>
                </div>
            @endforelse
        </div>

        {{-- FAB TAMBAH (MELAYANG DI POJOK KANAN BAWAH) --}}
        <button @click="showAdd = true" class="fixed bottom-24 right-6 bg-emerald-600 text-white w-14 h-14 rounded-full shadow-2xl shadow-emerald-400/50 flex items-center justify-center hover:bg-emerald-700 hover:scale-110 active:scale-90 transition-all duration-300 z-40 border-[3px] border-white/20">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        </button>

        {{-- MODAL TAMBAH (Z-INDEX TINGGI z-[60]) --}}
        <div x-show="showAdd" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showAdd = false"></div>
            
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 p-6 shadow-2xl transform transition-transform pb-10" {{-- pb-10 biar tombol simpan aman --}}
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div class="w-full flex justify-center pt-0 pb-4 cursor-pointer" @click="showAdd = false">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                <h3 class="font-bold text-lg mb-4 text-gray-800">Buat Asrama Baru</h3>
                <form action="{{ route('pengurus.asrama.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="jenis_kelamin" value="{{ $jkDb }}">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Nama Asrama</label>
                        <input type="text" name="nama_asrama" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="Cth: Al-Farabi 1" required>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Komplek</label>
                            <input type="text" name="komplek" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="Cth: Blok A" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Kapasitas</label>
                            <input type="number" name="kapasitas" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="20" required>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Ketua Asrama (Teks)</label>
                        <input type="text" name="ketua_asrama" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="Nama Ketua sementara..." required>
                        <p class="text-[10px] text-gray-400 mt-1">Anda bisa memilih santri sebagai ketua nanti di menu Settings.</p>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl mt-2 shadow-lg shadow-emerald-200 active:scale-95 transition">Simpan Asrama</button>
                </form>
            </div>
        </div>

    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>