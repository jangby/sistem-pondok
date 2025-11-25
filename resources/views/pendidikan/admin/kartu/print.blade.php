<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Ujian</title>
    <style>
        @page {
            size: A4 portrait; /* Kertas A4 Berdiri */
            margin: 0; /* Hilangkan margin default browser */
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            background: #fff;
            -webkit-print-color-adjust: exact; /* Pastikan warna/gambar tercetak */
        }
        
        /* Container Halaman A4 */
        .page {
            width: 210mm;
            height: 297mm;
            padding: 10mm; /* Margin aman sisi kertas */
            box-sizing: border-box;
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 Kolom */
            grid-template-rows: repeat(4, 1fr);    /* 4 Baris */
            gap: 5mm; /* Jarak antar kartu */
            page-break-after: always; /* Pindah kertas setelah 8 kartu */
        }

        /* Desain Kartu */
        .card {
            border: 2px solid #000;
            padding: 5px 10px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-sizing: border-box;
            border-radius: 4px;
        }

        /* Kop Kartu */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }
        .logo {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }
        .kop-text {
            text-align: center;
            flex: 1;
            text-transform: uppercase;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 11pt;
            font-weight: bold;
            color: #004d40; /* Warna Hijau Tua khas Pondok */
        }
        .kop-text h3 {
            margin: 2px 0 0;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .kop-text p {
            margin: 0;
            font-size: 7pt;
            text-transform: none;
        }

        /* Isi Kartu */
        .content {
            flex: 1;
            font-size: 10pt;
        }
        .content table {
            width: 100%;
        }
        .content td {
            padding: 2px 0;
            vertical-align: top;
        }
        .label {
            width: 80px;
            font-weight: bold;
        }
        .titik {
            width: 10px;
            text-align: center;
        }

        /* Footer & TTD */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            font-size: 9pt;
        }
        .nomor-ujian {
            border: 1px solid #000;
            padding: 5px;
            font-weight: bold;
            font-size: 12pt;
            background: #f0f0f0;
        }
        .ttd-box {
            text-align: center;
            width: 160px; 
            margin-right: 5px;
        }
        .ttd-img {
            height: 40px; /* Sesuaikan tinggi TTD */
            display: block;
            margin: 0 auto;
        }
        .tgl {
            margin-bottom: 2px;
            font-size: 8pt;
        }
        .panitia {
            font-weight: bold;
            border-top: 1px solid #000;
            display: inline-block;
            width: 100%;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    {{-- Loop Utama: Membagi santri per 8 orang (1 halaman) --}}
    @foreach($santris->chunk(8) as $chunk)
    <div class="page">
        @foreach($chunk as $santri)
        <div class="card">
            
            {{-- Header / Kop --}}
            <div class="header">
                <img src="https://raw.githubusercontent.com/Dhuyuand/aset-cerdas-cermat/main/Logo.png" class="logo" alt="Logo Pondok">
                <div class="kop-text">
                    <h2>PONDOK PESANTREN ASSA'ADAH</h2>
                    <h3>KARTU PESERTA UJIAN</h3>
                    <p>
                        {{ $ujian->nama ?? 'Ujian Diniyah' }} 
                        | Tahun Ajaran {{ $ujian->tahun_ajaran ?? date('Y/Y+1') }}
                    </p>
                </div>
            </div>

            {{-- Body / Data Santri --}}
            <div class="content">
                <table>
                    <tr>
                        <td class="label">Nama</td>
                        <td class="titik">:</td>
                        <td style="text-transform: uppercase;"><strong>{{ $santri->full_name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">NIS/ID</td>
                        <td class="titik">:</td>
                        <td>{{ $santri->nis }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kelas</td>
                        <td class="titik">:</td>
                        <td>{{ $santri->kelas->nama ?? $santri->mustawa->nama ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            {{-- Footer / TTD --}}
            <div class="footer">
                
                {{-- Kiri: Kotak Nomor Ujian (Opsional, bisa dihapus kalau tidak perlu) --}}
                <div class="nomor-ujian">
                   {{ substr($santri->nis, -4) }} </div>

                {{-- Kanan: Tanda Tangan --}}
                <div class="ttd-box">
                    <div class="tgl">Limbangan, {{ date('d M Y') }}</div>
                    <div class="tgl">Ketua Panitia Ujian</div>
                    <img src="https://raw.githubusercontent.com/Dhuyuand/aset-cerdas-cermat/main/TTD.png" class="ttd-img" alt="TTD Panitia">
                    <div class="panitia">Ihsan</div>
                </div>
            </div>

        </div>
        @endforeach
    </div>
    @endforeach

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>