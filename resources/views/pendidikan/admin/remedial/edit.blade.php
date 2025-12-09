<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Nilai Remedial') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-bold">{{ $nilai->santri->full_name }}</h3>
                        <p class="text-gray-500 text-sm">NIS: {{ $nilai->santri->nis }} | Kelas: {{ $nilai->mustawa->nama }}</p>
                    </div>

                    <form action="{{ route('pendidikan.admin.monitoring.remedial.update', $nilai->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="kategori" value="{{ $kategori }}">

                        <div class="grid grid-cols-1 gap-6">
                            
                            {{-- Info Mapel & KKM --}}
                            <div class="bg-blue-50 p-4 rounded-md border border-blue-200">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-blue-800">Mata Pelajaran:</span>
                                    <span class="text-blue-900">{{ $nilai->mapel->nama_mapel }}</span>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="font-semibold text-blue-800">Kategori Ujian:</span>
                                    <span class="text-blue-900 uppercase font-bold">{{ $kategori }}</span>
                                </div>
                                <div class="flex justify-between mt-2 border-t border-blue-200 pt-2">
                                    <span class="font-semibold text-red-600">KKM (Batas Minimal):</span>
                                    <span class="font-bold text-red-600">{{ $kkm }}</span>
                                </div>
                            </div>

                            {{-- Input Nilai --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-1">Nilai Awal (Sebelumnya)</label>
                                    <input type="text" value="{{ $nilaiLama }}" class="bg-gray-100 border-gray-300 rounded-md shadow-sm block w-full text-center font-bold text-gray-500" disabled>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-1">Nilai Remedial (Baru)</label>
                                    <input type="number" name="nilai_baru" min="0" max="100" step="0.01" required autofocus
                                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full text-center font-bold text-lg">
                                </div>
                            </div>

                            {{-- Opsi Aturan Remedial --}}
                            <div class="flex items-start mt-2">
                                <div class="flex items-center h-5">
                                    <input id="batasi_kkm" name="batasi_kkm" type="checkbox" value="1" checked
                                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-indigo-300 text-indigo-600">
                                </div>
                                <label for="batasi_kkm" class="ml-2 text-sm font-medium text-gray-900">
                                    Terapkan Aturan Batas KKM? 
                                    <p class="text-xs text-gray-500 font-normal">
                                        Jika dicentang: Meskipun nilai remedial inputnya 90, sistem akan menyimpan nilai sebesar KKM ({{ $kkm }}). 
                                        Hapus centang jika ingin memberi nilai murni.
                                    </p>
                                </label>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6 gap-3">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>