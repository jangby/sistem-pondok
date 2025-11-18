{{-- Kita gunakan layout tamu (Guest) yang sudah ada dari Breeze --}}
<x-guest-layout>
    <div class="mb-4 text-center">
         <h3 class="text-xl font-bold text-gray-800">Langganan Dibutuhkan</h3>
    </div>

    <div class="mb-4 text-sm text-gray-600">
        {{ session('error', 'Akses Anda ditolak. Langganan pondok Anda mungkin telah berakhir atau belum diatur.') }}
        <br><br>
        Silakan hubungi Administrator Sistem (Super Admin) untuk mengaktifkan atau memperpanjang langganan Anda.
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Kembali ke Halaman Login
        </a>
    </div>
</x-guest-layout>