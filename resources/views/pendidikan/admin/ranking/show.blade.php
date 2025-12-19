<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ranking Santri - Kelas {{ $mustawa->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Informasi Rumus --}}
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Informasi Penilaian</p>
                        <p>Skor Akhir dihitung dari: <strong>40% Akademik + 30% Kedisiplinan + 20% Sikap + 10% Keterampilan</strong></p>
                    </div>

                    {{-- Tombol Kembali --}}
                    <div class="mb-4">
                        <a href="{{ route('pendidikan.admin.ranking.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            &larr; Kembali Pilih Kelas
                        </a>
                    </div>

                    {{-- Tabel Ranking --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 border-b text-center w-16">Rank</th>
                                    <th class="py-3 px-4 border-b text-left">Nama Santri</th>
                                    <th class="py-3 px-4 border-b text-center text-sm">Akademik<br><span class="text-xs text-gray-500">(40%)</span></th>
                                    <th class="py-3 px-4 border-b text-center text-sm">Disiplin<br><span class="text-xs text-gray-500">(30%)</span></th>
                                    <th class="py-3 px-4 border-b text-center text-sm">Sikap<br><span class="text-xs text-gray-500">(20%)</span></th>
                                    <th class="py-3 px-4 border-b text-center text-sm">Skill<br><span class="text-xs text-gray-500">(10%)</span></th>
                                    <th class="py-3 px-4 border-b text-center bg-yellow-100 font-bold">SKOR AKHIR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rankingData as $index => $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 border-b text-center font-bold text-lg">
                                        @if($index == 0) 🥇 1
                                        @elseif($index == 1) 🥈 2
                                        @elseif($index == 2) 🥉 3
                                        @else {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 border-b">
                                        <div class="font-medium text-gray-900">{{ $data['nama'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $data['nis'] }}</div>
                                    </td>
                                    <td class="py-3 px-4 border-b text-center">{{ $data['akademik'] }}</td>
                                    <td class="py-3 px-4 border-b text-center">{{ $data['disiplin'] }}</td>
                                    <td class="py-3 px-4 border-b text-center">{{ $data['sikap'] }}</td>
                                    <td class="py-3 px-4 border-b text-center">{{ $data['skill'] }}</td>
                                    <td class="py-3 px-4 border-b text-center font-bold text-lg bg-yellow-50 text-indigo-700">
                                        {{ $data['total'] }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-4 px-4 border-b text-center text-gray-500">
                                        Belum ada data nilai untuk kelas ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>