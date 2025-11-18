<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('adminpondok.jenis-pembayarans.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Edit Jenis Pembayaran</h2>
                    <p class="text-sm text-gray-500">Ubah nama atau tipe pembayaran.</p>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('adminpondok.jenis-pembayarans.update', $jenisPembayaran->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Pembayaran')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $jenisPembayaran->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="tipe" :value="__('Tipe / Frekuensi')" />
                                <select name="tipe" id="tipe" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                    <option value="bulanan" {{ old('tipe', $jenisPembayaran->tipe) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="semesteran" {{ old('tipe', $jenisPembayaran->tipe) == 'semesteran' ? 'selected' : '' }}>Semesteran</option>
                                    <option value="tahunan" {{ old('tipe', $jenisPembayaran->tipe) == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                    <option value="sekali_bayar" {{ old('tipe', $jenisPembayaran->tipe) == 'sekali_bayar' ? 'selected' : '' }}>Sekali Bayar</option>
                                </select>
                                <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('adminpondok.jenis-pembayarans.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-2.5">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>