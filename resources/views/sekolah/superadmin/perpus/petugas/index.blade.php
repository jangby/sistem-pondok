<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Petugas Perpustakaan') }}
            </h2>
            <a href="{{ route('sekolah.superadmin.perpustakaan.petugas.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-bold">
                + Tambah Petugas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Nama Petugas</th>
                                <th class="px-6 py-3">Email (Login)</th>
                                <th class="px-6 py-3">Terdaftar Sejak</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($petugas as $p)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $p->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $p->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $p->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                                        <a href="{{ route('sekolah.superadmin.perpustakaan.petugas.edit', $p->id) }}" class="text-blue-600 hover:text-blue-900 font-bold">Edit</a>
                                        
                                        <form action="{{ route('sekolah.superadmin.perpustakaan.petugas.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus petugas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">Belum ada data petugas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $petugas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>