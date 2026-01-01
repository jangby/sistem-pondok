<x-app-layout>
    <x-slot name="navigation">
        @include('layouts.petugas-nav')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 1. HEADER STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-xs uppercase font-bold">Total Pendaftar</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-xs uppercase font-bold">Input Hari Ini</p>
                    <p class="text-3xl font-bold text-emerald-600">{{ $stats['today'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-xs uppercase font-bold">Jenjang SMP</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['smp'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-xs uppercase font-bold">Jenjang SMA</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $stats['sma'] }}</p>
                </div>
            </div>

            {{-- 2. TABEL MANAJEMEN --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                
                {{-- Toolbar: Search & Add Button --}}
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-bold text-gray-800">Data Pendaftar Offline</h3>
                    
                    <div class="flex gap-3 w-full md:w-auto">
                        <form action="{{ route('petugas.dashboard') }}" method="GET" class="flex-1 md:w-64">
                            <input type="text" name="search" placeholder="Cari Nama / NIK..." value="{{ request('search') }}" 
                                class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        </form>
                        
                        <a href="{{ route('petugas.pendaftaran.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Input Baru
                        </a>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">No. Daftar</th>
                                <th class="px-6 py-3">Nama Lengkap</th>
                                <th class="px-6 py-3">Jenjang</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Tgl Daftar</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($santris as $santri)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono font-bold text-gray-700">
                                    {{ $santri->no_pendaftaran }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $santri->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-400">NIK: {{ $santri->nik }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border border-blue-200">
                                        {{ $santri->jenjang }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($santri->status_pendaftaran == 'diterima')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Diterima</span>
                                    @elseif($santri->status_pendaftaran == 'menunggu_verifikasi')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Verifikasi</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $santri->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('petugas.pendaftaran.edit', $santri->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg" title="Edit Biodata">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        {{-- Tombol Cetak Kartu --}}
                                        <a href="{{ route('petugas.pendaftaran.success', $santri->id) }}" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-lg" title="Cetak Kartu Login">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    Tidak ada data pendaftar yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 border-t border-gray-100">
                    {{ $santris->withQueryString()->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>