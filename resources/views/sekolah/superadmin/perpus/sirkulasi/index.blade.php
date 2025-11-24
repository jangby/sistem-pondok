<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sirkulasi & Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">Transaksi Aktif</h3>
                <div class="space-x-2">
                    <a href="{{ route('sekolah.superadmin.perpustakaan.sirkulasi.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 shadow-sm transition">
                        Pinjam Buku
                    </a>
                    <a href="{{ route('sekolah.superadmin.perpustakaan.sirkulasi.kembali.index') }}" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 shadow-sm transition">
                        Kembalikan Buku
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Peminjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Buku</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tgl Pinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Wajib Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($peminjamans as $pinjam)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $pinjam->santri->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $pinjam->santri->kelas->nama_kelas ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $pinjam->buku->judul }}</div>
                                        <div class="text-xs font-mono text-gray-500">{{ $pinjam->buku->kode_buku }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $pinjam->tgl_pinjam->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $isLate = \Carbon\Carbon::now()->gt($pinjam->tgl_wajib_kembali);
                                        @endphp
                                        <span class="{{ $isLate ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                            {{ $pinjam->tgl_wajib_kembali->format('d M Y') }}
                                        </span>
                                        @if($isLate)
                                            <span class="text-xs text-red-500 block">(Terlambat)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Dipinjam</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada buku yang sedang dipinjam.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $peminjamans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>