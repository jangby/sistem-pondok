<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        {{-- Simple Header --}}
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex items-center gap-4 shadow-sm">
            <a href="{{ route('orangtua.monitoring.index', $santri->id) }}" class="text-gray-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            <h1 class="font-bold text-lg text-gray-800">Riwayat Absensi</h1>
        </div>

        {{-- Filter Kategori --}}
        <div class="p-4 overflow-x-auto whitespace-nowrap no-scrollbar">
            <div class="flex gap-2">
                <a href="?kategori=jamaah" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $kategori == 'jamaah' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-gray-500 border border-gray-200' }}">Sholat</a>
                <a href="?kategori=asrama" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $kategori == 'asrama' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-gray-500 border border-gray-200' }}">Asrama</a>
                <a href="?kategori=kegiatan" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $kategori == 'kegiatan' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-gray-500 border border-gray-200' }}">Kegiatan</a>
            </div>
        </div>

        {{-- List Data --}}
        <div class="px-5 space-y-3">
            @forelse($history as $h)
                <div class="bg-white p-4 rounded-2xl border border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">{{ $h->nama_kegiatan }}</h3>
                        <p class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($h->waktu_catat)->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="bg-green-100 text-green-700 text-[10px] px-2 py-1 rounded font-bold uppercase">{{ $h->status }}</span>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400 text-sm bg-white rounded-2xl border-dashed border border-gray-200 mt-2">
                    Belum ada data absensi di kategori ini.
                </div>
            @endforelse

            <div class="mt-4">{{ $history->withQueryString()->links('pagination::tailwind') }}</div>
        </div>
    </div>
</x-app-layout>