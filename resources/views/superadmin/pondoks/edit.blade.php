<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $pondok->name }}</h2>
                    <p class="text-sm text-gray-500">Edit informasi dan kelola langganan.</p>
                </div>
                <a href="{{ route('superadmin.pondoks.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1">
                    &larr; Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- KOLOM KIRI: Informasi Dasar --}}
                <div class="lg:col-span-2 bg-white shadow-sm rounded-xl border border-gray-100 h-fit">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                        <h3 class="text-lg font-bold text-gray-800">Informasi Pondok</h3>
                    </div>
                    <div class="p-6 text-gray-900">
                        {{-- 
                           PLACEHOLDER: Form edit info pondok.
                           Anda bisa menambahkan form update (PUT) di sini nantinya.
                        --}}
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-700">
                            <p class="font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Fitur Dalam Pengembangan
                            </p>
                            <p class="mt-1 ml-7">Formulir untuk mengubah nama, alamat, dan telepon pondok akan tersedia pada pembaruan berikutnya.</p>
                        </div>

                        <div class="mt-6 space-y-4 opacity-75 pointer-events-none select-none">
                            <div>
                                <x-input-label value="Nama Pondok" />
                                <x-text-input class="w-full mt-1 bg-gray-100" value="{{ $pondok->name }}" disabled />
                            </div>
                            <div>
                                <x-input-label value="Alamat" />
                                <textarea class="w-full mt-1 border-gray-300 rounded-md shadow-sm bg-gray-100" rows="2" disabled>{{ $pondok->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Manajemen Langganan --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-800">Status Langganan</h3>
                        </div>
                        <div class="p-6">
                            
                            @if ($pondok->subscription)
                                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-emerald-800">Aktif</h4>
                                    <p class="text-sm text-emerald-600 mt-1">{{ $pondok->subscription->plan->name ?? 'Paket Custom' }}</p>
                                    <p class="text-xs text-emerald-500 mt-2 pt-2 border-t border-emerald-200">
                                        Berakhir pada: <strong>{{ $pondok->subscription->ends_at->format('d M Y') }}</strong>
                                    </p>
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-xl p-5 text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 text-red-600 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-red-800">Tidak Aktif</h4>
                                    <p class="text-sm text-red-600 mt-1">Pondok ini belum memiliki langganan aktif.</p>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('superadmin.pondoks.subscribe', $pondok->id) }}">
                                @csrf
                                <div>
                                    <x-input-label for="plan_id" :value="__('Pilih/Ubah Paket')" />
                                    <select name="plan_id" id="plan_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                        @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ $pondok->subscription?->plan_id == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} (Rp {{ number_format($plan->price_monthly) }}/bln)
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="durasi_bulan" :value="__('Tambah Durasi (Bulan)')" />
                                    <div class="flex items-center">
                                        <x-text-input id="durasi_bulan" class="block mt-1 w-full" type="number" name="durasi_bulan" value="1" min="1" max="24" required />
                                        <span class="ml-2 text-sm text-gray-500">Bln</span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">*Menambah dari tanggal hari ini/akhir langganan.</p>
                                </div>
                                <div class="mt-6">
                                    <x-primary-button class="w-full justify-center">
                                        {{ __('Perbarui Langganan') }}
                                    </x-primary-button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>