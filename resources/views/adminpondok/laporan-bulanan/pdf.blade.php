<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judulLaporan }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 10px; border-bottom: 2px solid #059669; }
        .header h1 { margin: 0 0 5px 0; font-size: 22px; color: #059669; text-transform: uppercase; }
        .header p { margin: 0; font-size: 12px; color: #666; }
        
        .meta-info { margin-bottom: 20px; width: 100%; }
        .meta-info td { padding: 2px 0; }
        
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: left; }
        table.data th { background-color: #059669; color: white; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        table.data tr:nth-child(even) { background-color: #f9fafb; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-paid { background-color: #d1fae5; color: #065f46; } /* Hijau Muda */
        .badge-unpaid { background-color: #fee2e2; color: #991b1b; } /* Merah Muda */
        
        .summary-section { margin-top: 20px; page-break-inside: avoid; }
        .summary-table { width: 40%; margin-left: auto; border-collapse: collapse; }
        .summary-table td { padding: 5px; border-bottom: 1px solid #eee; }
        .summary-table .label { font-weight: bold; color: #555; }
        .summary-table .value { text-align: right; font-weight: bold; }
        .total-row td { border-top: 2px solid #333; font-size: 14px; padding-top: 10px; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 10px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    
    <div class="header">
        <h1>{{ $judulLaporan }}</h1>
        <p>{{ config('app.name', 'Pondok Pesantren') }}</p>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%"><strong>Tanggal Cetak</strong></td>
            <td>: {{ now()->format('d F Y, H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Pencetak</strong></td>
            <td>: {{ auth()->user()->name }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 25%;">Nama Santri</th>
                <th style="width: 15%;">NIS / Kelas</th>
                <th style="width: 15%;" class="text-center">Status</th>
                <th style="width: 20%;" class="text-right">Nominal Tagihan</th>
                <th style="width: 20%;" class="text-center">Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @forelse ($laporanData as $item)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>
                        <strong>{{ $item['santri']->full_name }}</strong>
                    </td>
                    <td>
                        {{ $item['santri']->nis }}<br>
                        <small style="color: #666;">{{ $item['santri']->class ?? '-' }}</small>
                    </td>
                    <td class="text-center">
                        @if ($item['status'] == 'paid')
                            <span class="badge badge-paid">Lunas</span>
                        @elseif ($item['status'] == 'pending' || $item['status'] == 'partial')
                            <span class="badge badge-unpaid">Belum Lunas</span>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($item['nominal_tagihan'], 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        {{ $item['tgl_bayar'] ? $item['tgl_bayar']->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #777;">
                        Tidak ada data santri yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-section">
        <table class="summary-table">
            <tr>
                <td class="label">Total Santri</td>
                <td class="value">{{ $summary['total_santri'] }}</td>
            </tr>
            <tr>
                <td class="label">Sudah Lunas</td>
                <td class="value" style="color: #059669;">{{ $summary['total_lunas'] }}</td>
            </tr>
            <tr>
                <td class="label">Belum Lunas</td>
                <td class="value" style="color: #dc2626;">{{ $summary['total_belum_lunas'] }}</td>
            </tr>
            <tr class="total-row">
                <td class="label">Potensi Tunggakan</td>
                <td class="value" style="color: #dc2626;">Rp {{ number_format($summary['total_tunggakan'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak otomatis oleh sistem pada {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>