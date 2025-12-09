<!DOCTYPE html>
<html>
<head>
    <title>Daftar Remedial</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h2, .header h3 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; }
        th { background-color: #f0f0f0; }
        .meta-info { margin-bottom: 15px; }
        .meta-info td { border: none; padding: 2px; }
    </style>
</head>
<body>

    <div class="header">
        <h3>{{ strtoupper($pondok->nama_pondok) }}</h3>
        <p>{{ $pondok->alamat }}</p>
        <hr>
        <h3>DAFTAR PESERTA REMEDIAL</h3>
    </div>

    <table class="meta-info" style="border: none;">
        <tr>
            <td width="150">Mata Pelajaran</td>
            <td>: {{ $selectedMapel->nama_mapel }}</td>
        </tr>
        <tr>
            <td>Kategori Ujian</td>
            <td>: {{ ucfirst($request->kategori) }}</td>
        </tr>
        <tr>
            <td>Kelas / Semester</td>
            <td>: {{ $selectedMustawa->nama }} / {{ ucfirst($request->semester) }}</td>
        </tr>
        <tr>
            <td>KKM</td>
            <td>: {{ $kkm }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%" style="text-align: center;">No</th>
                <th width="15%" style="text-align: center;">NIS</th>
                <th>Nama Santri</th>
                <th width="15%" style="text-align: center;">Nilai Awal</th>
                <th width="15%" style="text-align: center;">Paraf</th>
                <th width="15%" style="text-align: center;">Nilai Perbaikan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($remedialList as $item)
                @php 
                    $col = 'nilai_' . strtolower($request->kategori);
                    $nilai = $item->$col;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="text-align: center;">{{ $item->santri->nis }}</td>
                    <td>{{ $item->santri->full_name }}</td>
                    <td style="text-align: center; color: red;">{{ $nilai }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px; float: right; width: 200px; text-align: center;">
        <p>{{ $pondok->kota ?? 'Limbangan' }}, {{ date('d F Y') }}</p>
        <p>Penguji,</p>
        <br><br><br>
        <p>(....................................)</p>
    </div>

</body>
</html>