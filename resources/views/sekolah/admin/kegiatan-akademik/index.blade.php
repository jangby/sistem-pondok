<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Jadwal Ujian / Kegiatan Akademik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-4 text-right">
                        @if($tahunAjaranAktif)
                            <a href="{{ route('sekolah.admin.kegiatan-akademik.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('+ Tambah Jadwal Ujian') }}
                            </a>
                        @else
                            <span class="text-sm text-red-600">Silakan minta Super Admin Sekolah untuk mengaktifkan Tahun Ajaran terlebih dahulu.</span>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($kegiatans as $kegiatan)
                                    @php
                                        $today = now()->startOfDay();
                                        $tglMulai = \Carbon\Carbon::parse($kegiatan->tanggal_mulai);
                                        $tglSelesai = \Carbon\Carbon::parse($kegiatan->tanggal_selesai);
                                        
                                        $status = '';
                                        $statusClass = '';
                                        
                                        if ($today->lt($tglMulai)) {
                                            $status = 'Akan Datang';
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                        } elseif ($today->gt($tglSelesai)) {
                                            $status = 'Selesai';
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                        } else {
                                            $status = 'Berlangsung';
                                            $statusClass = 'bg-green-100 text-green-800';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $kegiatan->nama_kegiatan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $kegiatan->tipe }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $tglMulai->format('d M Y') }} - {{ $tglSelesai->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('sekolah.admin.kegiatan-akademik.edit', $kegiatan->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            
                                            <form action="{{ route('sekolah.admin.kegiatan-akademik.destroy', $kegiatan->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Anda yakin ingin menghapus jadwal kegiatan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada data Jadwal Ujian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $kegiatans->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>