<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permintaan Pencairan Uang Jajan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pondok</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nominal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tujuan Transfer</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($payouts as $payout)
                                <tr>
                                    <td class="px-6 py-4 text-sm">{{ $payout->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 font-bold">{{ $payout->pondok->name }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-emerald-600">Rp {{ number_format($payout->nominal, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $payout->tujuan_transfer }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold 
                                            {{ $payout->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                              ($payout->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($payout->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($payout->status == 'pending')
                                            <div x-data="{ showModal: false, action: 'approve' }">
                                                <button @click="showModal = true" class="text-blue-600 hover:underline text-sm font-bold">Proses</button>

                                                {{-- Modal --}}
                                                <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                                                    <div class="bg-white rounded-lg w-full max-w-md p-6 text-left">
                                                        <h3 class="text-lg font-bold mb-4">Proses Penarikan</h3>
                                                        <form action="{{ route('superadmin.uuj-payout.update', $payout->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf @method('PUT')
                                                            
                                                            <div class="mb-4">
                                                                <label class="block text-sm font-medium mb-1">Tindakan</label>
                                                                <select name="action" x-model="action" class="w-full border-gray-300 rounded-md">
                                                                    <option value="approve">Setujui & Transfer</option>
                                                                    <option value="reject">Tolak</option>
                                                                </select>
                                                            </div>

                                                            <div x-show="action == 'approve'" class="mb-4">
                                                                <label class="block text-sm font-medium mb-1">Upload Bukti Transfer</label>
                                                                <input type="file" name="bukti_transfer" class="w-full border border-gray-300 rounded-md p-2">
                                                            </div>

                                                            <div class="mb-4">
                                                                <label class="block text-sm font-medium mb-1">Catatan</label>
                                                                <textarea name="catatan" rows="2" class="w-full border-gray-300 rounded-md"></textarea>
                                                            </div>

                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-6">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $payouts->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>