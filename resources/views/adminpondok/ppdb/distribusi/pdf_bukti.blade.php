<!DOCTYPE html>
<html>
<head>
    <title>Berita Acara Serah Terima - {{ $transaksi->id }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            line-height: 1.5;
            color: #000;
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .title-doc {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 18px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .nomor-doc {
            text-align: center;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 30px;
            text-align: justify;
        }
        .table-info {
            width: 100%;
            margin: 10px 0 20px 0;
            border-collapse: collapse;
        }
        .table-info td {
            padding: 5px;
            vertical-align: top;
        }
        .nominal-box {
            background-color: #f0f0f0;
            border: 2px solid #000;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 22px;
            margin: 20px 0;
        }
        .terbilang {
            font-style: italic;
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .signatures {
            width: 100%;
            margin-top: 50px;
        }
        .signatures td {
            text-align: center;
            width: 50%;
            vertical-align: top;
        }
        .ttd-box {
            height: 80px;
        }
        .footer-note {
            margin-top: 50px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="header">
        <h1>PANITIA PENERIMAAN SANTRI BARU</h1>
        <p>PONDOK PESANTREN AL-KHOIROT</p> {{-- Ganti dengan Nama Pondok Anda --}}
        <p>Tahun Ajaran {{ $transaksi->ppdbSetting->tahun_ajaran ?? '-' }}</p>
    </div>

    {{-- JUDUL DOKUMEN --}}
    <div class="title-doc">BERITA ACARA SERAH TERIMA DANA</div>
    <div class="nomor-doc">Nomor: BAST/PPDB/{{ date('Y') }}/{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</div>

    {{-- ISI SURAT --}}
    <div class="content">
        <p>Pada hari ini, <strong>{{ \Carbon\Carbon::parse($transaksi->tanggal)->isoFormat('dddd') }}</strong>, tanggal <strong>{{ \Carbon\Carbon::parse($transaksi->tanggal)->isoFormat('D MMMM Y') }}</strong>, kami yang bertanda tangan di bawah ini:</p>

        <table class="table-info">
            <tr>
                <td width="120">Nama</td>
                <td width="10">:</td>
                <td><strong>{{ $transaksi->user->name ?? 'Admin PPDB' }}</strong></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>Bendahara / Panitia PPDB</td>
            </tr>
        </table>

        <p>Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong> (Yang Menyerahkan).</p>

        <table class="table-info">
            <tr>
                <td width="120">Kepada Pihak</td>
                <td width="10">:</td>
                <td style="text-transform: uppercase"><strong>{{ $transaksi->kategori }}</strong></td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>
                    {{-- Menampilkan Keterangan yang diinput user (bisa berisi nama santri jika diketik manual) --}}
                    {{ $transaksi->keterangan }}
                </td>
            </tr>
        </table>

        <p>Disebut sebagai <strong>PIHAK KEDUA</strong> (Yang Menerima).</p>

        <p>Pihak Pertama telah menyerahkan dana tunai/transfer kepada Pihak Kedua dengan rincian nominal sebagai berikut:</p>

        {{-- BOX NOMINAL --}}
        <div class="nominal-box">
            Rp {{ number_format($transaksi->nominal, 0, ',', '.') }},-
        </div>

        <div class="terbilang">
            ( ## {{ ucwords(terbilang($transaksi->nominal)) }} Rupiah ## )
        </div>
    </div>

    {{-- TANDA TANGAN --}}
    <table class="signatures">
        <tr>
            <td>
                Yang Menyerahkan,<br>
                (Pihak Pertama)
                <div class="ttd-box"></div>
                <strong>{{ $transaksi->user->name ?? '.........................' }}</strong>
            </td>
            <td>
                Yang Menerima,<br>
                (Pihak Kedua)
                <div class="ttd-box"></div>
                <strong>( ..................................... )</strong>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dicetak otomatis oleh Sistem PPDB pada: {{ now()->format('d/m/Y H:i') }} WIB
    </div>

    {{-- HALAMAN 2: LAMPIRAN (Jika ada santri yang dipilih) --}}
    @if(count($lampiranSantri) > 0)
        <div style="page-break-before: always;"></div>
        
        <div class="header">
            <h1>LAMPIRAN DATA SANTRI</h1>
            <p>Nomor Dokumen: BAST/PPDB/{{ date('Y') }}/{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>

        <p>Berikut adalah daftar nama calon santri yang pembayarannya termasuk dalam setoran/transaksi ini:</p>

        <table border="1" cellspacing="0" cellpadding="5" width="100%" style="font-size: 12px; border-collapse: collapse; border: 1px solid #000;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th width="5%" style="text-align: center;">No</th>
                    <th style="text-align: left;">Nama Lengkap</th>
                    <th width="15%" style="text-align: center;">Jenjang</th>
                    <th width="20%" style="text-align: center;">Nomor Daftar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lampiranSantri as $index => $santri)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $santri->full_name }}</td>
                    <td style="text-align: center;">{{ $santri->jenjang }}</td>
                    <td style="text-align: center;">{{ $santri->no_daftar ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer-note" style="margin-top: 20px;">
            Total Lampiran: {{ count($lampiranSantri) }} Santri
        </div>
    @endif

</body>
</html>

{{-- FUNGSI TERBILANG SEDERHANA (PHP Helper) --}}
@php
function terbilang($nilai) {
    if($nilai<0) {
        $hasil = "minus ". trim(penyebut($nilai));
    } else {
        $point = trim(penyebut($nilai));
        $hasil = $point;
    }
    return $hasil;
}

function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
        $temp = penyebut($nilai - 10). " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
    }
    return $temp;
}
@endphp