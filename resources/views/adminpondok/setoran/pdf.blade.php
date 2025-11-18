<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Setoran #{{ $setoran->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #059669; padding-bottom: 10px; }
        .header h1 { font-size: 24px; margin: 0; color: #059669; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }

        .info-box { width: 100%; margin-bottom: 20px; background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px; }
        .info-box h3 { margin: 0 0 5px 0; color: #166534; font-size: 18px; }
        .info-box p { margin: 0; font-size: 12px; }

        .section-title { font-size: 14px; font-weight: bold; margin: 20px 0 10px 0; color: #059669; text-transform: uppercase; border-bottom: 1px solid #ddd; padding-bottom: 5px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
        th { background-color: #059669; color: white; font-size: 11px; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #f9fafb; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        .footer { margin-top: 40px; width: 100%; text-align: center; }
        .signature-box { width: 30%; float: right; text-align: center; margin-top: 20px; }
        .signature-line { margin-top: 60px; border-top: 1px solid #333; width: 80%; margin-left: auto; margin-right: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bukti Laporan Setoran</h1>
            <p>ID Laporan: #{{ $setoran->id }} | Tanggal: {{ \Carbon\Carbon::parse($setoran->tanggal_setoran)->format('d F Y') }}</p>
        </div>

        <div class="info-box">
            <table width="100%" style="border: none;">
                <tr style="background: transparent;">
                    <td style="border: none; width: 60%;">
                        <h3>Total Disetor: Rp {{ number_format($setoran->total_setoran, 0, ',', '.') }}</h3>
                        <p>Terbilang: <em>{{ ucwords(Terbilang::make($setoran->total_setoran)) }} Rupiah</em></p>
                    </td>
                    <td style="border: none; text-align: right;">
                        <p><strong>Penyetor:</strong> {{ $setoran->admin->name }}</p>
                        <p><strong>Kategori:</strong> {{ ucwords(str_replace('_', ' ', $setoran->kategori_setoran)) }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-title">Ringkasan Pemasukan per Item</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Item Pembayaran</th>
                    <th class="text-right">Total Terkumpul</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summaryPerItem as $item)
                    <tr>
                        <td>{{ $item->nama_item }}</td>
                        <td class="text-right font-bold">Rp {{ number_format($item->total_terkumpul, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="section-title">Rincian Transaksi Santri</div>
        {{-- Gunakan page-break-inside: avoid jika ingin tabel tidak terpotong jelek --}}
        
        <div style="width: 48%; float: left; margin-right: 2%;">
            <h4 style="margin: 5px 0; text-align: center; background: #eee; padding: 5px;">Santri Putra</h4>
            <table>
                <thead>
                    <tr>
                        <th>Nama / Rincian</th>
                        <th class="text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($santriPutra as $santri)
                        <tr style="background-color: #f0fdf4;">
                            <td class="font-bold">{{ $santri['nama'] }}</td>
                            <td class="text-right font-bold">{{ number_format($santri['total'], 0, ',', '.') }}</td>
                        </tr>
                        @foreach($santri['rincian'] as $transaksi)
                            <tr>
                                <td style="padding-left: 15px; font-size: 10px; color: #555;">
                                    - {{ $transaksi->tagihan->jenisPembayaran->name }}
                                </td>
                                <td class="text-right" style="font-size: 10px; color: #555;">
                                    {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr><td colspan="2" class="text-center">- Tidak ada data -</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="width: 48%; float: right;">
            <h4 style="margin: 5px 0; text-align: center; background: #eee; padding: 5px;">Santri Putri</h4>
            <table>
                <thead>
                    <tr>
                        <th>Nama / Rincian</th>
                        <th class="text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($santriPutri as $santri)
                        <tr style="background-color: #f0fdf4;">
                            <td class="font-bold">{{ $santri['nama'] }}</td>
                            <td class="text-right font-bold">{{ number_format($santri['total'], 0, ',', '.') }}</td>
                        </tr>
                        @foreach($santri['rincian'] as $transaksi)
                            <tr>
                                <td style="padding-left: 15px; font-size: 10px; color: #555;">
                                    - {{ $transaksi->tagihan->jenisPembayaran->name }}
                                </td>
                                <td class="text-right" style="font-size: 10px; color: #555;">
                                    {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr><td colspan="2" class="text-center">- Tidak ada data -</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="clear: both;"></div>

        <div class="signature-box">
            <p>Mengetahui,<br>Bendahara</p>
            <div class="signature-line"></div>
            <p>( .................................... )</p>
        </div>
    </div>
</body>
</html>