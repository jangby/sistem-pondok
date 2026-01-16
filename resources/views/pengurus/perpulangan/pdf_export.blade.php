<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perpulangan</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .section-title { font-size: 12pt; font-weight: bold; margin-top: 20px; margin-bottom: 10px; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; font-size: 9pt; }
        th { background-color: #f0f0f0; }
        .text-center { text-align: center; }
        .text-red { color: red; font-weight: bold; }
        .text-green { color: green; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">Laporan Data Perpulangan</h2>
        <h3 style="margin:5px 0;">{{ $event->judul }}</h3>
        <p>Filter: {{ $status == 'all' ? 'Semua Data' : ucwords(str_replace('_', ' ', $status)) }}</p>
    </div>

    @foreach(['Putra' => $recordsPutra, 'Putri' => $recordsPutri] as $label => $data)
        <div class="section-title">Data Santri {{ $label }}</div>
        
        @if($data->isEmpty())
            <p><i>Tidak ada data untuk kategori ini.</i></p>
        @else
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%" class="text-center">No</th>
                        <th style="width: 25%">Nama</th>
                        <th style="width: 10%">Kelas</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 15%">Tgl Kembali</th>
                        <th style="width: 15%">Keterlambatan</th>
                        <th style="width: 15%">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $rec)
                    @php
                        // Logic Hitung Telat untuk View
                        $hariTerlambat = 0;
                        $batas = \Carbon\Carbon::parse($event->tanggal_akhir)->endOfDay();
                        
                        if ($rec->waktu_kembali) {
                            $kembali = \Carbon\Carbon::parse($rec->waktu_kembali);
                            if ($kembali->gt($batas)) {
                                $hariTerlambat = $batas->diffInDays($kembali);
                            }
                        } else {
                            // Jika belum kembali & hari ini > batas, maka telat berjalan
                            if (\Carbon\Carbon::now()->gt($batas) && $rec->status != 2) {
                                $hariTerlambat = $batas->diffInDays(\Carbon\Carbon::now());
                            }
                        }

                        // Logic Status Text
                        $statusText = match ($rec->status) {
                            0 => 'Belum Pulang',
                            1 => 'Sedang Pulang',
                            2 => 'Sudah Kembali',
                            default => '-'
                        };
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            {{ $rec->santri->full_name }}<br>
                            <small>{{ $rec->santri->nis }}</small>
                        </td>
                        <td>{{ $rec->santri->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $statusText }}</td>
                        <td>{{ $rec->waktu_kembali ? \Carbon\Carbon::parse($rec->waktu_kembali)->format('d/m/y H:i') : '-' }}</td>
                        <td>
                            @if($hariTerlambat > 0)
                                <span class="text-red">{{ $hariTerlambat }} Hari</span>
                            @else
                                <span class="text-green">Tepat Waktu</span>
                            @endif
                        </td>
                        <td>{{ $rec->santri->alamat ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

</body>
</html>