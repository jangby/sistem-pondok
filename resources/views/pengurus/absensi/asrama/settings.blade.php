<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- HEADER MOBILE (EMERALD) --}}
        <div class="bg-emerald-600 pt-6 pb-24 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            {{-- Dekorasi --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center gap-4">
                <a href="{{ route('pengurus.absensi.asrama') }}" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition active:scale-95">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Pengaturan Asrama</h1>
                    <p class="text-emerald-100 text-xs font-medium">Jam Absen & Hari Libur</p>
                </div>
            </div>
        </div>

        {{-- CONTENT CONTAINER (Floating Up) --}}
        <div class="px-5 -mt-16 relative z-20 space-y-6">
            
            {{-- 1. JAM ABSENSI --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-50">
                    <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    Waktu Absensi
                </h3>

                <form action="{{ route('pengurus.absensi.asrama.settings.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    {{-- Pagi --}}
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase block mb-2 tracking-wider">Sesi Pagi</label>
                        <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                            <input type="time" name="pagi_mulai" value="{{ $pagi->jam_mulai ?? '05:00' }}" class="bg-white border-0 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-emerald-500 w-full text-center shadow-sm">
                            <span class="text-gray-400 font-bold">-</span>
                            <input type="time" name="pagi_selesai" value="{{ $pagi->jam_selesai ?? '07:00' }}" class="bg-white border-0 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-emerald-500 w-full text-center shadow-sm">
                        </div>
                    </div>

                    {{-- Malam --}}
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase block mb-2 tracking-wider">Sesi Malam</label>
                        <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                            <input type="time" name="malam_mulai" value="{{ $malam->jam_mulai ?? '18:00' }}" class="bg-white border-0 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-emerald-500 w-full text-center shadow-sm">
                            <span class="text-gray-400 font-bold">-</span>
                            <input type="time" name="malam_selesai" value="{{ $malam->jam_selesai ?? '21:00' }}" class="bg-white border-0 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-emerald-500 w-full text-center shadow-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white py-3.5 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-200 active:scale-95 transition hover:bg-emerald-700">
                        Simpan Perubahan Jam
                    </button>
                </form>
            </div>

            {{-- 2. HARI LIBUR --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-50">
                    <div class="p-2 bg-red-50 rounded-lg text-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    Jadwal Libur
                </h3>

                {{-- Form Tambah Libur --}}
                <form action="{{ route('pengurus.absensi.asrama.libur.store') }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex flex-col gap-3">
                        <div class="flex gap-2">
                            <input type="date" name="tanggal" class="w-1/3 rounded-xl border-gray-200 text-xs focus:ring-red-500 focus:border-red-500" required>
                            <input type="text" name="keterangan" placeholder="Keterangan (Cth: Maulid)" class="w-2/3 rounded-xl border-gray-200 text-xs focus:ring-red-500 focus:border-red-500" required>
                        </div>
                        <button type="submit" class="w-full bg-red-50 text-red-600 py-2.5 rounded-xl font-bold text-xs border border-red-100 hover:bg-red-100 transition active:scale-95">
                            + Tambah Hari Libur
                        </button>
                    </div>
                </form>

                {{-- List Libur --}}
                <div class="space-y-2 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                    @forelse($libur as $l)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-2xl border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="text-center bg-white px-2 py-1 rounded-lg border border-gray-200 shadow-sm">
                                    <span class="block text-[10px] text-gray-400 uppercase font-bold">{{ $l->tanggal->format('M') }}</span>
                                    <span class="block text-lg font-bold text-gray-800 leading-none">{{ $l->tanggal->format('d') }}</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">{{ $l->tanggal->format('Y') }}</p>
                                    <p class="font-bold text-gray-700 text-sm truncate max-w-[120px]">{{ $l->keterangan }}</p>
                                </div>
                            </div>
                            
                            <form action="{{ route('pengurus.absensi.asrama.libur.delete', $l->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="bg-white p-2 rounded-xl text-red-400 hover:text-red-600 border border-gray-200 shadow-sm active:scale-90 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-400 text-xs italic">
                            Belum ada jadwal libur.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>