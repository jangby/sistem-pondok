<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Diniyah</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; }
        h1 { margin: 0 0 6px; font-size: 18px; }
        p { margin: 0 0 14px; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <h1>Rekap Absensi Diniyah</h1>
    <p>
        Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
        @if($mustawa)
            | Kelas: {{ $mustawa->nama }}
        @else
            | Semua kelas
        @endif
    </p>
    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Santri</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Pengajar</th>
                <th class="center">Status</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->waktu_scan?->format('H:i') ?? '-' }}</td>
                    <td>{{ $log->santri->full_name ?? '-' }}</td>
                    <td>{{ $log->santri->nis ?? '-' }}</td>
                    <td>{{ $log->jadwal->mustawa->nama ?? '-' }}</td>
                    <td>{{ $log->jadwal->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $log->jadwal->ustadz->nama_lengkap ?? '-' }}</td>
                    <td class="center">
                        {{ ['H' => 'Hadir', 'I' => 'Izin', 'S' => 'Sakit', 'A' => 'Alpha'][$log->status] ?? $log->status }}
                    </td>
                    <td>{{ strtoupper($log->metode ?? 'manual') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center">Tidak ada data absensi pada tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
