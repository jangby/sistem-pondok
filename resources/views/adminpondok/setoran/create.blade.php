<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('adminpondok.setoran.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Laporan
                </a>
            </div>

            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Pilih Kategori Setoran</h2>
                <p class="text-gray-500 mt-1">Pilih jenis dana yang ingin Anda laporkan ke Bendahara.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- KARTU 1: TUNGGAKAN (Merah) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all group">
                    <div class="p-6 border-b border-gray-50 bg-red-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-red-800">Setoran Tunggakan</h3>
                                <p class="text-xs text-red-600 mt-1">Pelunasan hutang bulan lalu.</p>
                            </div>
                            <div class="p-2 bg-white rounded-full text-red-500 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-3xl font-bold text-gray-900 mb-4">Rp {{ number_format($data['tunggakan'], 0, ',', '.') }}</p>
                        <form method="POST" action="{{ route('adminpondok.setoran.store') }}">
                            @csrf
                            <input type="hidden" name="kategori" value="tunggakan">
                            <x-input-label for="catatan_tunggakan" :value="__('Catatan (Opsional)')" class="text-xs uppercase tracking-wide text-gray-400 mb-1"/>
                            <textarea name="catatan" id="catatan_tunggakan" rows="2" class="block w-full border-gray-200 rounded-lg text-sm focus:border-red-500 focus:ring-red-500 bg-gray-50" placeholder="Catatan untuk bendahara..."></textarea>
                            
                            <div class="mt-4">
                                <x-danger-button class="w-full justify-center" :disabled="$data['tunggakan'] <= 0">
                                    Buat Laporan Tunggakan
                                </x-danger-button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KARTU 2: BULAN INI (Hijau) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all group">
                    <div class="p-6 border-b border-gray-50 bg-emerald-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-emerald-800">Setoran Bulan Ini</h3>
                                <p class="text-xs text-emerald-600 mt-1">Pembayaran rutin periode {{ now()->format('F Y') }}.</p>
                            </div>
                            <div class="p-2 bg-white rounded-full text-emerald-500 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-3xl font-bold text-gray-900 mb-4">Rp {{ number_format($data['bulan_ini'], 0, ',', '.') }}</p>
                        <form method="POST" action="{{ route('adminpondok.setoran.store') }}">
                            @csrf
                            <input type="hidden" name="kategori" value="bulan_ini">
                            <x-input-label for="catatan_bulan_ini" :value="__('Catatan (Opsional)')" class="text-xs uppercase tracking-wide text-gray-400 mb-1"/>
                            <textarea name="catatan" id="catatan_bulan_ini" rows="2" class="block w-full border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Catatan untuk bendahara..."></textarea>
                            
                            <div class="mt-4">
                                <x-primary-button class="w-full justify-center bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500" :disabled="$data['bulan_ini'] <= 0">
                                    Buat Laporan Bulan Ini
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KARTU 3: BAYAR DI MUKA (Biru) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all group">
                    <div class="p-6 border-b border-gray-50 bg-blue-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-blue-800">Bayar Di Muka</h3>
                                <p class="text-xs text-blue-600 mt-1">Pembayaran untuk bulan-bulan ke depan.</p>
                            </div>
                            <div class="p-2 bg-white rounded-full text-blue-500 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-3xl font-bold text-gray-900 mb-4">Rp {{ number_format($data['bulan_depan'], 0, ',', '.') }}</p>
                        <form method="POST" action="{{ route('adminpondok.setoran.store') }}">
                            @csrf
                            <input type="hidden" name="kategori" value="bulan_depan">
                            <textarea name="catatan" rows="2" class="block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" placeholder="Catatan..."></textarea>
                            <div class="mt-4">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25 disabled:cursor-not-allowed" {{ $data['bulan_depan'] <= 0 ? 'disabled' : '' }}>
                                    Buat Laporan Depan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KARTU 4: LAIN-LAIN (Abu-abu) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all group">
                    <div class="p-6 border-b border-gray-50 bg-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Setoran Lain-Lain</h3>
                                <p class="text-xs text-gray-500 mt-1">Uang Gedung, Tahunan, Semesteran.</p>
                            </div>
                            <div class="p-2 bg-white rounded-full text-gray-500 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-3xl font-bold text-gray-900 mb-4">Rp {{ number_format($data['lain_lain'], 0, ',', '.') }}</p>
                        <form method="POST" action="{{ route('adminpondok.setoran.store') }}">
                            @csrf
                            <input type="hidden" name="kategori" value="lain_lain">
                            <textarea name="catatan" rows="2" class="block w-full border-gray-200 rounded-lg text-sm focus:border-gray-500 focus:ring-gray-500 bg-gray-50" placeholder="Catatan..."></textarea>
                            <div class="mt-4">
                                <x-secondary-button type="submit" class="w-full justify-center" :disabled="$data['lain_lain'] <= 0">
                                    Buat Laporan Lainnya
                                </x-secondary-button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>