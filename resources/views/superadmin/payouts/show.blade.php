<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header & Navigasi --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="text-gray-400">#{{ $payout->id }}</span>
                    Detail Penarikan Dana
                </h2>
                <a href="{{ route('superadmin.payouts.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- KOLOM KIRI: Informasi Detail --}}
                <div class="space-y-6">
                    
                    {{-- Kartu Status & Jumlah --}}
                    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                        <div class="p-6 text-center border-b border-gray-100 bg-gray-50">
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Jumlah Penarikan</p>
                            <h3 class="text-4xl font-bold text-gray-900">Rp {{ number_format($payout->total_amount, 0, ',', '.') }}</h3>
                            
                            <div class="mt-4">
                                @if ($payout->status == 'completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Selesai / Ditransfer
                                    </span>
                                @elseif ($payout->status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 animate-pulse">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Menunggu Konfirmasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-6 bg-white space-y-4">
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500 text-sm">Tanggal Pengajuan</span>
                                <span class="font-medium text-gray-900">{{ $payout->requested_at->format('d F Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500 text-sm">Admin Peminta</span>
                                <span class="font-medium text-gray-900">{{ $payout->adminRequest->name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-50">
                                <span class="text-gray-500 text-sm">Pondok Pesantren</span>
                                <span class="font-medium text-gray-900 text-right">{{ $payout->pondok->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 text-sm block mb-1">Catatan Admin Pondok:</span>
                                <div class="bg-gray-50 p-3 rounded-lg text-sm text-gray-700 italic">
                                    "{{ $payout->catatan_admin ?? 'Tidak ada catatan.' }}"
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: Form Konfirmasi / Bukti Transfer --}}
                <div>
                    @if ($payout->status == 'pending')
                        <div class="bg-white shadow-sm rounded-xl border border-emerald-200 overflow-hidden">
                            <div class="px-6 py-4 bg-emerald-600 border-b border-emerald-500">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Konfirmasi Transfer
                                </h3>
                                <p class="text-emerald-100 text-sm mt-1">Pastikan Anda telah mentransfer dana sebelum mengisi form ini.</p>
                            </div>
                            
                            <form method="POST" action="{{ route('superadmin.payouts.confirm', $payout->id) }}" enctype="multipart/form-data" class="p-6">
                                @csrf
                                
                                <div class="space-y-5">
                                    <div>
                                        <x-input-label for="catatan_superadmin" :value="__('Referensi / No. Resi Transfer')" />
                                        <x-text-input id="catatan_superadmin" class="block mt-1 w-full border-emerald-300 focus:border-emerald-500 focus:ring-emerald-500" type="text" name="catatan_superadmin" :value="old('catatan_superadmin')" required placeholder="Cth: TRF-BCA-12345678" />
                                        <x-input-error :messages="$errors->get('catatan_superadmin')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="bukti_pembayaran" :value="__('Upload Bukti Transfer (Gambar)')" />
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-emerald-400 transition-colors">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="bukti_pembayaran" class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                                        <span>Upload file</span>
                                                        <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" class="sr-only" required accept="image/*">
                                                    </label>
                                                    <p class="pl-1">atau drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('bukti_pembayaran')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="mt-8 flex items-center justify-end gap-3">
                                    <a href="{{ route('superadmin.payouts.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
                                    <x-primary-button class="py-2.5 px-5">
                                        {{ __('Selesaikan Permintaan') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    @elseif ($payout->status == 'completed')
                        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-lg font-bold text-gray-800">Bukti Penyelesaian</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Diselesaikan Oleh:</span>
                                    <span class="font-medium text-gray-900">{{ $payout->superadminApprove->name ?? 'System' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Tanggal Transfer:</span>
                                    <span class="font-medium text-gray-900">{{ $payout->completed_at->format('d F Y, H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 block mb-1">Catatan / No. Ref:</span>
                                    <p class="font-medium text-gray-900 bg-gray-50 p-2 rounded">{{ $payout->catatan_superadmin }}</p>
                                </div>
                                
                                @if($payout->bukti_transfer_url)
                                    <div class="mt-4">
                                        <span class="text-sm text-gray-500 block mb-2">Lampiran Bukti Transfer:</span>
                                        <a href="{{ asset($payout->bukti_transfer_url) }}" target="_blank" class="group block relative rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ asset($payout->bukti_transfer_url) }}" alt="Bukti Transfer" class="w-full h-auto object-cover group-hover:opacity-90 transition">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-10 transition">
                                                <span class="sr-only">Lihat Gambar</span>
                                            </div>
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-4 p-4 bg-yellow-50 text-yellow-700 text-sm rounded-lg">
                                        Tidak ada bukti gambar yang dilampirkan.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>