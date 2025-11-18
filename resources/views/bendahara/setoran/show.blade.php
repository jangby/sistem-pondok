<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-20 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('bendahara.setoran.index') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-lg font-bold text-white">Detail Setoran #{{ $setoran->id }}</h1>
                </div>
            
            </div>
        </div>

        {{-- 2. KONTEN UTAMA --}}
        <div class="px-5 -mt-14 relative z-20 space-y-6">
            
            {{-- KARTU STATUS & TOTAL --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Total Setoran</p>
                        <h3 class="text-3xl font-black text-emerald-600 tracking-tight">
                            Rp {{ number_format($setoran->total_setoran, 0, ',', '.') }}
                        </h3>
                        
                        <div class="mt-4 flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Penyetor: <strong>{{ $setoran->admin->name ?? 'Admin Terhapus' }}</strong></span>
                        </div>
                        <div class="mt-1 flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Tanggal: {{ $setoran->created_at->format('d M Y • H:i') }}</span>
                        </div>
                    </div>

                    <div class="text-right">
                        @if ($setoran->dikonfirmasi_pada)
                            <div class="bg-emerald-50 border border-emerald-100 p-3 rounded-xl text-center">
                                <p class="text-[10px] font-bold text-emerald-600 uppercase mb-1">Status</p>
                                <div class="flex flex-col items-center justify-center text-emerald-700">
                                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-xs font-bold">Diterima</span>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-100 p-3 rounded-xl text-center animate-pulse">
                                <p class="text-[10px] font-bold text-yellow-600 uppercase mb-1">Status</p>
                                <div class="flex flex-col items-center justify-center text-yellow-700">
                                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-xs font-bold">Menunggu</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Form Konfirmasi (Jika Belum) --}}
                @if (is_null($setoran->dikonfirmasi_pada))
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <form method="POST" action="{{ route('bendahara.setoran.konfirmasi', $setoran->id) }}" id="konfirmasi-form">
                            @csrf
                            <button type="button" 
                                    onclick="confirmReceipt('{{ $setoran->total_setoran }}')" 
                                    class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition active:scale-[0.98] flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Konfirmasi Penerimaan Dana
                            </button>
                        </form>
                        <p class="text-center text-xs text-gray-400 mt-3">
                            Pastikan uang fisik/transfer sudah Anda terima sebelum mengonfirmasi.
                        </p>
                    </div>
                @else
                    <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500 text-center">
                        Dikonfirmasi oleh <strong>{{ $setoran->bendaharaPenerima->name ?? 'Anda' }}</strong> pada {{ $setoran->dikonfirmasi_pada }}
                    </div>
                @endif
            </div>

            {{-- RINCIAN ITEM --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Rincian Pemasukan</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($summaryPerItem as $item)
                        <div class="px-6 py-3 flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="text-sm font-medium text-gray-700">{{ $item->nama_item }}</span>
                            <span class="text-sm font-bold text-gray-900">Rp {{ number_format($item->total_terkumpul, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-6 text-center text-gray-500 text-sm">Tidak ada rincian item.</div>
                    @endforelse
                </div>
            </div>

            {{-- RINCIAN PER SANTRI --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Santri Putra --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-blue-800 text-sm uppercase">Santri Putra</h3>
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-bold">{{ $santriPutra->count() }}</span>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <div class="divide-y divide-gray-100">
                            @forelse ($santriPutra as $santri)
                                <div class="p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">{{ $santri['nama'] }}</p>
                                            <p class="text-[10px] text-gray-400">NIS: {{ $santri['nis'] }}</p>
                                        </div>
                                        <p class="text-sm font-bold text-emerald-600">Rp {{ number_format($santri['total'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="space-y-1 pl-2 border-l-2 border-gray-100">
                                        @foreach($santri['rincian'] as $transaksi)
                                            <div class="flex justify-between text-[10px] text-gray-500">
                                                <span>{{ $transaksi->tagihan->jenisPembayaran->name }}</span>
                                                <span>{{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-400 text-xs">Tidak ada data.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Santri Putri --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-pink-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-pink-800 text-sm uppercase">Santri Putri</h3>
                        <span class="bg-pink-100 text-pink-700 px-2 py-0.5 rounded text-xs font-bold">{{ $santriPutri->count() }}</span>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <div class="divide-y divide-gray-100">
                            @forelse ($santriPutri as $santri)
                                <div class="p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">{{ $santri['nama'] }}</p>
                                            <p class="text-[10px] text-gray-400">NIS: {{ $santri['nis'] }}</p>
                                        </div>
                                        <p class="text-sm font-bold text-emerald-600">Rp {{ number_format($santri['total'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="space-y-1 pl-2 border-l-2 border-gray-100">
                                        @foreach($santri['rincian'] as $transaksi)
                                            <div class="flex justify-between text-[10px] text-gray-500">
                                                <span>{{ $transaksi->tagihan->jenisPembayaran->name }}</span>
                                                <span>{{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-400 text-xs">Tidak ada data.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @include('layouts.bendahara-nav')

    @push('scripts')
    <script>
        // Fungsi untuk format Rupiah (agar lebih mudah dibaca di alert)
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0 
            }).format(angka);
        }

        // Fungsi konfirmasi menggunakan SweetAlert2
        function confirmReceipt(nominal) {
            Swal.fire({
                title: 'Konfirmasi Penerimaan',
                html: `Anda akan mengonfirmasi setoran senilai <strong class="text-emerald-600">${formatRupiah(nominal)}</strong>.<br><br>Pastikan Anda telah menerima uang fisik/transfer dari Admin Pondok.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669', // Emerald-600
                cancelButtonColor: '#ef4444', // Red-600
                confirmButtonText: 'Ya, Konfirmasi!',
                cancelButtonText: 'Cek Ulang',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl text-sm font-bold',
                    cancelButton: 'rounded-xl text-sm font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form setelah konfirmasi
                    document.getElementById('konfirmasi-form').submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>