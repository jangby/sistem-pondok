<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- BAGIAN ATAS --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Manajemen Paket Langganan</h2>
                    <p class="text-sm text-gray-500">Atur skema harga untuk pondok pesantren.</p>
                </div>
                <a href="{{ route('superadmin.plans.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Tambah Paket Baru
                </a>
            </div>

            {{-- BAGIAN KONTEN: Grid Kartu --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($plans as $plan)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md hover:border-emerald-300 transition-all duration-200 group relative">
                        
                        {{-- Hiasan Atas --}}
                        <div class="h-2 bg-emerald-500 w-full"></div>

                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">Langganan</p>
                                </div>
                                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                            </div>

                            {{-- Harga --}}
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between items-baseline border-b border-dashed border-gray-100 pb-2">
                                    <span class="text-gray-600 text-sm">Bulanan</span>
                                    <span class="font-bold text-lg text-gray-900">Rp {{ number_format($plan->price_monthly, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-baseline">
                                    <span class="text-gray-600 text-sm">Tahunan</span>
                                    <span class="font-bold text-lg text-gray-900">Rp {{ number_format($plan->price_yearly, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('superadmin.plans.edit', $plan->id) }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-emerald-600 hover:border-emerald-300 transition">
                                Edit Paket
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-xl border border-gray-200 p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada paket langganan</h3>
                        <p class="mt-1 text-gray-500">Buat paket pertama Anda untuk mulai menawarkan langganan.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>