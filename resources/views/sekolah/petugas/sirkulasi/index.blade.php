<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>
    
    <div x-data="{ tab: '{{ $errors->any() || session('error') || session('success') ? 'input' : 'list' }}' }" class="min-h-screen bg-gray-50 pb-20 font-sans">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 px-6 pt-8 pb-6 rounded-b-[30px] shadow-lg">
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('sekolah.petugas.dashboard') }}" class="text-white hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-white">Sirkulasi</h1>
            </div>

            {{-- BOX PENCARIAN PENGEMBALIAN (BARU) --}}
            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl mb-6 border border-white/20">
                <h3 class="text-xs font-bold text-white uppercase mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Scan Pengembalian Buku
                </h3>
                <form action="{{ route('sekolah.petugas.sirkulasi.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="scan_kode_buku" class="w-full pl-10 pr-12 py-2.5 rounded-xl border-none focus:ring-2 focus:ring-emerald-300 text-sm text-gray-800 shadow-inner" placeholder="Scan barcode buku disini..." autocomplete="off" autofocus>
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8V4h4V8H6zM6 20v-4h4v4H6zM16 8V4h4V8h-4zM6 12h12"></path></svg>
                        </div>
                        <button type="submit" class="absolute right-1.5 top-1.5 bg-emerald-500 text-white p-1.5 rounded-lg hover:bg-emerald-600 transition shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="flex bg-emerald-700/50 p-1 rounded-xl">
                <button @click="tab = 'list'" :class="tab === 'list' ? 'bg-white text-emerald-700 shadow-sm' : 'text-emerald-100 hover:bg-white/10'" class="flex-1 py-2 rounded-lg text-sm font-bold transition-all">Sedang Dipinjam</button>
                <button @click="tab = 'input'" :class="tab === 'input' ? 'bg-white text-emerald-700 shadow-sm' : 'text-emerald-100 hover:bg-white/10'" class="flex-1 py-2 rounded-lg text-sm font-bold transition-all">Peminjaman Baru</button>
            </div>
        </div>

        {{-- NOTIFIKASI --}}
        <div class="px-6 mt-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-2 text-sm shadow-sm">
                    <strong class="font-bold">Sukses!</strong> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-2 text-sm shadow-sm">
                    <strong class="font-bold">Gagal!</strong> {{ session('error') }}
                </div>
            @endif
            {{-- Error lainnya tetap sama --}}
        </div>

        {{-- KONTEN TAB LIST --}}
        <div x-show="tab === 'list'" class="px-6 mt-2 space-y-4">
            @forelse($peminjaman as $item)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-gray-800">
                                {{ $item->santri->full_name ?? 'Nama Tidak Dikenal' }}
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                NIS: <span class="font-mono text-gray-700">{{ $item->santri->nis ?? '-' }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mb-2">
                                Buku: <span class="text-emerald-600 font-medium">{{ $item->buku->judul ?? 'Buku Terhapus' }}</span>
                            </p>
                            <span class="text-[10px] bg-yellow-50 text-yellow-600 px-2 py-1 rounded-md border border-yellow-100">
                                Tenggat: {{ \Carbon\Carbon::parse($item->tgl_wajib_kembali)->format('d M Y') }}
                            </span>
                        </div>
                        
                        {{-- TOMBOL PENGEMBALIAN (Link ke Form) --}}
                        <a href="{{ route('sekolah.petugas.sirkulasi.kembali.form', $item->id) }}" class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-lg text-xs font-bold border border-emerald-100 hover:bg-emerald-600 hover:text-white transition">
                            Kembalikan
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400">
                    <p class="text-sm">Tidak ada buku yang sedang dipinjam.</p>
                </div>
            @endforelse
            
            <div class="pb-10">
                {{ $peminjaman->links() }}
            </div>
        </div>

        {{-- KONTEN TAB INPUT (Sama seperti sebelumnya) --}}
        <div x-show="tab === 'input'" style="display: none;" class="px-6 mt-6">
            {{-- ... form input peminjaman ... --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <form action="{{ route('sekolah.petugas.sirkulasi.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">NIS Santri</label>
                        <input type="text" name="identitas_peminjam" class="w-full pl-4 pr-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: 2024001" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kode Barcode Buku</label>
                        <input type="text" name="kode_buku" class="w-full pl-4 pr-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Scan barcode..." required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Durasi Pinjam (Hari)</label>
                        <input type="number" name="durasi" value="7" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl shadow-lg hover:bg-emerald-700 transition">
                        Proses Peminjaman
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>