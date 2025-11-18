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
                
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Aktifkan Dompet Santri</h2>
                    <p class="text-sm text-gray-500">Buat akun dompet digital baru untuk santri ini.</p>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('uuj-admin.dompet.store', $santri->id) }}">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            
                            {{-- KOLOM KIRI: Info Santri --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Identitas Pemilik</h3>
                                </div>

                                <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100 flex items-start gap-4">
                                    <div class="w-16 h-16 bg-emerald-200 rounded-full flex items-center justify-center text-emerald-700 font-bold text-2xl">
                                        {{ substr($santri->full_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">{{ $santri->full_name }}</h3>
                                        <p class="text-emerald-800 text-sm font-medium mt-1">NIS: {{ $santri->nis }}</p>
                                        <p class="text-gray-500 text-sm">Kelas: {{ $santri->kelas->nama_kelas ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-sm text-blue-800">
                                    <p><strong>Info:</strong> Setelah diaktifkan, saldo awal akan bernilai <strong>Rp 0</strong>. Wali santri dapat segera melakukan Top-up melalui aplikasi mereka.</p>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Setup Keamanan --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Pengaturan Keamanan</h3>
                                </div>

                                <p class="text-sm text-gray-600 mb-2">
                                    Buat <strong>PIN 6 digit</strong> angka. Santri wajib memasukkan PIN ini setiap kali melakukan transaksi di kantin.
                                </p>
                                
                                <div>
                                    <x-input-label for="pin" :value="__('Buat PIN Baru')" />
                                    <x-text-input id="pin" class="block mt-1 w-full text-lg tracking-widest text-center font-bold" 
                                                  type="password" name="pin" required maxlength="6" inputmode="numeric" placeholder="******" />
                                    <x-input-error :messages="$errors->get('pin')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="pin_confirmation" :value="__('Konfirmasi PIN')" />
                                    <x-text-input id="pin_confirmation" class="block mt-1 w-full text-lg tracking-widest text-center font-bold" 
                                                  type="password" name="pin_confirmation" required maxlength="6" inputmode="numeric" placeholder="******" />
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('uuj-admin.dompet.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-3 text-base">
                                {{ __('Aktifkan Dompet Sekarang') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>