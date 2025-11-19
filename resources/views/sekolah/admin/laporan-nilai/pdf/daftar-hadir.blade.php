<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $judul }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h3 { margin: 5px 0; font-size: 14px; }
        
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 2px; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px 5px; vertical-align: middle; }
        .data-table th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        .center { text-align: center; }
        
        .footer { margin-top: 40px; width: 100%; }
        .ttd-box { float: right; width: 200px; text-align: center; }
        .ttd-line { margin-top: 60px; border-bottom: 1px solid #000; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR HADIR PESERTA UJIAN</h2>
        <h3>{{ $kegiatan->sekolah->nama_sekolah }}</h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Kegiatan</strong></td>
            <td width="35%">: {{ $kegiatan->nama_kegiatan }}</td>
            <td width="15%"><strong>Mata Pelajaran</strong></td>
            <td width="35%">: {{ $mapel->nama_mapel }}</td>
        </tr>
        <tr>
            <td><strong>Kelas</strong></td>
            <td>: {{ $kelas->nama_kelas }}</td>
            <td><strong>Tanggal</strong></td>
            <td>: ______________________</td> {{-- Tanggal Dikosongkan --}}
        </tr>
        <tr>
            <td><strong>Waktu</strong></td>
            <td>: ______________________</td>
            <td><strong>Ruang</strong></td>
            <td>: ______________________</td>
        </tr>
    </table>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">NIS</th>
                <th style="width: 45%;">Nama Siswa</th>
                <th style="width: 35%;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($santris as $index => $santri)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center">{{ $santri->nis }}</td>
                    <td>{{ $santri->full_name }}</td>
                    <td>
                        {{-- Pola Tanda Tangan Zig-Zag --}}
                        @if(($index + 1) % 2 != 0)
                            <div style="text-align: left; padding-left: 10px;">{{ $index + 1 }}. ..........</div>
                        @else
                            <div style="text-align: center; padding-left: 50px;">{{ $index + 1 }}. ..........</div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd-box">
            <p>Pengawas Ujian,</p>
            <div class="ttd-line"></div>
            <p>( ........................................... )</p>
        </div>
    </div>
</body>
</html>