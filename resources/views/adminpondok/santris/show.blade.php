<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- KOLOM KIRI: Profil Santri --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Kartu Profil Utama --}}
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                    <div class="bg-emerald-600 px-6 py-8 text-center">
                        <div class="mx-auto w-24 h-24 bg-white rounded-full flex items-center justify-center text-emerald-600 text-3xl font-bold shadow-lg border-4 border-emerald-200">
                            {{ substr($santri->full_name, 0, 2) }}
                        </div>
                        <h2 class="mt-4 text-xl font-bold text-white">{{ $santri->full_name }}</h2>
                        <p class="text-emerald-100 text-sm">NIS: {{ $santri->nis }}</p>
                        <div class="mt-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white border border-white/30">
                                {{ ucfirst($santri->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Jenis Kelamin</span>
                            <span class="font-medium text-gray-900">{{ $santri->jenis_kelamin }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500 text-sm">Kelas</span>
                            <span class="font-medium text-gray-900">{{ $santri->kelas->nama_kelas ?? '-' }} ({{ $santri->kelas->tingkat ?? '' }})</span>
                        </div>
                        
                        <div class="pt-2">
                            <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Informasi Wali</h4>
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-gray-100 rounded-full">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $santri->orangTua->name ?? 'Belum ada wali' }}</p>
                                    <p class="text-xs text-gray-500">{{ $santri->orangTua->phone ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        <a href="{{ route('adminpondok.santris.edit', $santri->id) }}" class="block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Edit Profil
                        </a>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: Fitur & Aksi --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Kartu: Generate Tagihan Masa Depan --}}
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                        <div class="p-1.5 bg-emerald-100 text-emerald-600 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Buat Tagihan (Bayar di Muka)</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-6 flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-blue-800">
                                Gunakan fitur ini jika santri ingin membayar tagihan untuk bulan-bulan ke depan sekaligus (misal: bayar SPP untuk 6 bulan langsung). Sistem akan otomatis mencegah duplikasi.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('adminpondok.tagihan.store-future', $santri->id) }}">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="jenis_pembayaran_id" :value="__('Pilih Jenis Pembayaran')" />
                                    <select name="jenis_pembayaran_id" id="jenis_pembayaran_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($jenisPembayarans as $jenis)
                                            <option value="{{ $jenis->id }}" data-tipe="{{ $jenis->tipe }}">
                                                {{ $jenis->name }} ({{ ucfirst($jenis->tipe) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Opsi Bulanan --}}
                                <div id="opsi-bulanan" style="display: none;" class="p-4 bg-gray-50 rounded-lg border border-gray-200 space-y-4">
                                    <div>
                                        <x-input-label for="jumlah_bulan" :value="__('Generate untuk berapa bulan?')" />
                                        <x-text-input id="jumlah_bulan" class="block mt-1 w-full" type="number" name="jumlah_bulan" value="1" min="1" max="12" />
                                    </div>
                                    <div>
                                        <x-input-label for="mulai_bulan" :value="__('Mulai dari Bulan/Tahun')" />
                                        <div class="flex gap-4 mt-1">
                                            <select name="mulai_bulan" id="mulai_bulan" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ (date('n') + 1) % 12 == $i ? 'selected' : (date('n') == 12 && $i == 1 ? 'selected' : '') }}>
                                                        {{ \Carbon\Carbon::create(null, $i, 1)->format('F') }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <input type="number" name="mulai_tahun_bulan" value="{{ (date('n') == 12) ? date('Y') + 1 : date('Y') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Opsi Tahunan --}}
                                <div id="opsi-tahunan" style="display: none;" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <x-input-label for="mulai_tahun_tahunan" :value="__('Untuk Tahun Ajaran')" />
                                    <input type="number" name="mulai_tahun_tahunan" value="{{ date('Y') + 1 }}" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>

                                <div>
                                    <x-input-label for="due_date" :value="__('Jatuh Tempo (Tagihan Pertama)')" />
                                    <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', date('Y-m-d', strtotime('+7 days')))" required />
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-primary-button class="py-2.5 px-5">
                                    {{ __('Generate Tagihan') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- AREA: Riwayat Tagihan (Placeholder) --}}
                {{-- Anda bisa menambahkan tabel riwayat tagihan santri ini di sini nanti --}}
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6 text-center">
                    <p class="text-gray-500 text-sm">Riwayat tagihan santri dapat dilihat di menu <strong>Keuangan > Tagihan Santri</strong>.</p>
                    <a href="{{ route('adminpondok.tagihan.index', ['santri_id' => $santri->id]) }}" class="inline-block mt-2 text-emerald-600 font-medium hover:underline">
                        Lihat Tagihan Santri Ini &rarr;
                    </a>
                </div>

            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // JS Sederhana untuk toggle form
        document.getElementById('jenis_pembayaran_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const tipe = selected.getAttribute('data-tipe');
            
            document.getElementById('opsi-bulanan').style.display = 'none';
            document.getElementById('opsi-tahunan').style.display = 'none';

            if (tipe === 'bulanan') {
                document.getElementById('opsi-bulanan').style.display = 'block';
            } else if (tipe === 'tahunan' || tipe === 'semesteran') {
                document.getElementById('opsi-tahunan').style.display = 'block';
            }
        });
    </script>
    @endpush
</x-app-layout>