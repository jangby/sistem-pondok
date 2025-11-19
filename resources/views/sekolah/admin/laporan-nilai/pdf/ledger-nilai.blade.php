<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $judul }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }
        th { background-color: #e0e0e0; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 2px; }
        .keterangan { margin-top: 15px; font-size: 10px; }
        .nama { text-align: left; width: 40%; }
        .nilai { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $judul }}</h2>
        <h3>Kelas: {{ $kelas->nama_kelas }} | Periode: {{ $kegiatan->tanggal_mulai->format('d M Y') }} - {{ $kegiatan->tanggal_selesai->format('d M Y') }}</h3>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th class="nama">Nama Siswa</th>
                <th style="width: 15%;">NIS</th>
                <th style="width: 15%;">Nilai (0-100)</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($santris as $index => $santri)
                @php
                    $nilai = $existingNilai[$santri->id] ?? null;
                    $isMissing = is_null($nilai);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="nama">{{ $santri->full_name }}</td>
                    <td>{{ $santri->nis }}</td>
                    <td class="nilai" style="{{ $isMissing ? 'background-color: #f8d7da;' : '' }}">
                        {{ $isMissing ? 'BELUM' : number_format($nilai, 1) }}
                    </td>
                    <td style="text-align: left;">
                        {{ $isMissing ? 'Nilai belum diinput oleh guru.' : '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="keterangan">
        <p>Laporan ini mencakup semua siswa aktif di Kelas {{ $kelas->nama_kelas }}.</p>
    </div>
</body>
</html>