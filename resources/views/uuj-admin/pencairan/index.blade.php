<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pencairan Dana Midtrans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Card Saldo --}}
                <div class="bg-emerald-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs text-emerald-100 uppercase font-bold tracking-wider">Saldo Midtrans (Bisa Ditarik)</p>
                        <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($saldoTersedia, 0, ',', '.') }}</h3>
                        <p class="text-xs mt-2 opacity-80">Dana dari Top-up Orang Tua via Online</p>
                    </div>
                    <div class="absolute right-0 top-0 -mt-4 -mr-4 text-emerald-500 opacity-50">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/></svg>
                    </div>
                </div>

                {{-- Form Tarik --}}
                <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ajukan Penarikan ke Super Admin</h3>
                    <form method="POST" action="{{ route('uuj-admin.pencairan.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label :value="__('Nominal Penarikan')" />
                                <x-text-input type="number" name="nominal" class="block mt-1 w-full" :max="$saldoTersedia" required placeholder="0" />
                                <x-input-error :messages="$errors->get('nominal')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label :value="__('Tujuan Transfer (Bank/No.Rek/Atas Nama)')" />
                                <x-text-input type="text" name="tujuan_transfer" class="block mt-1 w-full" required placeholder="BCA 123456 a.n Pesantren" />
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <x-primary-button :disabled="$saldoTersedia <= 0">Kirim Pengajuan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Riwayat --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-700">Riwayat Pengajuan</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tujuan</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Bukti/Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($riwayat as $payout)
                            <tr>
                                <td class="px-6 py-4 text-sm">{{ $payout->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right font-bold">Rp {{ number_format($payout->nominal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm">{{ $payout->tujuan_transfer }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($payout->status == 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-bold">Menunggu</span>
                                    @elseif($payout->status == 'approved')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs rounded-full font-bold">Cair</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-bold">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($payout->bukti_transfer)
                                        <a href="{{ asset('storage/'.$payout->bukti_transfer) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-gray-500">Belum ada riwayat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>