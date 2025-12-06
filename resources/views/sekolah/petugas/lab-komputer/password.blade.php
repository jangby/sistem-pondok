<x-app-layout hide-nav>
    <div class="bg-white p-4 shadow-sm flex items-center sticky top-0 z-30">
        <a href="{{ route('petugas-lab.dashboard') }}" class="mr-4 text-gray-600 hover:text-blue-600">
            <i class="fas fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Rotasi Password</h1>
    </div>

    <div class="p-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Password akan diubah untuk <strong>SEMUA</strong> komputer klien. Perubahan akan terjadi dalam 1 menit saat komputer sinkronisasi.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('petugas-lab.password.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <div class="relative">
                    <input type="text" name="password" required minlength="6" placeholder="Masukkan password..." 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-lg tracking-wide">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Minimal 6 karakter.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 active:scale-95 transition transform">
                Update Password Sekarang
            </button>
        </form>
    </div>
</x-app-layout>