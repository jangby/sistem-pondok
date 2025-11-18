<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judulLaporan }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; text-transform: uppercase; }
        .header p { margin: 2px 0; color: #555; font-size: 9pt; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; font-size: 9pt; }
        th { background-color: #f0f0f0; text-transform: uppercase; font-size: 8pt; }
        td.left { text-align: left; }
        
        /* Status Colors (Optional for color printer) */
        .status-H { color: green; font-weight: bold; }
        .status-A { color: red; font-weight: bold; background-color: #ffecec; }
        .status-S { color: orange; font-weight: bold; }
        .status-I { color: blue; font-weight: bold; }
        .status-TM { color: #ccc; font-size: 8pt; }
        
        .footer { font-size: 8pt; text-align: right; color: #777; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $judulLaporan }}</h2>
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }} | Oleh: {{ Auth::user()->name }}</p>
        @if($isRentang)
            <p>Kategori: {{ $listKategori[$kategoriFilter] ?? 'Semua' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 150px;">Nama Santri</th>
                <th style="width: 60px;">Kelas</th>
                <th style="width: 30px;">L/P</th>
                @foreach($headerKolom as $label)
                    <th>{{ $label }}</th>
                @endforeach
                {{-- Kolom Rekap Total (Hanya di PDF biar ringkas) --}}
                <th style="width: 30px;">H</th>
                <th style="width: 30px;">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ledger as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left">{{ $row['nama'] }}</td>
                    <td>{{ $row['kelas'] }}</td>
                    <td>{{ $row['gender'] == 'Laki-laki' ? 'L' : 'P' }}</td>
                    
                    @php $totalH = 0; $totalA = 0; @endphp

                    @foreach($headerKolom as $key => $label)
                        @php 
                            $val = $row['data'][$key];
                            $status = $val['status'];
                            if($status == 'H') $totalH++;
                            if($status == 'A') $totalA++;
                        @endphp
                        <td class="status-{{ $status }}">
                            {{ $status }}
                        </td>
                    @endforeach
                    
                    {{-- Hitung Total --}}
                    <td><strong>{{ $totalH }}</strong></td>
                    <td style="{{ $totalA > 0 ? 'color:red; font-weight:bold;' : '' }}">{{ $totalA }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 10px; font-size: 9pt;">
        <strong>Keterangan:</strong><br>
        H = Hadir &nbsp;&nbsp; A = Alpha (Tanpa Keterangan) &nbsp;&nbsp; S = Sakit &nbsp;&nbsp; I = Izin &nbsp;&nbsp; TM = Tidak Mengikuti
    </div>

</body>
</html>