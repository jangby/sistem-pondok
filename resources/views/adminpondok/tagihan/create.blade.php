<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Link Kembali --}}
            <div class="mb-6">
                <a href="{{ route('adminpondok.tagihan.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Tagihan
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Generate Tagihan Masal</h2>
                    <p class="text-sm text-gray-500">Buat tagihan rutin (SPP, dll) untuk seluruh santri aktif sekaligus.</p>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('adminpondok.tagihan.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                            
                            {{-- KOLOM KIRI: PANDUAN --}}
                            <div class="lg:col-span-1 space-y-4">
                                <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                                    <h3 class="font-bold text-blue-800 flex items-center gap-2 mb-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Cara Kerja Sistem
                                    </h3>
                                    <ul class="text-sm text-blue-700 space-y-3 list-disc pl-4">
                                        <li>Sistem akan mencari semua <strong>Jenis Pembayaran</strong> yang bertipe <em>Bulanan</em>, <em>Semesteran</em>, atau <em>Tahunan</em>.</li>
                                        <li>Tagihan akan dibuatkan untuk setiap <strong>Santri Aktif</strong> yang kelasnya sesuai dengan pengaturan jenis pembayaran tersebut.</li>
                                        <li>Jika santri sudah memiliki tagihan pada periode yang dipilih (misal: SPP Januari 2025), sistem akan <strong>melewatinya</strong> (tidak ada duplikat).</li>
                                    </ul>
                                </div>

                                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100 text-xs text-yellow-800">
                                    <strong>Tips:</strong> Pastikan Anda sudah mengatur "Target Kelas" pada menu Jenis Pembayaran sebelum melakukan generate.
                                </div>
                            </div>

                            {{-- KOLOM KANAN: FORMULIR --}}
                            <div class="lg:col-span-2 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Periode Bulan --}}
                                    <div>
                                        <x-input-label for="periode_bulan" :value="__('1. Pilih Bulan Tagihan')" />
                                        <select name="periode_bulan" id="periode_bulan" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ (date('n')) == $i ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create(null, $i, 1)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                        <x-input-error :messages="$errors->get('periode_bulan')" class="mt-2" />
                                    </div>

                                    {{-- Periode Tahun --}}
                                    <div>
                                        <x-input-label for="periode_tahun" :value="__('2. Pilih Tahun Tagihan')" />
                                        <x-text-input id="periode_tahun" class="block mt-1 w-full" type="number" name="periode_tahun" :value="old('periode_tahun', date('Y'))" required />
                                        <x-input-error :messages="$errors->get('periode_tahun')" class="mt-2" />
                                    </div>
                                </div>

                                {{-- Jatuh Tempo --}}
                                <div>
                                    <x-input-label for="due_date" :value="__('3. Tanggal Jatuh Tempo')" />
                                    <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', date('Y-m-d', strtotime('+10 days')))" required />
                                    <p class="text-xs text-gray-500 mt-1">Batas akhir pembayaran sebelum status menjadi 'Terlambat'.</p>
                                    <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="pt-6 border-t border-gray-100 flex justify-end">
                                    <x-primary-button class="px-6 py-3 text-base shadow-lg shadow-emerald-200">
                                        {{ __('Proses & Generate Tagihan') }}
                                    </x-primary-button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>