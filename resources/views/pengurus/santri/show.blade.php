<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20 relative">
        
        {{-- Background Header --}}
        <div class="h-48 bg-emerald-600 rounded-b-[40px]">
            <div class="flex justify-between items-start p-6 text-white">
                <a href="{{ route('pengurus.santri.index') }}" class="bg-white/20 p-2 rounded-xl backdrop-blur-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <a href="{{ route('pengurus.santri.edit', $santri->id) }}" class="bg-white/20 px-4 py-2 rounded-xl backdrop-blur-md text-xs font-bold">
                    Edit Data
                </a>
            </div>
        </div>

        <div class="px-6 mt-6">
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-4 text-white shadow-lg shadow-emerald-200 relative overflow-hidden">
                {{-- Dekorasi --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10"></div>
                
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest opacity-80">Kartu Santri</p>
                        <h2 class="text-lg font-bold mt-1">{{ $santri->full_name }}</h2>
                        <p class="text-xs opacity-90 font-mono mt-0.5">{{ $santri->nis }}</p>
                    </div>
                    {{-- QR Code --}}
                    <div class="bg-white p-1.5 rounded-lg shadow-sm">
                         @if($santri->qrcode_token)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $santri->qrcode_token }}" class="w-14 h-14">
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex justify-between items-end">
                    <div>
                        <p class="text-[9px] opacity-70 uppercase">RFID UID</p>
                        <p class="font-mono text-sm font-bold tracking-wider">{{ $santri->rfid_uid ?? 'BELUM ADA KARTU' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] opacity-70">Status</p>
                        <p class="text-xs font-bold flex items-center gap-1 justify-end">
                            @if($santri->status == 'active')
                                <span class="w-2 h-2 rounded-full bg-green-300 animate-pulse"></span> Aktif
                            @else
                                <span class="w-2 h-2 rounded-full bg-red-300"></span> Nonaktif
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Info --}}
        <div class="px-6 mt-6 space-y-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Informasi Pribadi
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Jenis Kelamin</span>
                        <span class="font-medium text-gray-800">{{ $santri->jenis_kelamin }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Tempat/Tgl Lahir</span>
                        <span class="font-medium text-gray-800">{{ $santri->tempat_lahir }}, {{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Golongan Darah</span>
                        <span class="font-medium text-gray-800">{{ $santri->golongan_darah ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Kesehatan
                </h3>
                <p class="text-sm text-gray-600">
                    {{ $santri->riwayat_penyakit ?? 'Tidak ada riwayat penyakit khusus.' }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Wali Santri
                </h3>
                <div class="text-sm">
                    <p class="font-bold text-gray-800">{{ $santri->orangTua->name }}</p>
                    <p class="text-gray-500 mt-1">{{ $santri->orangTua->phone }}</p>
                    <p class="text-gray-500 text-xs mt-2">{{ $santri->orangTua->address }}</p>
                    
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $santri->orangTua->phone) }}" target="_blank" class="mt-4 block text-center bg-emerald-100 text-emerald-700 py-2 rounded-xl font-bold text-xs hover:bg-emerald-200">
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>