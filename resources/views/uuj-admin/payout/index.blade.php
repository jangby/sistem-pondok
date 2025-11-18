<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Penarikan Warung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Warung</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nominal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Info Rekening</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($payouts as $payout)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $payout->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $payout->warung->nama_warung }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold">Rp {{ number_format($payout->nominal, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $payout->keterangan }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if($payout->status == 'pending')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-bold animate-pulse">Menunggu</span>
                                            @elseif($payout->status == 'approved')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-bold">Selesai</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-bold">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($payout->status == 'pending')
                                                {{-- Gunakan AlpineJS untuk Modal --}}
                                                <div x-data="{ showModal: false, actionType: '' }">
                                                    <div class="flex justify-center gap-2">
                                                        <button @click="showModal = true; actionType = 'approve'" class="text-green-600 hover:text-green-900 font-bold text-sm">Setujui</button>
                                                        <span class="text-gray-300">|</span>
                                                        <button @click="showModal = true; actionType = 'reject'" class="text-red-600 hover:text-red-900 font-bold text-sm">Tolak</button>
                                                    </div>

                                                    {{-- MODAL --}}
                                                    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                                                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                                                            <h3 class="text-lg font-bold mb-4" x-text="actionType == 'approve' ? 'Konfirmasi Transfer' : 'Tolak Penarikan'"></h3>
                                                            
                                                            {{-- Form Approve --}}
                                                            <form x-show="actionType == 'approve'" action="{{ route('uuj-admin.payout.approve', $payout->id) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Transfer / Nota Cash</label>
                                                                    <input type="file" name="bukti_transfer" class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required accept="image/*">
                                                                    <p class="text-xs text-gray-500 mt-1">Wajib upload foto bukti.</p>
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin (Opsional)</label>
                                                                    <input type="text" name="catatan_admin" class="w-full border-gray-300 rounded-md shadow-sm">
                                                                </div>
                                                                <div class="flex justify-end gap-2">
                                                                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700">Batal</button>
                                                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Konfirmasi & Simpan</button>
                                                                </div>
                                                            </form>

                                                            {{-- Form Reject --}}
                                                            <form x-show="actionType == 'reject'" action="{{ route('uuj-admin.payout.reject', $payout->id) }}" method="POST">
                                                                @csrf
                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                                                                    <textarea name="catatan_admin" class="w-full border-gray-300 rounded-md shadow-sm" rows="3" required placeholder="Contoh: Nomor rekening salah..."></textarea>
                                                                </div>
                                                                <div class="flex justify-end gap-2">
                                                                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700">Batal</button>
                                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Tolak Permintaan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($payout->status == 'approved')
                                                <a href="{{ asset('storage/' . $payout->bukti_transfer) }}" target="_blank" class="text-blue-600 text-xs hover:underline font-bold flex items-center justify-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    Lihat Bukti
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-500">{{ $payout->catatan_admin }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-gray-500">Tidak ada permintaan penarikan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $payouts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>