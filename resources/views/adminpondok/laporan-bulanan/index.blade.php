<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- JUDUL HALAMAN --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Laporan Pembayaran Bulanan</h2>
                    <p class="text-sm text-gray-500">Pantau status pembayaran SPP dan tagihan rutin lainnya per periode.</p>
                </div>
            </div>

            {{-- KARTU FILTER --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <h3 class="font-semibold text-gray-700">Filter Periode & Kategori</h3>
                </div>
                
                <div class="p-6">
                    <form method="GET" action="{{ route('adminpondok.laporan.bulanan') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            {{-- Baris 1 --}}
                            <div>
                                <x-input-label for="jenis_pembayaran_id" :value="__('Jenis Pembayaran')" />
                                <select name="jenis_pembayaran_id" id="jenis_pembayaran_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach ($jenisPembayarans as $jenis)
                                        <option value="{{ $jenis->id }}" {{ request('jenis_pembayaran_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <x-input-label for="bulan" :value="__('Bulan')" />
                                <select name="bulan" id="bulan" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('bulan', date('n')) == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create(null, $i, 1)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <x-input-label for="tahun" :value="__('Tahun')" />
                                <x-text-input type="number" id="tahun" name="tahun" :value="request('tahun', date('Y'))" class="block mt-1 w-full" required />
                            </div>

                            {{-- Baris 2 --}}
                            <div>
                                <x-input-label for="status_filter" :value="__('Status Bayar')" />
                                <select name="status_filter" id="status_filter" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="lunas" {{ request('status_filter') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="belum_lunas" {{ request('status_filter') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="kelas" :value="__('Kelas')" />
                                <select name="kelas" id="kelas" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Semua Kelas</option>
                                    @foreach($daftarKelas as $kelas)
                                        <option value="{{ $kelas->class }}" {{ request('kelas') == $kelas->class ? 'selected' : '' }}>
                                            {{ $kelas->class }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tombol --}}
                            <div class="flex items-end">
                                <x-primary-button class="w-full justify-center py-2.5">
                                    {{ __('Tampilkan Laporan') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (request('jenis_pembayaran_id'))
                
                {{-- STATS CARDS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Total Santri --}}
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase">Total Santri</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $summary['total_santri'] }}</p>
                    </div>

                    {{-- Lunas --}}
                    <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-emerald-500">
                        <p class="text-xs font-bold text-emerald-600 uppercase">Sudah Lunas</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $summary['total_lunas'] }}</p>
                    </div>

                    {{-- Belum Lunas --}}
                    <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-red-500">
                        <p class="text-xs font-bold text-red-600 uppercase">Belum Lunas</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $summary['total_belum_lunas'] }}</p>
                    </div>

                    {{-- Tunggakan --}}
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase">Potensi Tunggakan</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- TOMBOL DOWNLOAD PDF --}}
                <div class="flex justify-end">
                    <a href="{{ route('adminpondok.laporan.bulanan.pdf', request()->all()) }}" target="_blank" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download PDF
                    </a>
                </div>

                {{-- TABEL DATA --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Santri</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NIS / Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tagihan</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($laporanData as $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $item['santri']->full_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <div class="flex flex-col">
                                                <span>{{ $item['santri']->nis }}</span>
                                                <span class="text-xs text-gray-400">{{ $item['santri']->class ?? '-'}}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($item['nominal_tagihan'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($item['status'] == 'paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    Lunas
                                                </span>
                                            @elseif ($item['status'] == 'pending' || $item['status'] == 'partial')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                                    Belum Lunas
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                                    Belum Ada Tagihan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item['tgl_bayar'] ? $item['tgl_bayar']->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            Tidak ada data santri yang sesuai dengan filter di atas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                {{-- Placeholder State (Belum Filter) --}}
                <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 mb-4 text-emerald-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Silakan Terapkan Filter</h3>
                    <p class="text-gray-500 mt-1 max-w-md mx-auto">Pilih Jenis Pembayaran, Bulan, dan Tahun pada panel di atas untuk melihat laporan keuangan.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>