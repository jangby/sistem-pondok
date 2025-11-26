<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>
    <div class="min-h-screen bg-gray-50 pb-20 font-sans">
        
        {{-- HEADER MERAH/ORANYE --}}
        <div class="bg-orange-500 px-6 pt-8 pb-10 rounded-b-[30px] shadow-lg">
            <div class="flex items-center gap-4">
                <a href="{{ route('sekolah.petugas.dashboard') }}" class="text-white hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-white">Cek Stok (Audit)</h1>
            </div>
        </div>

        {{-- SCANNER AREA --}}
        <div class="px-6 -mt-6">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 text-center">
                <form method="GET">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Scan Barcode Buku</label>
                    <input type="text" name="kode_buku" value="{{ request('kode_buku') }}" class="w-full text-center text-lg font-mono rounded-xl border-gray-200 bg-gray-50 mb-3" placeholder="||||||||||||" autofocus>
                    <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-xl font-bold text-sm">Cari Buku</button>
                </form>
            </div>
        </div>

        {{-- HASIL PENCARIAN & UPDATE --}}
        @if(isset($buku))
        <div class="px-6 mt-6">
            <div class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm">
                <h3 class="font-bold text-lg text-gray-800">{{ $buku->judul }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $buku->kode_buku }}</p>

                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl mb-4">
                    <span class="text-xs font-bold text-gray-500 uppercase">Stok Sistem</span>
                    <span class="text-xl font-bold text-gray-800">{{ $buku->stok }}</span>
                </div>

                <form action="{{ route('sekolah.petugas.audit.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Stok Fisik (Nyata)</label>
                    <input type="number" name="stok_fisik" value="{{ $buku->stok }}" class="w-full text-center text-2xl font-bold text-orange-600 rounded-xl border-orange-200 focus:ring-orange-500 mb-4">
                    
                    <button type="submit" class="w-full py-3 bg-gray-800 text-white font-bold rounded-xl hover:bg-gray-900 transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
        @elseif(request('kode_buku'))
             <div class="px-6 mt-6 text-center text-red-500 font-medium">Buku tidak ditemukan!</div>
        @endif
    </div>
</x-app-layout>