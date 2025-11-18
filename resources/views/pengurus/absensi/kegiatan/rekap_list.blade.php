<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- Header --}}
        <div class="bg-emerald-600 pt-8 pb-16 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-white tracking-tight">Laporan Kegiatan</h1>
                <a href="{{ route('pengurus.absensi.kegiatan') }}" class="bg-white/20 p-2 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
            <p class="text-emerald-100 text-sm">Pilih kegiatan untuk melihat detail kehadiran.</p>
        </div>

        {{-- List Kegiatan --}}
        <div class="px-5 -mt-8 relative z-20 space-y-3">
            @forelse($kegiatans as $k)
                <a href="{{ route('pengurus.absensi.kegiatan.rekap.show', $k->id) }}" class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition group">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center font-bold text-lg group-hover:bg-orange-600 group-hover:text-white transition">
                                {{ substr($k->nama_kegiatan, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-[15px]">{{ $k->nama_kegiatan }}</h3>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <span class="bg-gray-100 px-1.5 py-0.5 rounded uppercase">{{ $k->frekuensi }}</span>
                                    <span>{{ \Carbon\Carbon::parse($k->jam_mulai)->format('H:i') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-gray-300 group-hover:text-orange-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-12 bg-white rounded-3xl border border-dashed border-gray-200">
                    <p class="text-gray-400 text-xs">Belum ada kegiatan dibuat.</p>
                </div>
            @endforelse
        </div>
    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>