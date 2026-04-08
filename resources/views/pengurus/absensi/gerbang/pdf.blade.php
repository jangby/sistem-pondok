<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Penjaga Gerbang - {{ $namaBulan }}</title>
    <style>
        /* Pengaturan Dasar */
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 11pt; 
            color: #333; 
            line-height: 1.5;
        }
        
        /* Kop Surat */
        .kop-surat { 
            width: 100%; 
            border-bottom: 3px solid #047857; /* Warna Emerald */
            padding-bottom: 15px; 
            margin-bottom: 25px; 
        }
        .kop-surat td { vertical-align: middle; }
        .logo-container { width: 15%; text-align: center; }
        .logo { width: 80px; height: auto; }
        .school-header { width: 85%; text-align: center; padding-right: 15%; }
        .school-name { 
            font-size: 20pt; 
            font-weight: bold; 
            color: #047857; 
            margin: 0; 
            letter-spacing: 1px;
            text-transform: uppercase; 
        }
        .school-address { 
            font-size: 10pt; 
            color: #555; 
            margin: 5px 0 0 0; 
        }

        /* Judul Laporan */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h3 { 
            margin: 0; 
            font-size: 14pt; 
            text-transform: uppercase; 
            color: #111; 
            text-decoration: underline;
        }
        .report-title p { margin: 8px 0 0 0; font-size: 11pt; color: #444; }

        /* Tabel Data */
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
            font-size: 10pt;
        }
        .table-data th, .table-data td { 
            border: 1px solid #cbd5e1; 
            padding: 10px 8px; 
        }
        .table-data th { 
            background-color: #047857; 
            color: #ffffff; 
            font-weight: bold; 
            text-align: center; 
            text-transform: uppercase; 
            font-size: 9.5pt;
            letter-spacing: 0.5px;
        }
        .table-data tbody tr:nth-child(even) { background-color: #f8fafc; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        /* Highlight Teks */
        .text-bold { font-weight: bold; }
        .text-green { color: #059669; }
        .text-orange { color: #d97706; }

        /* Bagian Tanda Tangan */
        .footer { margin-top: 40px; width: 100%; }
        .ttd-container { width: 280px; float: right; text-align: center; font-size: 11pt; }
        .ttd-container p { margin: 0 0 5px 0; }
        .ttd-date { margin-bottom: 15px !important; }
        .ttd-name { 
            font-weight: bold; 
            text-decoration: underline; 
            margin-top: 70px !important; 
            text-transform: uppercase;
        }
        .clearfix::after { content: ""; clear: both; display: table; }

        /* Waktu Cetak */
        .print-info {
            position: fixed;
            bottom: -20px;
            left: 0;
            font-size: 8pt;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td class="logo-container">
                @php $logoPath = public_path('logo-pondok.jpg'); @endphp
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" class="logo" alt="Logo Pondok">
                @endif
            </td>
            <td class="school-header">
                <h1 class="school-name">Sistem Informasi Pondok</h1>
                <p class="school-address">
                    Jl Raya Limbangan Tengah N0 104, Ds. Limbangan Tengah Kec. Bl Limbangan<br>
                    Website: www.ponpesassaadah.com | Email: assaadah@pondok.com
                </p>
            </td>
        </tr>
    </table>

    <div class="report-title">
        <h3>Laporan Kinerja Penjaga Gerbang</h3>
        <p>Periode Bulan: <span class="text-bold">{{ $namaBulan }}</span></p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%" class="text-left">Nama Petugas (Santri)</th>
                <th width="20%">Total Hadir Pagi</th>
                <th width="20%">Total Hadir Sore</th>
                <th width="20%">Total Jadwal Dinas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekap as $index => $data)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-bold">{{ $data['nama'] }}</td>
                    <td class="text-center text-bold text-green">{{ $data['hadir_pagi'] }} Kali</td>
                    <td class="text-center text-bold text-orange">{{ $data['hadir_sore'] }} Kali</td>
                    <td class="text-center">{{ $data['total_tugas'] }} Hari</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 30px 0; color: #64748b; font-style: italic;">
                        Tidak ada data absensi pada periode bulan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer clearfix">
        <div class="ttd-container">
            <p class="ttd-date">Mengetahui,<br>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
            <p><strong>Pengurus Keamanan Pondok</strong></p>
            <p class="ttd-name">Admin Keamanan</p>
            <p style="font-size: 10pt;">NIP: .......................................</p>
        </div>
    </div>

    <div class="print-info">
        Dokumen ini dicetak secara otomatis dari Sistem pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB.
    </div>

</body>
</html>