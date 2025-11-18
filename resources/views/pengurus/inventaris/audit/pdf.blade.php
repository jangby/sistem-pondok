<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekonsiliasi Stok</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0; color: #555; font-size: 9pt; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #555; padding: 6px; text-align: left; vertical-align: middle; }
        th { background-color: #eee; text-transform: uppercase; font-size: 9pt; }
        
        /* Utility Colors */
        .text-red { color: #dc2626; font-weight: bold; }
        .text-green { color: #16a34a; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-small { font-size: 8pt; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN REKONSILIASI STOK (AUDIT)</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <p>Dicetak Oleh: {{ Auth::user()->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Tanggal</th>
                <th width="25%">Nama Barang</th>
                <th width="15%">Lokasi</th>
                <th width="15%">Penanggung Jawab</th>
                <th width="8%" class="text-center">Sistem</th>
                <th width="8%" class="text-center">Fisik</th>
                <th width="12%" class="text-center">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @foreach($audits as $index => $a)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $a->audit_date->format('d/m/Y') }}</td>
                    <td>
                        {{ $a->barang->name }}
                        <br><span class="text-small">{{ $a->barang->code }}</span>
                    </td>
                    <td>{{ $a->barang->location->name ?? '-' }}</td>
                    <td>
                        {{-- Tampilkan Nama PIC --}}
                        {{ $a->barang->pic->full_name ?? 'Tidak Ada PIC' }}
                    </td>
                    <td class="text-center">{{ $a->expected_qty }}</td>
                    <td class="text-center">{{ $a->actual_qty }}</td>
                    <td class="text-center {{ $a->difference < 0 ? 'text-red' : 'text-green' }}">
                        {{ $a->difference > 0 ? '+' : '' }}{{ $a->difference }}
                        <br>
                        <span class="text-small" style="font-weight: normal; color: #333;">
                            @if($a->difference < 0) Hilang @elseif($a->difference > 0) Lebih @else Cocok @endif
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 30px; font-size: 9pt;">
        <p><strong>Catatan:</strong> Data stok sistem telah disesuaikan secara otomatis mengikuti jumlah fisik (rekonsiliasi) pada saat audit dilakukan.</p>
    </div>
</body>
</html>