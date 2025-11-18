<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- Latar belakang utama bg-gray-100 --}}
    <div class="min-h-screen bg-gray-100 pb-32 relative">

        {{-- Header Background (Tema UKS) --}}
        <div class="h-44 bg-red-600 rounded-b-[40px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            
            {{-- Navbar DENGAN Z-INDEX LEBIH TINGGI --}}
            <div class="flex justify-between items-center p-6 text-white relative z-40"> {{-- z-index: 40 --}}
                <a href="{{ route('pengurus.uks.index') }}" class="bg-white/20 p-2 rounded-xl backdrop-blur-md hover:bg-white/30 transition text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="font-bold text-lg">Detail Kesehatan</h1>
                <div class="w-10"></div> {{-- Spacer --}}
            </div>
        </div>

        {{-- Card Utama (Floating) DENGAN Z-INDEX SEDIKIT LEBIH RENDAH DARI NAVBAR --}}
        <div class="px-6 -mt-20 relative z-30"> {{-- z-index: 30 --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-900/5 p-6 border border-gray-100">
                
                {{-- Bagian Profil Santri --}}
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-50 rounded-full mx-auto mb-3 flex items-center justify-center text-2xl font-bold text-red-600 ring-4 ring-white shadow-md">
                        {{ substr($uks->santri->full_name, 0, 1) }}
                    </div>
                    
                    <h2 class="text-xl font-bold text-gray-800">{{ $uks->santri->full_name }}</h2>
                    <p class="text-gray-500 text-sm mb-4">{{ $uks->santri->kelas->nama_kelas ?? '-' }} • {{ $uks->santri->nis }}</p>

                    <div class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                        {{ $uks->status == 'sembuh' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ str_replace('_', ' ', $uks->status) }}
                    </div>
                </div>

                {{-- Pemisah --}}
                <hr class="my-6 border-gray-100">

                {{-- Bagian Detail Info (List Style Baru) --}}
                <div class="space-y-6">
                    
                    {{-- Tanggal & Waktu --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-500 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Tanggal Sakit</p>
                            <p class="font-bold text-gray-800">{{ $uks->created_at->isoFormat('dddd, D MMMM Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $uks->created_at->format('H:i') }} WIB</p>
                        </div>
                    </div>

                    {{-- Keluhan --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 shrink-0">
                            {{-- Icon "Clipboard" untuk Keluhan --}}
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-red-400 uppercase font-bold">Keluhan Utama</p>
                            <p class="text-gray-800 font-medium leading-relaxed">{{ $uks->keluhan }}</p>
                        </div>
                    </div>

                    {{-- Tindakan --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shrink-0">
                             {{-- Icon "Pill" untuk Tindakan/Obat --}}
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v3m0 0v3m0-3h3m-3 0H3.75M6.75 9v3m0 0v3m0-3h3m-3 0H3.75m6-6v3m0 0v3m0-3h3m-3 0H9.75m6 6v3m0 0v3m0-3h3m-3 0h-3m-6-6v3m0 0v3m0-3h3m-3 0H3.75m6 6v3m0 0v3m0-3h3m-3 0H9.75m6-6v3m0 0v3m0-3h3m-3 0h-3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-blue-400 uppercase font-bold">Tindakan / Obat</p>
                            <p class="text-gray-800 font-medium leading-relaxed">{{ $uks->tindakan ?? 'Belum ada tindakan tercatat.' }}</p>
                        </div>
                    </div>

                    {{-- Status Sembuh (Jika ada) --}}
                    @if($uks->tanggal_sembuh)
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-green-600 font-bold">Telah Sembuh Pada:</p>
                                <p class="text-gray-800 font-bold">{{ $uks->tanggal_sembuh->isoFormat('D MMMM Y') }}</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    {{-- Tombol Aksi (Sticky Bottom) - KODE DARI PERBAIKAN SEBELUMNYA --}}
    <div class="fixed bottom-0 left-0 right-0 p-5 pb-8 z-30 bg-white border-t border-gray-200">
        <a href="{{ route('pengurus.uks.edit', $uks->id) }}" 
           class="block w-full bg-red-600 text-white font-bold text-center py-4 rounded-2xl shadow-lg shadow-red-500/50 active:scale-95 transition">
            Edit Data / Update Status
        </a>
    </div>
</x-app-layout>