<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pendaftar: {{ $calonSantri->nama_lengkap }}</h2>
            <div class="space-x-2">
                @if($calonSantri->status_pendaftaran != 'diterima')
                    {{-- PERBAIKAN ROUTE: adminpondok. (tanpa underscore) --}}
                    <form action="{{ route('adminpondok.ppdb.pendaftar.approve', $calonSantri->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin terima santri ini? Data akan dipindahkan ke database utama.')">
                        @csrf
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md font-bold hover:bg-emerald-500 shadow transition duration-150 ease-in-out">
                            ✓ TERIMA SANTRI
                        </button>
                    </form>
                    <form action="{{ route('adminpondok.ppdb.pendaftar.reject', $calonSantri->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak pendaftaran?')">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md font-bold hover:bg-red-500 shadow transition duration-150 ease-in-out">
                            X TOLAK
                        </button>
                    </form>
                @else
                    <span class="bg-emerald-100 text-emerald-800 px-4 py-2 rounded-md font-bold border border-emerald-300">
                        SUDAH DITERIMA / AKTIF
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- 1. INFO PEMBAYARAN --}}
            <div class="bg-white p-6 rounded-lg shadow flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-700">Status Pembayaran</h3>
                    <p class="text-sm text-gray-500">Total Tagihan: Rp {{ number_format($calonSantri->total_biaya, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Sudah Bayar: <span class="font-bold text-gray-800">Rp {{ number_format($calonSantri->total_sudah_bayar, 0, ',', '.') }}</span></p>
                    <p class="text-sm text-gray-500 mt-1">
                        Sisa: <span class="font-bold {{ $calonSantri->sisa_tagihan > 0 ? 'text-red-600' : 'text-emerald-600' }}">Rp {{ number_format($calonSantri->sisa_tagihan, 0, ',', '.') }}</span>
                    </p>
                </div>
                <div>
                    {{-- TOMBOL KELOLA PEMBAYARAN --}}
                    {{-- Pastikan route ini sudah ditambahkan di routes/admin_pondok.php --}}
                    <a href="{{ route('adminpondok.ppdb.pendaftar.payment', $calonSantri->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:border-emerald-900 focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                       </svg>
                       Kelola Pembayaran / Bayar
                    </a>
                </div>
            </div>

            {{-- 2. BIODATA & BERKAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Data Diri --}}
                <div class="md:col-span-2 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold border-b pb-2 mb-4 text-gray-800">Biodata Santri</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-500 block mb-1">NIK:</span><span class="font-medium">{{ $calonSantri->nik ?? '-' }}</span></div>
                        <div><span class="text-gray-500 block mb-1">KK:</span><span class="font-medium">{{ $calonSantri->no_kk ?? '-' }}</span></div>
                        <div><span class="text-gray-500 block mb-1">TTL:</span><span class="font-medium">{{ $calonSantri->tempat_lahir }}, {{ $calonSantri->tanggal_lahir ? $calonSantri->tanggal_lahir->format('d-m-Y') : '-' }}</span></div>
                        <div>
                            <span class="text-gray-500 block mb-1">Alamat:</span>
                            <span class="font-medium">
                                {{ $calonSantri->alamat }}
                                @if($calonSantri->desa), Ds. {{ $calonSantri->desa }}@endif
                                @if($calonSantri->kecamatan), Kec. {{ $calonSantri->kecamatan }}@endif
                            </span>
                        </div>
                        <div class="md:col-span-2"><span class="text-gray-500 block mb-1">Sekolah Asal:</span><span class="font-medium">{{ $calonSantri->sekolah_asal ?? '-' }}</span></div>
                        
                        <div class="md:col-span-2 mt-4 font-bold border-t pt-2 text-gray-800">Data Orang Tua</div>
                        <div>
                            <span class="text-gray-500 block mb-1">Ayah:</span>
                            <span class="font-medium">{{ $calonSantri->nama_ayah ?? '-' }}</span> 
                            <span class="text-gray-400 text-xs block">{{ $calonSantri->no_hp_ayah ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-1">Ibu:</span>
                            <span class="font-medium">{{ $calonSantri->nama_ibu ?? '-' }}</span>
                            <span class="text-gray-400 text-xs block">{{ $calonSantri->no_hp_ibu ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Berkas PDF --}}
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold border-b pb-2 mb-4 text-gray-800">Berkas Upload</h3>
                    <ul class="space-y-4 text-sm">
                        @php
                            $files = [
                                'foto_santri' => 'Pas Foto',
                                'file_kk' => 'Kartu Keluarga',
                                'file_akta' => 'Akta Kelahiran',
                                'file_ijazah' => 'Ijazah',
                                'file_skl' => 'SKL',
                                'file_kip' => 'KIP',
                            ];
                        @endphp

                        @foreach($files as $field => $label)
                            <li class="flex justify-between items-center p-2 hover:bg-gray-50 rounded transition">
                                <span class="text-gray-600">{{ $label }}</span>
                                @if($calonSantri->$field)
                                    <a href="{{ asset('storage/' . $calonSantri->$field) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 px-3 py-1 rounded border border-blue-100 hover:bg-blue-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-red-400 text-xs italic flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Kosong
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>