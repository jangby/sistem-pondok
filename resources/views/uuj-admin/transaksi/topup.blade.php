<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        {{-- Ubah max-w-4xl menjadi max-w-7xl (Lebar Penuh) --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('uuj-admin.dashboard') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Top-up Saldo Manual</h2>
                    <p class="text-sm text-gray-500">Catat penerimaan uang tunai dari orang tua/wali untuk menambah saldo santri.</p>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('uuj-admin.topup.store') }}">
                        @csrf

                        {{-- Pesan Error/Sukses Session (jika ada) --}}
                        @if (session('error'))
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            
                            {{-- KOLOM KIRI: Cari Santri --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">1. Cari Santri</h3>
                                </div>

                                <div>
                                    <x-input-label for="select-santri" :value="__('Nama atau NIS Santri')" />
                                    <div class="relative mt-1">
                                        <select id="select-santri" name="santri_id" placeholder="Ketik nama santri..." required class="shadow-sm"></select>
                                    </div>
                                    <x-input-error :messages="$errors->get('santri_id')" class="mt-2" />
                                </div>

                                <div class="bg-blue-50 p-5 rounded-xl border border-blue-100 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-bold mb-1">Pastikan Data Benar</p>
                                        <p>Saldo akan bertambah secara <strong>real-time</strong>. Pastikan Anda telah menerima uang tunai sebelum memproses top-up ini.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Nominal --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">2. Rincian Setoran</h3>
                                </div>

                                <div>
                                    <x-input-label for="nominal" :value="__('Nominal Top-up')" />
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                                        </div>
                                        <x-text-input id="nominal" class="block w-full pl-10 text-lg font-bold text-gray-800" 
                                                      type="number" name="nominal" :value="old('nominal')" 
                                                      min="1000" placeholder="0" required autofocus />
                                    </div>
                                    <x-input-error :messages="$errors->get('nominal')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                                    <x-text-input id="keterangan" class="block mt-1 w-full" 
                                                  type="text" name="keterangan" 
                                                  :value="old('keterangan', 'Setor Tunai')" required />
                                    <p class="text-xs text-gray-500 mt-1">Contoh: Titipan Wali, Tabungan, dll.</p>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('uuj-admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-3 text-base shadow-lg shadow-emerald-100">
                                {{ __('Konfirmasi & Tambah Saldo') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Inisialisasi TomSelect dengan API Search
        new TomSelect('#select-santri', {
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            create: false,
            placeholder: 'Ketik nama santri...',
            load: function(query, callback) {
                if (!query.length) return callback();
                fetch('{{ route('api.search.santri') }}?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(json => { callback(json); })
                    .catch(()=>{ callback(); });
            },
            render: {
                option: function(item, escape) {
                    return `<div class="py-2 px-3">
                                <div class="font-bold text-gray-800">${escape(item.text.split(' (')[0])}</div>
                                <div class="text-xs text-gray-500">NIS: ${escape(item.text.match(/NIS: (\d+)/)?.[1] || '-')}</div>
                            </div>`;
                }
            }
        });
    </script>
    @endpush
</x-app-layout>