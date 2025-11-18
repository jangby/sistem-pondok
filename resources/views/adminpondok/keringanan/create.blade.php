<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('adminpondok.keringanans.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Buat Aturan Keringanan Baru</h2>
                    <p class="text-sm text-gray-500">Atur diskon otomatis untuk tagihan tertentu.</p>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('adminpondok.keringanans.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI: Target Penerima --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-emerald-700 border-b border-emerald-100 pb-2">Target Penerima</h3>
                                
                                <div>
                                    <x-input-label for="santri_id" :value="__('Pilih Santri')" />
                                    <select name="santri_id" id="santri_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                        <option value="">-- Cari Santri --</option>
                                        @foreach ($santris as $santri)
                                            <option value="{{ $santri->id }}" {{ old('santri_id') == $santri->id ? 'selected' : '' }}>
                                                {{ $santri->full_name }} (NIS: {{ $santri->nis }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('santri_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="jenis_pembayaran_id" :value="__('Tagihan yang Diringankan')" />
                                    <select name="jenis_pembayaran_id" id="jenis_pembayaran_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                        <option value="">-- Pilih Jenis Pembayaran --</option>
                                        @foreach ($jenisPembayarans as $jenis)
                                            <option value="{{ $jenis->id }}" {{ old('jenis_pembayaran_id') == $jenis->id ? 'selected' : '' }}>
                                                {{ $jenis->name }} ({{ ucfirst($jenis->tipe) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Keringanan hanya berlaku untuk jenis pembayaran ini.</p>
                                    <x-input-error :messages="$errors->get('jenis_pembayaran_id')" class="mt-2" />
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Detail Keringanan --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-emerald-700 border-b border-emerald-100 pb-2">Konfigurasi Potongan</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="tipe_keringanan" :value="__('Tipe Potongan')" />
                                        <select name="tipe_keringanan" id="tipe_keringanan" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                            <option value="persentase" {{ old('tipe_keringanan') == 'persentase' ? 'selected' : '' }}>Persentase (%)</option>
                                            <option value="nominal_tetap" {{ old('tipe_keringanan') == 'nominal_tetap' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="nilai" :value="__('Nilai Potongan')" />
                                        <x-text-input id="nilai" class="block mt-1 w-full" type="number" name="nilai" :value="old('nilai')" required placeholder="Cth: 50 atau 50000" />
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 -mt-4">
                                    *Jika Persentase, isi 1-100. Jika Nominal, isi jumlah Rupiah (misal 50000).
                                </p>
                                <x-input-error :messages="$errors->get('nilai')" class="mt-1" />

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="berlaku_mulai" :value="__('Mulai Berlaku')" />
                                        <x-text-input id="berlaku_mulai" class="block mt-1 w-full" type="date" name="berlaku_mulai" :value="old('berlaku_mulai', date('Y-m-d'))" required />
                                    </div>
                                    <div>
                                        <x-input-label for="berlaku_sampai" :value="__('Sampai Tanggal (Opsional)')" />
                                        <x-text-input id="berlaku_sampai" class="block mt-1 w-full" type="date" name="berlaku_sampai" :value="old('berlaku_sampai')" />
                                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika berlaku selamanya.</p>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="keterangan" :value="__('Keterangan / Alasan')" />
                                    <x-text-input id="keterangan" class="block mt-1 w-full" type="text" name="keterangan" :value="old('keterangan')" placeholder="Cth: Beasiswa Yatim, Prestasi, dll." />
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('adminpondok.keringanans.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-2.5">
                                {{ __('Simpan Aturan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>