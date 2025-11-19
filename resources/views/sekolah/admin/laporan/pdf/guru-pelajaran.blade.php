<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $judul }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; vertical-align: middle; }
        th { background-color: #e0e0e0; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .nama { text-align: left; }
        .detail { text-align: left; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $judul }}</h2>
        <h3>{{ $sekolah->nama_sekolah }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th class="nama">Nama Guru</th>
                <th>Total Jam Mengajar</th>
                <th>Hadir</th>
                <th>Terlambat</th>
                <th>Digantikan</th>
                <th>Alpa/Kosong</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                @php
                    $logs = $item['logs'];
                    $totalJadwal = $logs->count();
                    $hadir = $logs->where('status_guru', 'hadir')->count();
                    $terlambat = $logs->where('status_guru', 'terlambat')->count(); // Asumsi ada status 'terlambat'
                    $digantikan = $logs->where('is_substitute', true)->count(); // Jika ada flag ini
                    // Alpa di sini berarti status 'alpa' atau 'sakit'/'izin' tanpa pengganti
                    $kosong = $logs->whereIn('status_guru', ['alpa', 'sakit', 'izin'])->where('guru_pengganti_user_id', null)->count();
                    
                    // Total jam efektif = Hadir + Terlambat (yang dia ajar sendiri)
                    $jamEfektif = $hadir + $terlambat;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="nama">{{ $item['guru']->name }}</td>
                    <td>{{ $totalJadwal }} Kelas</td>
                    <td>{{ $jamEfektif }}</td>
                    <td>{{ $terlambat }}</td>
                    <td>{{ $digantikan }}</td>
                    <td style="color: red;">{{ $kosong }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; font-size: 10px;">
        <p>* Laporan ini menghitung berdasarkan jadwal pelajaran yang tercatat di sistem.</p>
    </div>
</body>
</html>