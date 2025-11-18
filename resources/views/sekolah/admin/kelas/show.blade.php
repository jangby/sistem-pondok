<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Kelas: ') }} {{ $kela->nama_kelas }} ({{ $kela->tingkat }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Tambah Santri ke Kelas Ini
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Hanya santri (status aktif) yang belum memiliki kelas yang akan muncul di sini.
                            </p>
                            
                            <form method="POST" action="{{ route('sekolah.admin.kelas.addSantri', $kela->id) }}">
                                @csrf
                                <div>
                                    <x-input-label for="santri_id" :value="__('Pilih Santri')" />
                                    <select name="santri_id" id="santri_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">-- Pilih Santri --</option>
                                        @forelse($santriTanpaKelas as $santri)
                                            <option value="{{ $santri->id }}">{{ $santri->full_name }} (NIS: {{ $santri->nis }})</option>
                                        @empty
                                            <option value="" disabled>Semua santri sudah punya kelas</option>
                                        @endforelse
                                    </select>
                                    <x-input-error :messages="$errors->get('santri_id')" class="mt-2" />
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <x-primary-button>
                                        {{ __('+ Tambahkan') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Daftar Santri di Kelas Ini (Total: {{ $santriDiKelas->count() }})
                            </h3>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Santri</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pindahkan ke</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($santriDiKelas as $santri)
                                        <tr>
                                            <td class="px-4 py-2 text-sm font-medium">{{ $santri->full_name }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500">{{ $santri->nis }}</td>
                                            <td class="px-4 py-2 text-sm">
                                                {{-- Form Pindah Kelas Individual --}}
                                                <form method="POST" action="{{ route('sekolah.admin.kelas.moveSantri', $santri->id) }}" class="flex items-center space-x-2">
                                                    @csrf
                                                    <select name="kelas_tujuan_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                                        <option value="">-- Pilih Kelas --</option>
                                                        @foreach($kelasList as $tingkat => $kelases)
                                                            <optgroup label="Tingkat: {{ $tingkat }}">
                                                                @foreach($kelases as $kelas)
                                                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                    <x-primary-button class="!py-1 !px-2 !text-xs">
                                                        {{ __('Pindah') }}
                                                    </x-primary-button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500">Belum ada santri di kelas ini.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>