<!DOCTYPE html>
<html>
<head>
    <title>Data Perpulangan</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">Laporan Data Perpulangan</h2>
        <h3 style="margin:5px 0;">{{ $event->judul }}</h3>
        <p>Filter: {{ ucwords(str_replace('_', ' ', $status == 'all' ? 'Semua Data' : $status)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama</th>
                <th style="width: 10%">NISM</th>
                <th style="width: 10%">Hari Telat</th>
                <th style="width: 15%">Tgl Kembali</th>
                <th style="width: 40%">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $rec)
            @php
                $hariTerlambat = 0;
                if ($rec->waktu_kembali && $event->tanggal_akhir) {
                    $batas = \Carbon\Carbon::parse($event->tanggal_akhir);
                    $kembali = \Carbon\Carbon::parse($rec->waktu_kembali);
                    if ($kembali->gt($batas)) {
                        $hariTerlambat = $batas->diffInDays($kembali);
                    }
                }
                
                $alamat = $rec->santri->alamat ?? '';
                $alamat .= $rec->santri->desa ? ", {$rec->santri->desa}" : '';
                $alamat .= $rec->santri->kecamatan ? ", {$rec->santri->kecamatan}" : '';
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $rec->santri->full_name }}</td>
                <td>{{ $rec->santri->nis }}</td>
                <td>{{ $hariTerlambat }} Hari</td>
                <td>{{ $rec->waktu_kembali ? \Carbon\Carbon::parse($rec->waktu_kembali)->format('d/m/y') : '-' }}</td>
                <td>{{ $alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>