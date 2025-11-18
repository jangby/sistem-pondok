<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER & NAV --}}
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $jenisPembayaran->name }}</h2>
                    <p class="text-sm text-gray-500 capitalize">Tipe: {{ str_replace('_', ' ', $jenisPembayaran->tipe) }}</p>
                </div>
                <a href="{{ route('adminpondok.jenis-pembayarans.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            {{-- INFO CARD --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 flex justify-between items-center bg-emerald-50">
                    <div>
                        <p class="text-sm text-emerald-800 font-medium">Total Nominal Tagihan</p>
                        <h3 class="text-3xl font-bold text-emerald-700">Rp {{ number_format($jenisPembayaran->items->sum('nominal'), 0, ',', '.') }}</h3>
                        <p class="text-xs text-emerald-600 mt-1">Akumulasi dari semua item rincian di bawah.</p>
                    </div>
                    <div class="p-3 bg-white rounded-full text-emerald-500 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- ITEM RINCIAN --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Item Rincian Pembayaran</h3>
                    <a href="{{ route('adminpondok.jenis-pembayarans.items.create', $jenisPembayaran->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 focus:outline-none transition ease-in-out duration-150">
                        + Tambah Item
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Prioritas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Item</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                                <th scope="col" class="relative px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($jenisPembayaran->items->sortBy('prioritas') as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-xs font-bold text-gray-600">
                                            {{ $item->prioritas }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $item->nama_item }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('adminpondok.items.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            <button type="button" onclick="confirmDeleteItem('{{ $item->id }}', '{{ $item->nama_item }}')" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </div>
                                        <form id="delete-item-form-{{ $item->id }}" action="{{ route('adminpondok.items.destroy', $item->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada item rincian. Tagihan ini bernilai Rp 0.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PENGATURAN KELAS --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Target Kelas</h3>
                    <p class="text-xs text-gray-500 mt-1">Pilih kelas mana saja yang wajib membayar tagihan ini.</p>
                </div>
                
                <form method="POST" action="{{ route('adminpondok.jenis-pembayarans.update', $jenisPembayaran->id) }}">
                    @csrf
                    @method('PUT')
                    
                    {{-- Hidden input agar update nama/tipe tidak error (karena controller mungkin validasi required) --}}
                    <input type="hidden" name="name" value="{{ $jenisPembayaran->name }}">
                    <input type="hidden" name="tipe" value="{{ $jenisPembayaran->tipe }}">

                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @forelse($daftarKelas as $kelas)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-emerald-50 hover:border-emerald-200 cursor-pointer transition-colors">
                                    <input type="checkbox" name="kelas_ids[]" value="{{ $kelas->id }}"
                                        @if($jenisPembayaran->kelas->contains($kelas->id)) checked @endif
                                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 h-5 w-5">
                                    <span class="ms-3 text-sm font-medium text-gray-700">{{ $kelas->nama_kelas }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500 col-span-full">
                                    Belum ada kelas. <a href="{{ route('adminpondok.kelas.index') }}" class="text-emerald-600 underline">Buat Kelas Dulu</a>.
                                </p>
                            @endforelse
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <x-primary-button>Simpan Target Kelas</x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDeleteItem(id, name) {
            Swal.fire({
                title: 'Hapus Item?',
                text: `Menghapus item rincian '${name}'?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-item-form-' + id).submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>