<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24 relative">
        
        {{-- Background Header --}}
        <div class="h-48 bg-emerald-600 rounded-b-[40px]">
            <div class="flex justify-between items-start p-6 text-white">
                <a href="{{ route('pengurus.santri.index') }}" class="bg-white/20 p-2 rounded-xl backdrop-blur-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <a href="{{ route('pengurus.santri.edit', $santri->id) }}" class="bg-white/20 px-4 py-2 rounded-xl backdrop-blur-md text-xs font-bold hover:bg-white/30 transition">
                    Edit Data
                </a>
            </div>
        </div>

        {{-- Kartu Utama Santri --}}
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

        {{-- Container Data Detail --}}
        <div class="px-6 mt-6 space-y-4">
            
            {{-- 1. Informasi Pribadi Dasar --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Informasi Pribadi
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Kelas</span>
                        <span class="font-medium text-gray-800">{{ $santri->kelas->nama_kelas ?? 'Belum ditentukan' }}</span>
                    </div>
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

            {{-- 2. Alamat & Domisili (BARU) --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Alamat & Domisili
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="bg-gray-50 p-3 rounded-xl text-gray-700 text-xs mb-3">
                        {{ $santri->alamat ?? 'Alamat jalan belum diisi.' }}
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-400 block">RT / RW</span>
                            <span class="font-medium text-gray-800">{{ $santri->rt ?? '-' }} / {{ $santri->rw ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Kode Pos</span>
                            <span class="font-medium text-gray-800">{{ $santri->kode_pos ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Desa/Kel</span>
                            <span class="font-medium text-gray-800">{{ $santri->desa ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Kecamatan</span>
                            <span class="font-medium text-gray-800">{{ $santri->kecamatan ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Data Ayah (BARU) --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Ayah
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Nama Lengkap</span>
                        <span class="font-medium text-gray-800">{{ $santri->nama_ayah ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">NIK</span>
                        <span class="font-medium text-gray-800">{{ $santri->nik_ayah ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Tahun Lahir</span>
                        <span class="font-medium text-gray-800">{{ $santri->thn_lahir_ayah ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Pendidikan</span>
                        <span class="font-medium text-gray-800">{{ $santri->pendidikan_ayah ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Pekerjaan</span>
                        <span class="font-medium text-gray-800">{{ $santri->pekerjaan_ayah ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Penghasilan</span>
                        <span class="font-medium text-gray-800">{{ $santri->penghasilan_ayah ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- 4. Data Ibu (BARU) --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Ibu
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Nama Lengkap</span>
                        <span class="font-medium text-gray-800">{{ $santri->nama_ibu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">NIK</span>
                        <span class="font-medium text-gray-800">{{ $santri->nik_ibu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Tahun Lahir</span>
                        <span class="font-medium text-gray-800">{{ $santri->thn_lahir_ibu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Pendidikan</span>
                        <span class="font-medium text-gray-800">{{ $santri->pendidikan_ibu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Pekerjaan</span>
                        <span class="font-medium text-gray-800">{{ $santri->pekerjaan_ibu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Penghasilan</span>
                        <span class="font-medium text-gray-800">{{ $santri->penghasilan_ibu ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- 5. Kesehatan --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Kesehatan
                </h3>
                <p class="text-sm text-gray-600 bg-red-50 p-3 rounded-xl border border-red-100">
                    {{ $santri->riwayat_penyakit ?? 'Tidak ada riwayat penyakit khusus.' }}
                </p>
            </div>

            {{-- 6. Akun Wali Santri (Login) --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Akun Aplikasi Wali
                </h3>
                <div class="text-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                            {{ substr($santri->orangTua->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">{{ $santri->orangTua->name }}</p>
                            <p class="text-gray-500 text-xs">{{ $santri->orangTua->phone }}</p>
                        </div>
                    </div>
                    
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $santri->orangTua->phone) }}" target="_blank" class="block text-center bg-emerald-50 text-emerald-700 py-2.5 rounded-xl font-bold text-xs hover:bg-emerald-100 transition border border-emerald-100 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-8.68-2.031-.967-.272-.297-.471-.446-.966-.595-.496-.149-1.711.149-1.909.248-.198.099-1.091 1.338-1.289 1.586-.198.248-.397.297-.694.149-.297-.149-1.254-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>