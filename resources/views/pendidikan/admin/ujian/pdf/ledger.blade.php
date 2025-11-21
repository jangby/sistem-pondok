<!DOCTYPE html>
<html>
<head>
    <title>Ledger Nilai</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .nilai { text-align: center; font-family: monospace; font-size: 13px; }
        .nilai-akhir { background-color: #f9f9f9; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LEDGER NILAI {{ strtoupper($jadwal->jenis_ujian) }}</h2>
        <p><strong>Mata Pelajaran:</strong> {{ $jadwal->mapel->nama_mapel }} ({{ $jadwal->mapel->nama_kitab }})</p>
        <p><strong>Kelas:</strong> {{ $jadwal->mustawa->nama }} | <strong>Tahun Ajaran:</strong> {{ $jadwal->tahun_ajaran }} ({{ ucfirst($jadwal->semester) }})</p>
        <p><strong>Guru Pengampu:</strong> {{ $jadwal->pengawas->nama_lengkap }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Santri</th>
                <th width="10%">NIS</th>
                <th width="12%">Nilai Tulis</th>
                <th width="12%">Nilai Lisan</th>
                <th width="12%">Nilai Praktek</th>
                <th width="12%" style="background-color: #e6fffa;">Kehadiran</th>
                <th width="12%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $santri)
                @php 
                    $n = $santri->nilai_ujian; 
                    // Format angka agar rapi (hilangkan .00 jika bulat)
                    $tulis = $n ? (float)$n->nilai_tulis : 0;
                    $lisan = $n ? (float)$n->nilai_lisan : 0;
                    $praktek = $n ? (float)$n->nilai_praktek : 0;
                    $hadir = $n ? (float)$n->nilai_kehadiran : 0;
                    $akhir = $n ? (float)$n->nilai_akhir : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $santri->full_name }}</td>
                    <td class="text-center">{{ $santri->nis }}</td>
                    
                    {{-- Kolom Nilai --}}
                    <td class="nilai">{{ $tulis > 0 ? $tulis : '-' }}</td>
                    <td class="nilai">{{ $lisan > 0 ? $lisan : '-' }}</td>
                    <td class="nilai">{{ $praktek > 0 ? $praktek : '-' }}</td>
                    
                    {{-- Kolom Kehadiran (Highlight Hijau Tipis) --}}
                    <td class="nilai" style="background-color: #f0fdf4;">
                        {{ $hadir > 0 ? $hadir.'%' : '-' }}
                    </td>
                    
                    {{-- Nilai Akhir --}}
                    <td class="nilai nilai-akhir">
                        {{ $akhir > 0 ? number_format($akhir, 2) : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tanda Tangan (Khusus PDF) --}}
    @if(!isset($isExcel))
    <div style="margin-top: 40px; page-break-inside: avoid;">
        <table style="border: none; margin-top: 20px;">
            <tr style="border: none;">
                <td style="border: none; width: 70%;"></td>
                <td style="border: none; text-align: center; width: 30%;">
                    <p>Dicetak pada: {{ date('d M Y') }}</p>
                    <br><br><br>
                    <p><strong>{{ $jadwal->pengawas->nama_lengkap }}</strong></p>
                    <p>Pengawas / Penguji</p>
                </td>
            </tr>
        </table>
    </div>
    @endif
</body>
</html>