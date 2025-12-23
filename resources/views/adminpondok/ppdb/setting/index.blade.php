<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan PPDB') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tombol Tambah --}}
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Daftar Gelombang Pendaftaran</h3>
                <a href="{{ route('adminpondok.ppdb.setting.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-500 font-bold text-sm shadow">
                    + Buka Gelombang Baru
                </a>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gelombang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($settings as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->tahun_ajaran }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">{{ $item->nama_gelombang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_akhir->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    Rp {{ number_format($item->biaya_pendaftaran, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($item->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif / Dibuka
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('adminpondok.ppdb.setting.biaya', $item->id) }}" class="text-blue-600 hover:text-blue-900 mx-2 font-bold underline">
                                        Atur Biaya
                                    </a>
                                    <form action="{{ route('adminpondok.ppdb.setting.toggle', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 mx-1">
                                            {{ $item->is_active ? 'Tutup' : 'Buka' }}
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('adminpondok.ppdb.setting.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 mx-1">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data gelombang pendaftaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>