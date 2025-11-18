<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('uuj-admin.dompet.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Kelola Dompet Santri</h2>
                        <p class="text-sm text-gray-500">Update status, limit, atau reset kredensial.</p>
                    </div>
                    @if($dompet->status == 'active')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase border border-green-200">Status: Aktif</span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase border border-red-200">Status: Diblokir</span>
                    @endif
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('uuj-admin.dompet.update', $dompet->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            
                            {{-- KOLOM KIRI: Info Dompet --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Informasi Dompet</h3>
                                </div>

                                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-gray-600 font-bold text-lg border shadow-sm">
                                            {{ substr($dompet->santri->full_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-lg">{{ $dompet->santri->full_name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $dompet->santri->nis }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">Saldo Saat Ini</p>
                                            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($dompet->saldo, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">Barcode Token</p>
                                            <p class="text-sm font-mono bg-white px-2 py-1 rounded border inline-block mt-1">{{ $dompet->barcode_token }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="checkbox" name="regenerate_barcode" value="1" class="mt-1 rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 h-5 w-5">
                                        <div>
                                            <span class="font-bold text-gray-800 text-sm block">Generate Barcode Baru</span>
                                            <span class="text-xs text-gray-500">Centang ini jika kartu santri hilang atau rusak. Barcode lama tidak akan bisa digunakan lagi.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Pengaturan --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Pengaturan Akses</h3>
                                </div>

                                <div>
                                    <x-input-label for="status" :value="__('Status Dompet')" />
                                    <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm">
                                        <option value="active" {{ old('status', $dompet->status) == 'active' ? 'selected' : '' }}>Aktif (Bisa Transaksi)</option>
                                        <option value="blocked" {{ old('status', $dompet->status) == 'blocked' ? 'selected' : '' }}>Diblokir (Bekukan Sementara)</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="daily_spending_limit" :value="__('Limit Jajan Harian (Rp)')" />
                                    <x-text-input id="daily_spending_limit" class="block mt-1 w-full" type="number" name="daily_spending_limit" :value="old('daily_spending_limit', $dompet->daily_spending_limit)" placeholder="0" />
                                    <p class="text-xs text-gray-500 mt-1">Isi 0 atau kosongkan jika tidak ingin memberi batas harian.</p>
                                </div>
                                
                                <div class="bg-yellow-50 p-5 rounded-xl border border-yellow-100 mt-6">
                                    <p class="text-sm text-yellow-800 font-bold mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                        Reset PIN Santri (Opsional)
                                    </p>
                                    <div class="space-y-4">
                                        <div>
                                            <x-input-label for="pin" :value="__('PIN Baru (6 Angka)')" />
                                            <x-text-input id="pin" class="block mt-1 w-full" type="password" name="pin" maxlength="6" inputmode="numeric" placeholder="******" />
                                        </div>
                                        <div>
                                            <x-input-label for="pin_confirmation" :value="__('Konfirmasi PIN')" />
                                            <x-text-input id="pin_confirmation" class="block mt-1 w-full" type="password" name="pin_confirmation" maxlength="6" inputmode="numeric" placeholder="******" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('uuj-admin.dompet.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-3 text-base">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>