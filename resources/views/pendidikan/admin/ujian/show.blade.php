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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: 'absensi' }">
            
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
                    <span class="block font-bold text-emerald-600">{{ $jadwal->pengawas->nama_lengkap }}</span>
                </div>
            </div>

            {{-- TABS NAVIGASI --}}
            <div class="flex gap-4 mb-6 border-b border-gray-200">
                <button @click="tab = 'absensi'" :class="tab === 'absensi' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Absensi Ujian
                </button>
                <button @click="tab = 'nilai'" :class="tab === 'nilai' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Input Nilai ({{ ucfirst($jadwal->kategori_tes) }})
                </button>
                <button @click="tab = 'ledger'" :class="tab === 'ledger' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Ledger & Export
                </button>
            </div>

            {{-- TAB 1: ABSENSI --}}
            <div x-show="tab === 'absensi'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                <form action="{{ route('pendidikan.admin.ujian.attendance', $jadwal->id) }}" method="POST">
                    @csrf
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
                                @php $status = $absensiData[$santri->id] ?? 'A'; @endphp
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
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-emerald-700 transition">Simpan Absensi</button>
                    </div>
                </form>
            </div>

            {{-- TAB 2: INPUT NILAI --}}
            <div x-show="tab === 'nilai'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6" style="display: none;">
                <div class="mb-4 bg-blue-50 p-3 rounded-lg text-blue-700 text-xs flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Anda sedang menginput nilai untuk kategori: <strong>{{ strtoupper($jadwal->kategori_tes) }}</strong>.
                </div>

                <form action="{{ route('pendidikan.admin.ujian.grades', $jadwal->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($santris as $santri)
                            @php 
                                $record = $nilaiData[$santri->id] ?? null;
                                $nilaiAwal = 0;
                                if($record) {
                                    if($jadwal->kategori_tes == 'tulis') $nilaiAwal = $record->nilai_tulis;
                                    elseif($jadwal->kategori_tes == 'lisan') $nilaiAwal = $record->nilai_lisan;
                                    elseif($jadwal->kategori_tes == 'praktek') $nilaiAwal = $record->nilai_praktek;
                                }
                            @endphp
                            <div class="flex items-center justify-between p-3 border rounded-lg bg-gray-50">
                                <label class="text-sm font-bold text-gray-700 w-2/3">{{ $santri->full_name }}</label>
                                <input type="number" name="grades[{{ $santri->id }}]" value="{{ $nilaiAwal }}" min="0" max="100" step="0.01"
                                    class="w-24 rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-right font-mono font-bold">
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Simpan Nilai</button>
                    </div>
                </form>
            </div>

            {{-- TAB 3: LEDGER & EXPORT --}}
            <div x-show="tab === 'ledger'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 text-center" style="display: none;">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 text-emerald-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Export Data Nilai</h3>
                    <p class="text-gray-500 text-sm mb-6">Unduh rekapitulasi nilai untuk ujian ini dalam format PDF atau Excel.</p>
                    
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