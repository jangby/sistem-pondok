<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- JUDUL HALAMAN --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Dompet & Penarikan Dana</h2>
                    <p class="text-sm text-gray-500">Kelola saldo hasil pembayaran online (Midtrans) dan ajukan pencairan.</p>
                </div>
            </div>

            {{-- STATS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- Saldo Tersedia (Utama) --}}
                <div class="bg-emerald-600 rounded-xl shadow-lg p-6 relative overflow-hidden text-white group">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-emerald-200 uppercase tracking-wider">Saldo Tersedia</p>
                        <div class="mt-2">
                            <h3 class="text-3xl font-bold">
                                Rp {{ number_format($saldoTersedia, 0, ',', '.') }}
                            </h3>
                        </div>
                        <p class="text-xs text-emerald-100 mt-2">Siap ditarik ke rekening.</p>
                    </div>
                    <div class="absolute right-2 top-2 text-emerald-500 opacity-30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/></svg>
                    </div>
                </div>

                {{-- Total Netto (Akumulasi) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pemasukan Netto</p>
                        <div class="mt-2">
                            <h3 class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($totalNetto, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>

                {{-- Sedang Diproses (Pending) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sedang Diproses</p>
                        <div class="mt-2">
                            <h3 class="text-2xl font-bold text-yellow-600">
                                Rp {{ number_format($totalPending, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                    <div class="absolute right-2 top-2 text-yellow-500 opacity-10">
                        <svg class="w-16 h-16 -mr-2 -mt-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                </div>

                {{-- Sudah Ditarik (Completed) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Berhasil Dicairkan</p>
                        <div class="mt-2">
                            <h3 class="text-2xl font-bold text-gray-700">
                                Rp {{ number_format($totalCompleted, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- KOLOM KIRI: Form Penarikan --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 h-full">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-bold text-gray-800">Ajukan Penarikan</h3>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="{{ route('adminpondok.payout.store') }}">
                                @csrf
                                
                                <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-100 mb-6">
                                    <p class="text-sm text-emerald-800 mb-1">Saldo Maksimal:</p>
                                    <p class="text-xl font-bold text-emerald-700">Rp {{ number_format($saldoTersedia, 0, ',', '.') }}</p>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="jumlah_penarikan" :value="__('Nominal Penarikan (Rp)')" />
                                        <div class="relative mt-1 rounded-md shadow-sm">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>
                                            <x-text-input id="jumlah_penarikan" class="block w-full pl-10" 
                                                          type="number" 
                                                          name="jumlah_penarikan" 
                                                          :value="old('jumlah_penarikan', $saldoTersedia > 0 ? $saldoTersedia : 0)"
                                                          min="10000"
                                                          max="{{ $saldoTersedia > 0 ? $saldoTersedia : 10000 }}"
                                                          required />
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Minimal penarikan Rp 10.000</p>
                                        <x-input-error :messages="$errors->get('jumlah_penarikan')" class="mt-2" />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="catatan_admin" :value="__('Rekening Tujuan / Catatan')" />
                                        <textarea id="catatan_admin" name="catatan_admin" rows="3" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm placeholder-gray-400" placeholder="Contoh: Transfer ke BNI 12345678 a.n Yayasan Pondok">{{ old('catatan_admin') }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <x-primary-button class="w-full justify-center py-2.5" :disabled="$saldoTersedia < 10000">
                                        {{ __('Kirim Permintaan') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Riwayat --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 h-full">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">Riwayat Penarikan</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Catatan</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($riwayatPayouts as $payout)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $payout->requested_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                                Rp {{ number_format($payout->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($payout->status == 'completed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                        Selesai
                                                    </span>
                                                @elseif($payout->status == 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Menunggu
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <div class="truncate max-w-[150px]" title="{{ $payout->catatan_superadmin }}">
                                                    {{ $payout->catatan_superadmin ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('adminpondok.payout.show', $payout->id) }}" class="text-emerald-600 hover:text-emerald-900">Detail</a>
                                                    @if ($payout->status == 'pending')
                                                        <form id="cancel-form-{{ $payout->id }}" action="{{ route('adminpondok.payout.destroy', $payout->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" 
                                                                    onclick="confirmCancel('{{ $payout->id }}', 'Rp {{ number_format($payout->total_amount, 0, ',', '.') }}')" 
                                                                    class="text-red-600 hover:text-red-900 ml-2">
                                                                Batal
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                                Belum ada riwayat pengajuan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                            {{ $riwayatPayouts->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmCancel(id, amount) {
            Swal.fire({
                title: 'Batalkan Pengajuan?',
                text: `Saldo ${amount} akan dikembalikan ke dompet aktif Anda.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-form-' + id).submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>