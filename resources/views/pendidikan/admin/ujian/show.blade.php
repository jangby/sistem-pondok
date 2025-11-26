<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Ujian: {{ $jadwal->mapel->nama_mapel }}
            </h2>
            <a href="{{ route('pendidikan.admin.ujian.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: 'nilai' }"> {{-- Default Tab Nilai agar langsung fokus --}}
            
            {{-- INFO JADWAL --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $jadwal->mapel->nama_kitab }}</h3>
                    <p class="text-sm text-gray-500">
                        {{ $jadwal->mustawa->nama }} • 
                        {{ ucfirst($jadwal->jenis_ujian) }} {{ ucfirst($jadwal->semester) }} •
                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('D MMMM Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="block text-xs text-gray-400 uppercase font-bold">Pengawas</span>
                    <span class="block font-bold text-emerald-600">{{ $jadwal->pengawas->nama_lengkap ?? '-' }}</span>
                </div>
            </div>

            {{-- TABS NAVIGASI --}}
            <div class="flex gap-4 mb-6 border-b border-gray-200">
                <button @click="tab = 'nilai'" :class="tab === 'nilai' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Input Nilai ({{ ucfirst($jadwal->kategori_tes) }})
                </button>
                <button @click="tab = 'absensi'" :class="tab === 'absensi' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Absensi Ujian
                </button>
                <button @click="tab = 'ledger'" :class="tab === 'ledger' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Ledger & Export
                </button>
            </div>

            {{-- TAB 1: INPUT NILAI --}}
            <div x-show="tab === 'nilai'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                
                <form action="{{ route('pendidikan.admin.ujian.grades', $jadwal->id) }}" method="POST">
                    @csrf
                    
                    {{-- [LOGIKA BARU] TOTAL PERTEMUAN (KHUSUS UJIAN TULIS) --}}
                    @if($jadwal->kategori_tes == 'tulis')
                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex items-center justify-between mb-6">
                        <div>
                            <h4 class="text-sm font-bold text-blue-800">Parameter Kehadiran (Semester Ini)</h4>
                            <p class="text-xs text-blue-600">Masukkan total tatap muka untuk menghitung nilai kehadiran otomatis.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-bold text-blue-700 uppercase">Total Tatap Muka:</label>
                            {{-- Nilai default 14 jika belum ada data, atau ambil dari controller --}}
                            <input type="number" name="total_meetings" value="{{ $totalPertemuan > 0 ? $totalPertemuan : 14 }}" 
                                class="w-24 text-center font-bold text-blue-700 border-blue-200 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                min="1" required>
                        </div>
                    </div>
                    @else
                    {{-- Pesan untuk ujian non-tulis --}}
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg text-gray-600 text-sm border border-gray-100 flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="font-bold">Input Nilai: Kategori {{ strtoupper($jadwal->kategori_tes) }}</p>
                            <p class="opacity-80 mt-1">Hanya input nilai ujian. Nilai kehadiran diambil dari data ujian Tulis.</p>
                        </div>
                    </div>
                    @endif

                    {{-- Header Tabel --}}
                    <div class="flex justify-between items-center px-4 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <span class="w-1/2">Identitas Santri</span>
                        <div class="flex gap-4 w-1/2 justify-end">
                            <span class="w-32 text-center">Nilai {{ ucfirst($jadwal->kategori_tes) }}</span>
                            
                            {{-- Header Kolom Hadir HANYA MUNCUL JIKA TULIS --}}
                            @if($jadwal->kategori_tes == 'tulis')
                                <span class="w-32 text-center text-emerald-600">Jml Hadir</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($santris as $santri)
                            @php 
                                $record = $nilai[$santri->id] ?? null; // Variabel dari controller admin biasanya $nilai atau $nilaiData
                                
                                // 1. Nilai Ujian Utama
                                $nilaiAwal = 0;
                                if($record) {
                                    if($jadwal->kategori_tes == 'tulis') $nilaiAwal = $record->nilai_tulis;
                                    elseif($jadwal->kategori_tes == 'lisan') $nilaiAwal = $record->nilai_lisan;
                                    elseif($jadwal->kategori_tes == 'praktek') $nilaiAwal = $record->nilai_praktek;
                                }
                                $nilaiAwal = $nilaiAwal == 0 ? '' : $nilaiAwal;

                                // 2. Nilai Kehadiran (Raw Count)
                                // Kita ambil dari variabel $dataKehadiran yang dikirim controller
                                $valHadir = $dataKehadiran[$santri->id] ?? 0;
                            @endphp

                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl bg-white hover:border-emerald-300 transition shadow-sm">
                                {{-- Identitas --}}
                                <div class="w-1/2 pr-4">
                                    <div class="font-bold text-gray-800">{{ $santri->full_name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">NIS: {{ $santri->nis }}</div>
                                </div>

                                {{-- Input Area --}}
                                <div class="flex gap-4 w-1/2 justify-end">
                                    {{-- Input Nilai Ujian --}}
                                    <div class="w-32">
                                        <input type="number" name="grades[{{ $santri->id }}]" value="{{ $nilaiAwal }}" min="0" max="100" step="0.01"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-center font-bold text-gray-700 bg-gray-50 py-2"
                                            placeholder="0">
                                    </div>

                                    {{-- Input Kehadiran (HANYA MUNCUL JIKA TULIS) --}}
                                    @if($jadwal->kategori_tes == 'tulis')
                                    <div class="w-32 relative">
                                        <input type="number" name="attendance_count[{{ $santri->id }}]" value="{{ $valHadir }}" min="0"
                                            class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 text-center font-bold text-emerald-700 bg-emerald-50 py-2"
                                            placeholder="0">
                                        <div class="absolute right-2 top-2.5 text-[10px] text-emerald-400 pointer-events-none">Hari</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-8 border-t border-gray-100 pt-6">
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB 2: ABSENSI (SAAT UJIAN) --}}
            <div x-show="tab === 'absensi'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6" style="display: none;">
                <form action="{{ route('pendidikan.admin.ujian.attendance', $jadwal->id) }}" method="POST">
                    @csrf
                    <div class="mb-4 text-sm text-gray-500">
                        Absensi kehadiran peserta pada saat pelaksanaan ujian <b>{{ $jadwal->mapel->nama_mapel }}</b>.
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 mb-4">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-bold text-gray-500 uppercase">Nama Santri</th>
                                <th class="text-center text-xs font-bold text-gray-500 uppercase">Hadir</th>
                                <th class="text-center text-xs font-bold text-gray-500 uppercase">Izin</th>
                                <th class="text-center text-xs font-bold text-gray-500 uppercase">Sakit</th>
                                <th class="text-center text-xs font-bold text-gray-500 uppercase">Alpha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($santris as $santri)
                                @php $status = $absensi[$santri->id] ?? 'A'; @endphp {{-- Pastikan variabel controller konsisten $absensi atau $absensiData --}}
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 text-sm font-bold">{{ $santri->full_name }}</td>
                                    <td class="text-center"><input type="radio" name="attendance[{{ $santri->id }}]" value="H" {{ $status == 'H' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500"></td>
                                    <td class="text-center"><input type="radio" name="attendance[{{ $santri->id }}]" value="I" {{ $status == 'I' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500"></td>
                                    <td class="text-center"><input type="radio" name="attendance[{{ $santri->id }}]" value="S" {{ $status == 'S' ? 'checked' : '' }} class="text-orange-600 focus:ring-orange-500"></td>
                                    <td class="text-center"><input type="radio" name="attendance[{{ $santri->id }}]" value="A" {{ $status == 'A' ? 'checked' : '' }} class="text-red-600 focus:ring-red-500"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-emerald-700 transition">Simpan Absensi Ujian</button>
                    </div>
                </form>
            </div>

            {{-- TAB 3: LEDGER & EXPORT --}}
            <div x-show="tab === 'ledger'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6" style="display: none;">
                
                {{-- Bagian 1: Dokumen Kelengkapan (Untuk Dibawa ke Kelas) --}}
                <div class="mb-8 border-b border-gray-100 pb-8 text-center">
                    <h3 class="text-md font-bold text-gray-700 mb-2">Dokumen Kelengkapan Ujian</h3>
                    <p class="text-gray-400 text-sm mb-4">Cetak dokumen ini untuk pegangan pengawas saat ujian berlangsung.</p>
                    
                    <div class="flex justify-center gap-3">
                        <a href="{{ route('pendidikan.admin.ujian.format-nilai', $jadwal->id) }}" target="_blank" class="bg-indigo-50 text-indigo-700 border border-indigo-200 px-5 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-indigo-100 transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Cetak Blangko Nilai
                        </a>
                        <a href="{{ route('pendidikan.admin.ujian.daftar-hadir', $jadwal->id) }}" target="_blank" class="bg-indigo-50 text-indigo-700 border border-indigo-200 px-5 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-indigo-100 transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Cetak Absensi Ujian
                        </a>
                    </div>
                </div>

                {{-- Bagian 2: Export Data (Setelah Nilai Masuk) --}}
                <div class="text-center">
                    <h3 class="text-md font-bold text-gray-700 mb-2">Export Ledger Nilai</h3>
                    <p class="text-gray-400 text-sm mb-4">Unduh rekapitulasi nilai yang sudah diinput ke sistem.</p>
                    
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('pendidikan.admin.ujian.pdf', $jadwal->id) }}" class="bg-red-500 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 hover:bg-red-600 transition shadow-lg shadow-red-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Download PDF
                        </a>
                        <a href="{{ route('pendidikan.admin.ujian.excel', $jadwal->id) }}" class="bg-green-600 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 hover:bg-green-700 transition shadow-lg shadow-green-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Excel
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>