<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20 font-sans">
        
        {{-- HEADER --}}
        <div class="bg-emerald-700 px-6 pt-8 pb-10 rounded-b-[30px] shadow-lg">
            <div class="flex items-center gap-4">
                <a href="{{ route('sekolah.petugas.sirkulasi.index') }}" class="text-white hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-white">Konfirmasi Kembali</h1>
            </div>
        </div>

        <div class="px-6 -mt-6">
            {{-- INFO PEMINJAMAN --}}
            <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100 mb-4">
                <div class="flex gap-4 mb-4 border-b border-gray-100 pb-4">
                    <div class="w-16 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-300 shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $peminjaman->buku->judul }}</h3>
                        <p class="text-xs text-gray-500 mb-1">{{ $peminjaman->buku->kode_buku }}</p>
                        <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-bold">Dipinjam: {{ $peminjaman->tgl_pinjam->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl">
                    <div>
                        <p class="text-xs text-gray-500">Peminjam</p>
                        <p class="font-bold text-gray-800">{{ $peminjaman->santri->full_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">NIS</p>
                        <p class="font-mono font-bold text-gray-800">{{ $peminjaman->santri->nis }}</p>
                    </div>
                </div>
            </div>

            {{-- FORM PENGEMBALIAN --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <form action="{{ route('sekolah.petugas.sirkulasi.kembali.process', $peminjaman->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    {{-- Keterlambatan --}}
                    @if($terlambat > 0)
                        <div class="bg-red-50 p-3 rounded-xl border border-red-100 flex justify-between items-center">
                            <div class="flex items-center gap-2 text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-xs font-bold">Telat {{ $terlambat }} Hari</span>
                            </div>
                            <span class="text-xs text-red-500">Wajib: {{ \Carbon\Carbon::parse($peminjaman->tgl_wajib_kembali)->format('d M') }}</span>
                        </div>
                    @else
                        <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 flex items-center gap-2 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs font-bold">Tepat Waktu</span>
                        </div>
                    @endif

                    {{-- Input Kondisi --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kondisi Buku</label>
                        <select name="kondisi_kembali" id="kondisi" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="baik">Baik (Normal)</option>
                            <option value="rusak_ringan">Rusak Ringan (Sobek/Coret)</option>
                            <option value="rusak_berat">Rusak Berat (Basah/Lepas)</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>

                    {{-- Input Denda Keterlambatan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Denda Keterlambatan (Rp)</label>
                        <input type="number" name="denda_keterlambatan" value="{{ $estimasiDenda }}" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3 focus:ring-emerald-500 focus:border-emerald-500">
                        <p class="text-[10px] text-gray-400 mt-1">*Otomatis dihitung, bisa diubah manual.</p>
                    </div>

                    {{-- Input Denda Kerusakan --}}
                    <div x-data="{ show: false }" x-init="$watch('document.getElementById(`kondisi`).value', value => show = value !== 'baik')">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Denda Kerusakan / Ganti Rugi (Rp)</label>
                        <input type="number" name="denda_kerusakan" value="0" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl shadow-lg hover:bg-emerald-700 transition">
                        Simpan Pengembalian
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>