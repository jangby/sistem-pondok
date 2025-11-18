<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER & NAV --}}
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Detail Penarikan #{{ $payout->id }}</h2>
                    <p class="text-sm text-gray-500">Informasi lengkap status pengajuan dana.</p>
                </div>
                <a href="{{ route('adminpondok.payout.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                {{-- STATUS BANNER --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <span class="text-sm text-gray-600 font-medium">Status Pengajuan</span>
                    @if($payout->status == 'completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Selesai / Ditransfer
                        </span>
                    @elseif($payout->status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                            Sedang Diproses
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800 border border-red-200">
                            Ditolak
                        </span>
                    @endif
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-10">
                    
                    {{-- KOLOM KIRI: Info Pengajuan --}}
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Jumlah Penarikan</p>
                            <p class="text-4xl font-bold text-emerald-600">Rp {{ number_format($payout->total_amount, 0, ',', '.') }}</p>
                        </div>
                        
                        <div class="space-y-3 pt-4 border-t border-gray-100">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Pengajuan</span>
                                <span class="font-medium text-gray-900">{{ $payout->requested_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 block mb-1">Catatan Anda:</span>
                                <div class="bg-gray-50 p-3 rounded-lg text-sm text-gray-800 italic border border-gray-200">
                                    "{{ $payout->catatan_admin ?? '-' }}"
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: Info Penyelesaian (Jika ada) --}}
                    <div class="space-y-6 border-l border-gray-100 pl-10 md:pl-10 md:border-l-0">
                        @if ($payout->status == 'completed')
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Bukti Transfer
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Dikonfirmasi Oleh</span>
                                    <span class="font-medium text-gray-900">{{ $payout->superadminApprove->name ?? 'Super Admin' }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tanggal Transfer</span>
                                    <span class="font-medium text-gray-900">{{ $payout->completed_at ? $payout->completed_at->format('d M Y, H:i') : '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 text-sm block mb-1">Ref / Catatan Admin:</span>
                                    <p class="font-mono text-sm bg-emerald-50 text-emerald-800 p-2 rounded border border-emerald-100">
                                        {{ $payout->catatan_superadmin ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            @if($payout->bukti_transfer_url)
                                <div class="mt-4">
                                    <p class="text-xs text-gray-500 mb-2">Lampiran Gambar:</p>
                                    <a href="{{ asset($payout->bukti_transfer_url) }}" target="_blank" class="block group relative rounded-lg overflow-hidden border border-gray-200">
                                        <img src="{{ asset($payout->bukti_transfer_url) }}" alt="Bukti Transfer" class="w-full h-auto object-cover group-hover:opacity-90 transition">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-10 transition">
                                            <span class="sr-only">Lihat</span>
                                        </div>
                                    </a>
                                </div>
                            @endif

                        @elseif ($payout->status == 'pending')
                            <div class="h-full flex flex-col items-center justify-center text-center py-10 bg-yellow-50 rounded-xl border border-yellow-100 border-dashed">
                                <div class="bg-yellow-100 p-3 rounded-full text-yellow-600 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h4 class="text-gray-900 font-medium">Sedang Diproses</h4>
                                <p class="text-sm text-gray-500 mt-1 max-w-xs">
                                    Permintaan Anda telah masuk ke sistem kami dan sedang menunggu verifikasi Super Admin.
                                </p>
                            </div>
                        @elseif ($payout->status == 'rejected')
                            <div class="bg-red-50 p-4 rounded-xl border border-red-100">
                                <h4 class="font-bold text-red-800 mb-2">Alasan Penolakan:</h4>
                                <p class="text-sm text-red-700">
                                    {{ $payout->catatan_superadmin ?? 'Tidak ada catatan.' }}
                                </p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>