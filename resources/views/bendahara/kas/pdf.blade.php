<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Kas</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #059669; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #059669; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; }
        th { background-color: #059669; color: white; text-transform: uppercase; font-size: 10px; }
        tr:nth-child(even) { background-color: #f3f4f6; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-green { color: #059669; }
        .text-red { color: #dc2626; }
        .font-bold { font-weight: bold; }

        .saldo-box { 
            width: 100%; 
            margin-bottom: 20px; 
            background-color: #ecfdf5; 
            border: 1px solid #a7f3d0; 
            padding: 10px; 
            font-size: 12px;
        }
        .saldo-box table td { border: none; padding: 2px 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Buku Kas</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}</p>
    </div>

    <div class="saldo-box">
        <table style="width: 50%; float: right; margin: 0;">
            <tr style="background: transparent;">
                <td><strong>Saldo Awal:</strong></td>
                <td class="text-right">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
            </tr>
            <tr style="background: transparent;">
                <td><strong>Total Pemasukan (+) :</strong></td>
                <td class="text-right text-green">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr style="background: transparent;">
                <td><strong>Total Pengeluaran (-) :</strong></td>
                <td class="text-right text-red">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr style="background: transparent; border-top: 1px solid #059669;">
                <td><strong>Saldo Akhir:</strong></td>
                <td class="text-right font-bold">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="12%">Tanggal</th>
                <th>Uraian / Deskripsi</th>
                <th class="text-right" width="18%">Pemasukan (Debit)</th>
                <th class="text-right" width="18%">Pengeluaran (Kredit)</th>
                <th class="text-right" width="18%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            {{-- Baris Saldo Awal --}}
            <tr style="background-color: #e5e7eb; font-weight: bold;">
                <td colspan="4" class="text-right">Saldo Awal</td>
                <td class="text-right">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
            </tr>

            @php $saldoBerjalan = $saldoAwal; @endphp
            
            @forelse ($transaksis as $trx)
                @php
                    if ($trx->tipe == 'pemasukan') {
                        $saldoBerjalan += $trx->nominal;
                    } else {
                        $saldoBerjalan -= $trx->nominal;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</td>
                    <td>{{ $trx->deskripsi }}</td>
                    
                    <td class="text-right text-green">
                        @if($trx->tipe == 'pemasukan')
                            Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                        @else - @endif
                    </td>
                    
                    <td class="text-right text-red">
                        @if($trx->tipe == 'pengeluaran')
                            Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                        @else - @endif
                    </td>
                    
                    <td class="text-right font-bold">
                        Rp {{ number_format($saldoBerjalan, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>