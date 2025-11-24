<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Buku Perpustakaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Sukses/Error akan muncul otomatis dari AppLayout --}}

            <div class="flex justify-end mb-6">
                <a href="{{ route('sekolah.superadmin.perpustakaan.buku.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 shadow-sm transition">
                    + Tambah Buku
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Form Pencarian --}}
                    <form method="GET" class="mb-6 flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Judul, Penulis, atau Barcode..." class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full md:w-1/3">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Cari</button>
                    </form>

                    {{-- Tabel --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rak</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($bukus as $buku)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ $buku->kode_buku }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $buku->judul }}</div>
                                        <div class="text-xs text-gray-500">{{ $buku->penulis ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Indikator Warna Stok --}}
                                        @if($buku->stok_tersedia > 0)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $buku->stok_tersedia }} / {{ $buku->stok_total }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Habis ({{ $buku->stok_total }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $buku->lokasi_rak ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('sekolah.superadmin.perpustakaan.buku.edit', $buku->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold">Edit</a>
                                        
                                        {{-- Tombol Hapus dengan Form DELETE --}}
                                        <form action="{{ route('sekolah.superadmin.perpustakaan.buku.destroy', $buku->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini? Data yang sudah dihapus tidak dapat dikembalikan.');">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data buku.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $bukus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>