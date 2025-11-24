<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Pengembalian Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Area Scan --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Scan Barcode Buku</h3>
                    
                    <form action="{{ route('sekolah.superadmin.perpustakaan.sirkulasi.kembali.index') }}" method="GET" class="flex gap-4">
                        <div class="flex-1">
                            <x-text-input name="kode_buku" class="w-full text-lg" placeholder="Scan barcode buku di sini..." autofocus required autocomplete="off" />
                        </div>
                        <x-primary-button class="py-3 px-6 text-lg">
                            {{ __('Cari') }}
                        </x-primary-button>
                    </form>
                    <p class="text-sm text-gray-500 mt-2">Pastikan kursor aktif di kolom input sebelum melakukan scan.</p>
                </div>
            </div>

            {{-- Hasil Pencarian --}}
            @if(isset($peminjaman) && $peminjaman)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 shadow-sm animate-pulse-once">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-lg font-bold text-green-800 mb-1">Data Peminjaman Ditemukan!</h4>
                        <p class="text-green-700">Buku ini sedang dipinjam. Lanjutkan proses untuk mengembalikan.</p>
                        
                        <div class="mt-4 grid grid-cols-2 gap-x-12 gap-y-2 text-sm">
                            <div>
                                <span class="text-gray-500 block">Judul Buku:</span>
                                <span class="font-semibold text-gray-900 text-base">{{ $peminjaman->buku->judul }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Peminjam:</span>
                                <span class="font-semibold text-gray-900 text-base">{{ $peminjaman->santri->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Kode Buku:</span>
                                <span class="font-mono text-gray-700">{{ $peminjaman->buku->kode_buku }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Tgl Wajib Kembali:</span>
                                <span class="font-semibold {{ \Carbon\Carbon::now()->gt($peminjaman->tgl_wajib_kembali) ? 'text-red-600' : 'text-gray-700' }}">
                                    {{ $peminjaman->tgl_wajib_kembali->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('sekolah.superadmin.perpustakaan.sirkulasi.kembali.form', $peminjaman->id) }}" 
                       class="bg-blue-600 text-white font-bold py-3 px-6 rounded-md shadow hover:bg-blue-700 transition flex items-center gap-2">
                        <span>✅</span> PROSES PENGEMBALIAN
                    </a>
                </div>
            </div>
            @elseif(request()->has('kode_buku'))
                {{-- Jika tidak ditemukan (sudah dihandle session flash di controller, tapi bisa tambah UI disini jika mau) --}}
                {{-- Alert error sudah muncul otomatis dari layout app.blade.php --}}
            @endif

        </div>
    </div>
</x-app-layout>