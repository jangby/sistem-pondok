<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Petugas PPDB (Offline)') }}
            </h2>
            <a href="{{ route('adminpondok.ppdb.petugas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold">
                + Tambah Petugas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Nama Petugas</th>
                                <th class="px-6 py-3">Email Login</th>
                                <th class="px-6 py-3">No. HP</th>
                                <th class="px-6 py-3">Tanggal Dibuat</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($petugas as $p)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $p->name }}</td>
                                <td class="px-6 py-4">{{ $p->email }}</td>
                                <td class="px-6 py-4">{{ $p->telepon }}</td>
                                <td class="px-6 py-4">{{ $p->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('adminpondok.ppdb.petugas.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus akses petugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-400">Belum ada petugas yang didaftarkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>