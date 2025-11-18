<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-32" x-data="{ copied: false }">
        
        {{-- 1. HEADER STATUS (Orange/Kuning karena Menunggu) --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
                <div class="w-32 h-32 bg-white opacity-10 rounded-full absolute -top-10 -left-10 blur-2xl"></div>
                <div class="w-40 h-40 bg-emerald-400 opacity-10 rounded-full absolute bottom-0 right-0 blur-xl"></div>
            </div>
            
            <div class="relative z-10 text-center text-white">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mx-auto mb-3 animate-pulse">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h1 class="text-xl font-bold">Menunggu Pembayaran</h1>
                <p class="text-emerald-100 text-sm mt-1">Selesaikan sebelum kedaluwarsa</p>
            </div>
        </div>

        {{-- 2. KARTU INSTRUKSI UTAMA --}}
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                
                {{-- Total Bayar --}}
                <div class="p-6 text-center border-b border-gray-50 border-dashed">
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-2">Total Pembayaran</p>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                        Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                    </h2>
                    <p class="text-[10px] text-gray-400 mt-2 font-mono bg-gray-50 inline-block px-2 py-1 rounded">
                        Order ID: {{ $transaksi->order_id_pondok }}
                    </p>
                </div>

                {{-- AREA DINAMIS SESUAI METODE --}}
                <div class="p-6">
                    
                    {{-- A. VIRTUAL ACCOUNT (Mandiri Bill) --}}
                    @if($transaksi->metode_pembayaran == 'virtual_account' && Str::contains($transaksi->bukti_bayar_url, '|'))
                        @php $mandiriParts = explode('|', $transaksi->bukti_bayar_url); @endphp
                        
                        <div class="text-center mb-6">
                            <span class="font-bold text-gray-800 text-lg">Mandiri Bill Payment</span>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <p class="text-xs text-gray-500 mb-1">Kode Perusahaan (Biller)</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-gray-800 font-mono">{{ $mandiriParts[0] }}</span>
                                    {{-- Tombol Salin (Opsional, bisa pakai AlpineJS clipboard di masa depan) --}}
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <p class="text-xs text-gray-500 mb-1">Nomor Tagihan (Bill Key)</p>
                                <p class="text-2xl font-bold text-emerald-600 font-mono tracking-wider">{{ $mandiriParts[1] }}</p>
                            </div>
                        </div>

                    {{-- B. VIRTUAL ACCOUNT (BCA, BNI, BRI, dll) --}}
                    @elseif($transaksi->metode_pembayaran == 'virtual_account')
                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-500 mb-1">Transfer ke Virtual Account</p>
                            {{-- Deteksi Bank dari string URL atau logic controller (sederhana) --}}
                            <h3 class="text-lg font-bold text-gray-800">Bank Virtual Account</h3>
                        </div>

                        <div class="bg-emerald-50 p-5 rounded-xl border border-emerald-100 text-center relative">
                            <p class="text-xs text-emerald-600 uppercase font-bold mb-2">Nomor Virtual Account</p>
                            
                            {{-- Nomor VA Besar --}}
                            <p class="text-3xl font-bold text-gray-800 font-mono tracking-wide break-all" id="va-number">
                                {{ $transaksi->bukti_bayar_url }}
                            </p>

                            {{-- Tombol Salin (Visual Alpine) --}}
                            <button @click="navigator.clipboard.writeText('{{ $transaksi->bukti_bayar_url }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-white border border-emerald-200 rounded-lg text-sm font-medium text-emerald-700 shadow-sm hover:bg-emerald-50 transition">
                                <template x-if="!copied">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                        Salin Nomor
                                    </span>
                                </template>
                                <template x-if="copied">
                                    <span class="flex items-center text-emerald-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Tersalin!
                                    </span>
                                </template>
                            </button>
                        </div>

                    {{-- C. QRIS --}}
                    @elseif($transaksi->metode_pembayaran == 'qris')
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-4">Scan QR Code di bawah ini</p>
                            <div class="inline-block p-4 bg-white border-2 border-gray-100 rounded-2xl shadow-sm">
                                <img src="{{ $transaksi->bukti_bayar_url }}" alt="QRIS Code" class="w-64 h-64 object-contain">
                            </div>
                            <div class="mt-4 flex justify-center gap-2">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/2560px-Gopay_logo.svg.png" class="h-6 opacity-60" alt="Gopay">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Ovo_logo.png/1200px-Ovo_logo.png" class="h-6 opacity-60" alt="OVO">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/2560px-Logo_dana_blue.svg.png" class="h-6 opacity-60" alt="Dana">
                            </div>
                        </div>

                    {{-- D. GOPAY / DEEP LINK --}}
                    @elseif($transaksi->metode_pembayaran == 'gopay')
                        <div class="text-center py-4">
                            <div class="w-20 h-20 bg-sky-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-sky-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Pembayaran GoPay</h3>
                            <p class="text-gray-500 text-sm mb-6">Klik tombol di bawah untuk diarahkan ke aplikasi Gojek.</p>
                            
                            <a href="{{ $transaksi->bukti_bayar_url }}" class="inline-flex items-center justify-center px-6 py-3.5 bg-sky-600 text-white font-bold rounded-xl shadow-lg shadow-sky-200 hover:bg-sky-700 transition w-full">
                                Buka Aplikasi Gojek
                            </a>
                        </div>
                    @endif

                </div>
            </div>
            
            {{-- Petunjuk Tambahan --}}
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-400 flex items-center justify-center gap-1">
                    <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Halaman ini akan otomatis diperbarui setelah Anda membayar.
                </p>
            </div>
        </div>

    </div>

    {{-- 3. FIXED BOTTOM BAR (Cek Status Manual) --}}
    <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-100 p-4 pb-6 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] z-50">
        <div class="max-w-3xl mx-auto">
            {{-- Tombol Kembali / Cek Status --}}
            @php
                $backUrl = $transaksi->tagihan_id ? route('orangtua.tagihan.show', $transaksi->tagihan_id) : route('orangtua.dompet.index');
            @endphp
            
            <a href="{{ $backUrl }}" class="w-full block bg-gray-100 text-gray-600 font-bold text-sm text-center py-3.5 px-6 rounded-xl hover:bg-gray-200 transition">
                Saya Sudah Membayar / Cek Status
            </a>
        </div>
    </div>

    @push('scripts')
    {{-- Kode JavaScript Polling (Tetap Dipertahankan agar auto-redirect) --}}
    <script>
        if (typeof Swal !== 'undefined') {
            const checkStatusUrl = "{{ route('orangtua.pembayaran.status', $transaksi->id) }}";
            
            const redirectUrl = @if($transaksi->tagihan_id)
                "{{ route('orangtua.tagihan.show', $transaksi->tagihan_id) }}";
            @else
                "{{ route('orangtua.dompet.index') }}";
            @endif

            const checkPaymentStatus = async () => {
                try {
                    const response = await fetch(checkStatusUrl);
                    const data = await response.json();
                    if (data.status === 'verified') {
                        clearInterval(pollingInterval); 
                        Swal.fire({
                            title: 'Pembayaran Berhasil!',
                            text: 'Terima kasih, pembayaran telah diterima.',
                            icon: 'success',
                            confirmButtonColor: '#059669', // Emerald-600
                            confirmButtonText: 'Lanjut'
                        }).then((result) => {
                            window.location.href = redirectUrl;
                        });
                    } else if (data.status === 'rejected' || data.status === 'canceled') {
                        clearInterval(pollingInterval);
                        Swal.fire({
                            title: 'Pembayaran Gagal',
                            text: 'Waktu pembayaran habis atau dibatalkan.',
                            icon: 'error',
                            confirmButtonText: 'Kembali'
                        }).then((result) => {
                            window.location.href = redirectUrl;
                        });
                    }
                } catch (error) {
                    console.error('Error checking status:', error);
                }
            };

            const pollingInterval = setInterval(checkPaymentStatus, 5000);
        }
    </script>
    @endpush
</x-app-layout>