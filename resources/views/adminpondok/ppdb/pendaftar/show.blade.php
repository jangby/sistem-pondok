<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pendaftar: {{ $calon->full_name }}</h2>
            <div class="space-x-2">
                @if($calon->status_pendaftaran != 'diterima')
                    <form action="{{ route('adminpondok.ppdb.pendaftar.approve', $calon->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin terima santri ini? Data akan dipindahkan ke database utama.')">
                        @csrf
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md font-bold hover:bg-emerald-500 shadow">
                            ✓ TERIMA SANTRI
                        </button>
                    </form>
                    <form action="{{ route('adminpondok.ppdb.pendaftar.reject', $calon->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak pendaftaran?')">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md font-bold hover:bg-red-500 shadow">
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
            <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-700">Status Pembayaran</h3>
                    <p class="text-sm text-gray-500">Total Tagihan: Rp {{ number_format($calon->total_biaya, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Sudah Bayar: <span class="font-bold text-gray-800">Rp {{ number_format($calon->total_sudah_bayar, 0, ',', '.') }}</span></p>
                </div>
                <div>
                    @if($calon->status_pembayaran == 'lunas')
                        <span class="text-xl font-bold text-emerald-600 border-2 border-emerald-600 px-4 py-1 rounded uppercase">LUNAS</span>
                    @else
                        <form action="{{ route('adminpondok.ppdb.pendaftar.payment.confirm', $calon->id) }}" method="POST" onsubmit="return confirm('Konfirmasi lunas manual?')">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 text-sm">
                                Konfirmasi Lunas Manual
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- 2. BIODATA & BERKAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Data Diri --}}
                <div class="md:col-span-2 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold border-b pb-2 mb-4">Biodata Santri</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-500">NIK:</span><br>{{ $calon->nik }}</div>
                        <div><span class="text-gray-500">KK:</span><br>{{ $calon->no_kk }}</div>
                        <div><span class="text-gray-500">TTL:</span><br>{{ $calon->tempat_lahir }}, {{ $calon->tanggal_lahir->format('d-m-Y') }}</div>
                        <div><span class="text-gray-500">Alamat:</span><br>{{ $calon->alamat }}, {{ $calon->desa }}, {{ $calon->kecamatan }}</div>
                        <div class="col-span-2"><span class="text-gray-500">Sekolah Asal:</span><br>{{ $calon->sekolah_asal }}</div>
                        <div class="col-span-2 mt-2 font-bold border-t pt-2">Data Orang Tua</div>
                        <div><span class="text-gray-500">Ayah:</span><br>{{ $calon->nama_ayah }} ({{ $calon->no_hp_ayah }})</div>
                        <div><span class="text-gray-500">Ibu:</span><br>{{ $calon->nama_ibu }} ({{ $calon->no_hp_ibu }})</div>
                    </div>
                </div>

                {{-- Berkas PDF --}}
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold border-b pb-2 mb-4">Berkas Upload</h3>
                    <ul class="space-y-3 text-sm">
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
                            <li class="flex justify-between items-center">
                                <span>{{ $label }}</span>
                                @if($calon->$field)
                                    <a href="{{ asset('storage/' . $calon->$field) }}" target="_blank" class="text-blue-600 hover:underline font-bold">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-red-400 text-xs italic">Belum ada</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>