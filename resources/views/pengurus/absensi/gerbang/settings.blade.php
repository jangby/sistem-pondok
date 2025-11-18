<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex items-center gap-4">
            <a href="{{ route('pengurus.absensi.gerbang') }}" class="text-gray-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            <h1 class="font-bold text-lg text-gray-800">Pengaturan Gerbang</h1>
        </div>

        <div class="p-6">
            <form action="{{ route('pengurus.absensi.gerbang.settings.store') }}" method="POST" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-6">
                @csrf
                
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Batas Waktu Keluar (Menit)</label>
                    <input type="number" name="max_minutes_out" value="{{ $setting->max_minutes_out ?? 15 }}" class="w-full rounded-xl border-gray-200 text-lg font-bold text-center text-blue-600 focus:ring-blue-500">
                    <p class="text-[10px] text-gray-400 mt-1">Jika melebihi waktu ini, notifikasi WA akan dikirim.</p>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Nomor WA Satpam</label>
                    <input type="text" name="satpam_wa_number" value="{{ $setting->satpam_wa_number }}" class="w-full rounded-xl border-gray-200" placeholder="0812xxx">
                </div>

                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <span class="text-sm font-bold text-gray-700">Aktifkan Auto Notifikasi</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_notify" value="1" class="sr-only peer" {{ ($setting->auto_notify ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200 active:scale-95 transition">Simpan Pengaturan</button>
            </form>
        </div>
    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>