<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                <form method="GET" class="flex gap-4 items-center">
                    <select name="bulan" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" @selected($i == $bulan)>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                        @endfor
                    </select>
                    <select name="tahun" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        @for($y=now()->year; $y>=2023; $y--)
                            <option value="{{ $y }}" @selected($y == $tahun)>{{ $y }}</option>
                        @endfor
                    </select>
                    <x-primary-button class="bg-teal-600 hover:bg-teal-700">Filter</x-primary-button>
                </form>
                
                <a href="{{ route('sekolah.admin.monitoring.siswa') }}" class="text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ranking Kedisiplinan Siswa (Periode: {{ \Carbon\Carbon::create()->month($bulan)->format('F') }} {{ $tahun }})</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Hadir</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tepat Waktu</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Terlambat</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Skor Disiplin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($laporan as $index => $row)
                                    <tr class="{{ $index < 3 ? 'bg-teal-50' : '' }}">
                                        <td class="px-4 py-3 whitespace-nowrap text-center font-bold text-gray-500">
                                            #{{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $row->nama }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $row->kelas }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-900 font-bold">{{ $row->hadir }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-green-600">{{ $row->tepat_waktu }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-yellow-600">{{ $row->terlambat }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            @php
                                                $color = $row->skor >= 90 ? 'green' : ($row->skor >= 75 ? 'yellow' : 'red');
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                                {{ $row->skor }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>