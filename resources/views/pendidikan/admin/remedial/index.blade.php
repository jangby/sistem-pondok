<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Remedial') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- CARD FILTER --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('pendidikan.admin.monitoring.remedial.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            
                            {{-- Input Kelas --}}
                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-1">Kelas (Mustawa)</label>
                                <select name="mustawa_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" onchange="this.form.submit()">
                                    <option value="">- Pilih Kelas -</option>
                                    @foreach($mustawas as $m)
                                        <option value="{{ $m->id }}" {{ request('mustawa_id') == $m->id ? 'selected' : '' }}>
                                            {{ $m->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if(request('mustawa_id'))
                                {{-- Input Mapel --}}
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-1">Mata Pelajaran</label>
                                    <select name="mapel_diniyah_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                                        <option value="">- Pilih Mapel -</option>
                                        @foreach($mapels as $mapel)
                                            <option value="{{ $mapel->id }}" {{ request('mapel_diniyah_id') == $mapel->id ? 'selected' : '' }}>
                                                {{ $mapel->nama_mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Input Kategori --}}
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-1">Kategori Ujian</label>
                                    <select name="kategori" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                                        <option value="tulis" {{ request('kategori') == 'tulis' ? 'selected' : '' }}>Tulis</option>
                                        <option value="lisan" {{ request('kategori') == 'lisan' ? 'selected' : '' }}>Lisan</option>
                                        <option value="praktek" {{ request('kategori') == 'praktek' ? 'selected' : '' }}>Praktek</option>
                                        <option value="hafalan" {{ request('kategori') == 'hafalan' ? 'selected' : '' }}>Hafalan</option>
                                    </select>
                                </div>

                                {{-- Input Semester --}}
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-1">Semester</label>
                                    <div class="flex gap-2">
                                        <select name="semester" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                                            <option value="ganjil" {{ request('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                            <option value="genap" {{ request('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                                        </select>
                                        
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Cari
                                        </button>
                                    </div>
                                </div>

                                <input type="hidden" name="tahun_ajaran" value="{{ request('tahun_ajaran', date('Y') . '/' . (date('Y') + 1)) }}">
                            @endif

                        </div>
                    </form>
                </div>
            </div>

            {{-- CARD HASIL (Hanya muncul jika Mapel sudah dipilih) --}}
            @if($selectedMapel)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                Hasil Pencarian: {{ $selectedMapel->nama_mapel }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                KKM: <span class="font-bold text-red-600">{{ $kkm }}</span> | 
                                Kategori: {{ ucfirst(request('kategori')) }}
                            </p>
                        </div>

                        @if(count($remedialList) > 0)
                            <a href="{{ route('pendidikan.admin.monitoring.remedial.pdf', request()->all()) }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Download PDF
                            </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Santri</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai {{ ucfirst(request('kategori')) }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Defisit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($remedialList as $item)
                                    @php 
                                        $col = 'nilai_' . strtolower(request('kategori'));
                                        $nilai = $item->$col;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->santri->nis }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->santri->full_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold">{{ $nilai }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">- {{ $kkm - $nilai }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <p class="text-lg font-medium text-gray-900">Alhamdulillah!</p>
                                                <p class="text-sm">Tidak ada santri yang remedial pada kategori ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>