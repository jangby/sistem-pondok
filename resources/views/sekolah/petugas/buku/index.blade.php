<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>
    <div class="min-h-screen bg-gray-50 pb-20 font-sans">
        
        {{-- HEADER & SEARCH --}}
        <div class="bg-blue-600 px-6 pt-8 pb-10 rounded-b-[30px] shadow-lg sticky top-0 z-30">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('sekolah.petugas.dashboard') }}" class="text-white hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-white">Data Buku</h1>
            </div>
            {{-- Search Bar --}}
            <form>
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari judul atau penulis..." value="{{ request('search') }}" class="w-full pl-10 pr-4 py-3 rounded-xl border-none focus:ring-2 focus:ring-blue-300 shadow-sm bg-white/95 backdrop-blur">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
        </div>

        {{-- LIST BUKU --}}
        <div class="px-6 -mt-0 space-y-3">
            @forelse($bukus as $buku)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex gap-4 items-center">
                    {{-- Icon Buku / Cover --}}
                    <div class="w-16 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-300 shrink-0 overflow-hidden">
                        @if($buku->cover)
                            <img src="{{ asset('storage/'.$buku->cover) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-800 truncate">{{ $buku->judul }}</h3>
                        <p class="text-xs text-gray-500 mb-1">{{ $buku->penulis }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[10px] font-bold">Stok: {{ $buku->stok }}</span>
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-md text-[10px] font-mono">{{ $buku->kode_buku }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400">
                    <p class="text-sm">Buku tidak ditemukan.</p>
                </div>
            @endforelse

            {{-- Pagination Minimalis --}}
            <div class="py-4">
                {{ $bukus->links() }} 
            </div>
        </div>
    </div>
</x-app-layout>