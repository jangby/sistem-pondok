<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Atur Rincian Biaya: {{ $setting->nama_gelombang }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Form Tambah Biaya --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">Tambah Komponen Biaya</h3>
                <form action="{{ route('adminpondok.ppdb.setting.biaya.store', $setting->id) }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenjang</label>
                        <select name="jenjang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="TAKHOSUS">TAKHOSUS</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Nama Biaya</label>
                        <input type="text" name="nama_biaya" placeholder="Contoh: Seragam, Gedung" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
                        <input type="number" name="nominal" placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-500 font-bold">
                        + Tambah
                    </button>
                </form>
            </div>

            {{-- List Biaya per Jenjang --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(['SMP', 'SMA', 'TAKHOSUS'] as $jenjang)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-gray-50 border-b font-bold text-center text-gray-700">
                        Biaya {{ $jenjang }}
                    </div>
                    <div class="p-4">
                        <ul class="space-y-3">
                            @php 
                                $biayas = $setting->biayas->where('jenjang', $jenjang); 
                                $total = 0;
                            @endphp
                            
                            @forelse($biayas as $biaya)
                                @php $total += $biaya->nominal; @endphp
                                <li class="flex justify-between items-center text-sm border-b pb-2">
                                    <span>{{ $biaya->nama_biaya }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono">Rp {{ number_format($biaya->nominal, 0, ',', '.') }}</span>
                                        <form action="{{ route('adminpondok.ppdb.biaya.destroy', $biaya->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-400 text-sm text-center italic">Belum ada rincian.</li>
                            @endforelse
                        </ul>
                        <div class="mt-4 pt-4 border-t border-dashed flex justify-between font-bold text-emerald-700">
                            <span>Total:</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                <a href="{{ route('adminpondok.ppdb.setting.index') }}" class="text-gray-600 hover:underline">&larr; Kembali ke Daftar Gelombang</a>
            </div>
        </div>
    </div>
</x-app-layout>