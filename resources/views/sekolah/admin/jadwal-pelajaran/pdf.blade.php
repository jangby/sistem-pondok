<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Pelajaran</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10pt; color: #555; }
        
        .class-section { margin-bottom: 30px; page-break-inside: avoid; }
        .class-title { background-color: #f3f4f6; padding: 8px; font-weight: bold; border: 1px solid #ddd; border-bottom: none; font-size: 11pt; color: #111827; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #f9fafb; font-weight: bold; text-transform: uppercase; font-size: 8pt; color: #374151; }
        
        /* Warna Badge Hari (Visual sederhana untuk print) */
        .day-badge { font-weight: bold; text-transform: uppercase; font-size: 8pt; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 8pt; color: #999; text-align: right; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $sekolah->nama_sekolah }}</h1>
        <p>{{ $sekolah->alamat ?? 'Alamat Sekolah' }}</p>
        <p><strong>Jadwal Pelajaran - Tahun Ajaran {{ $tahunAjaranAktif->nama_tahun }}</strong></p>
    </div>

    @foreach($jadwals as $namaKelas => $listJadwal)
        <div class="class-section">
            <div class="class-title">KELAS: {{ $namaKelas }}</div>
            <table>
                <thead>
                    <tr>
                        <th width="10%">Hari</th>
                        <th width="15%">Waktu</th>
                        <th width="35%">Mata Pelajaran</th>
                        <th width="40%">Guru Pengajar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listJadwal as $jadwal)
                    <tr>
                        <td class="day-badge">{{ $jadwal->hari }}</td>
                        <td style="font-family: monospace;">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </td>
                        <td style="font-weight: bold;">{{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}</td>
                        <td>{{ $jadwal->guru->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }} | Sistem Sekolah
    </div>

</body>
</html>