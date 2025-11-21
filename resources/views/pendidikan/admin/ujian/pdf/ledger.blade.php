<!DOCTYPE html>
<html>
<head>
    <title>Ledger Nilai</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LEDGER NILAI UJIAN {{ strtoupper($jadwal->jenis_ujian) }}</h2>
        <p>{{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->mustawa->nama }}</p>
        <p>Tahun Ajaran: {{ $jadwal->tahun_ajaran }} ({{ ucfirst($jadwal->semester) }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Santri</th>
                <th>NIS</th>
                <th>Nilai Tulis</th>
                <th>Nilai Lisan</th>
                <th>Nilai Praktek</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $santri)
                @php $n = $santri->nilai_ujian; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $santri->full_name }}</td>
                    <td>{{ $santri->nis }}</td>
                    <td>{{ $n->nilai_tulis ?? 0 }}</td>
                    <td>{{ $n->nilai_lisan ?? 0 }}</td>
                    <td>{{ $n->nilai_praktek ?? 0 }}</td>
                    <td><strong>{{ $n->nilai_akhir ?? 0 }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>