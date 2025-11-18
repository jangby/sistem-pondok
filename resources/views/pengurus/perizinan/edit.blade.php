<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        <div class="bg-white p-8 text-center rounded-b-[40px] shadow-sm">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h1 class="text-xl font-bold text-gray-800">Konfirmasi Kepulangan</h1>
            <p class="text-gray-500 mt-2 text-sm">Apakah santri <strong>{{ $izin->santri->full_name }}</strong> sudah kembali ke pondok?</p>
        </div>

        <form action="{{ route('pengurus.perizinan.update', $izin->id) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            
            <div class="bg-white p-4 rounded-xl border border-gray-200 mb-6 text-sm">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-500">Rencana Kembali:</span>
                    <span class="font-bold">{{ $izin->tgl_selesai_rencana->format('d M H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status:</span>
                    @if($izin->tgl_selesai_rencana < now())
                        <span class="text-red-600 font-bold">Terlambat {{ $izin->tgl_selesai_rencana->diffForHumans() }}</span>
                    @else
                        <span class="text-blue-600 font-bold">Tepat Waktu</span>
                    @endif
                </div>
            </div>

            <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg active:scale-95 transition">
                Ya, Sudah Kembali
            </button>
            <a href="{{ route('pengurus.perizinan.index') }}" class="block text-center mt-4 text-gray-500 text-sm">Batal</a>
        </form>
    </div>
</x-app-layout>