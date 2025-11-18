<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>
    <div class="min-h-screen bg-gray-50 pb-20">
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex items-center gap-4">
            <a href="{{ route('pengurus.asrama.ketua.index') }}" class="text-gray-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
            <h1 class="font-bold text-lg text-gray-800">Pengaturan Absen Ketua</h1>
        </div>
        <div class="p-6">
            <form action="{{ route('pengurus.asrama.ketua.settings.store') }}" method="POST" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                @csrf
                <h3 class="font-bold text-purple-700 mb-4">Rentang Jam Wajib Absen</h3>
                <div class="flex gap-2 items-center mb-4">
                    <input type="time" name="jam_mulai" value="{{ $setting->jam_mulai ?? '18:00' }}" class="w-full rounded-xl border-gray-200 text-sm">
                    <span>-</span>
                    <input type="time" name="jam_selesai" value="{{ $setting->jam_selesai ?? '22:00' }}" class="w-full rounded-xl border-gray-200 text-sm">
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-xl font-bold">Simpan Pengaturan</button>
            </form>
            
            {{-- Form Libur (Gunakan Controller Asrama yang sudah ada) --}}
            <div class="mt-6">
                <h3 class="font-bold text-gray-800 mb-4">Daftar Libur</h3>
                {{-- Tampilkan list libur dan form tambah libur sama seperti di Asrama Settings --}}
                {{-- (Anda bisa copy-paste bagian libur dari resources/views/pengurus/absensi/asrama/settings.blade.php) --}}
            </div>
        </div>
    </div>
</x-app-layout>