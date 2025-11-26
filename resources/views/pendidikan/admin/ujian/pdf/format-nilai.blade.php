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
        .info-table td { padding: 2px; vertical-align: top; }
        
        /* Kotak khusus Lisan */
        .box-pertemuan {
            border: 1px solid #000;
            padding: 5px 10px;
            width: 200px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }
        .data-table th { background-color: #f0f0f0; font-weight: bold; }
        
        /* Memastikan nama rata kiri */
        .nama { text-align: left !important; padding-left: 8px; }
        
        .footer { margin-top: 40px; width: 100%; }
        .ttd-box { float: right; width: 200px; text-align: center; }
        .ttd-line { margin-top: 60px; border-bottom: 1px solid #000; }
    </style>
</head>
<body>
    <div class="header">
        <h2>FORMAT PENILAIAN UJIAN DINIYAH</h2>
        <h3>{{ $pondok->pondokName ?? 'PONDOK PESANTREN ASSAADAH' }}</h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Ujian</strong></td>
            <td width="35%">: {{ ucfirst($jadwal->jenis_ujian) }} {{ ucfirst($jadwal->semester) }}</td>
            <td width="15%"><strong>Mapel/Kitab</strong></td>
            <td width="35%">: </td>
        </tr>
        <tr>
            <td><strong>Mustawa</strong></td>
            <td>: {{ $jadwal->mustawa->nama }}</td>
            <td><strong>Kategori</strong></td>
            <td>: {{ ucfirst($jadwal->kategori_tes) }}</td>
        </tr>
        <tr>
            <td><strong>Pengawas</strong></td>
            <td>: </td>
            <td><strong>Waktu</strong></td>
            <td>: </td>
        </tr>
    </table>
    
    {{-- LOGIKA KHUSUS: KATEGORI LISAN (Kotak Jumlah Pertemuan) --}}
    @if(strtolower($jadwal->kategori_tes) == 'tulis')
        <div class="box-pertemuan">
            Jumlah Pertemuan: ___________
        </div>
    @endif
    
    <table class="data-table">
        <thead>
            <tr>
                {{-- Penyesuaian Lebar Kolom --}}
                @if(strtolower($jadwal->kategori_tes) == 'tulis')
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">NIS</th>
                    <th style="width: 40%;">Nama Santri</th>
                    <th style="width: 20%;">Total Kehadiran</th>
                    <th style="width: 20%;">Nilai ({{ ucfirst($jadwal->kategori_tes) }})</th>
                @else
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">NIS</th>
                    <th style="width: 50%;">Nama Santri</th>
                    <th style="width: 25%;">Nilai ({{ ucfirst($jadwal->kategori_tes) }})</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($santris as $index => $santri)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $santri->nis }}</td>
                    {{-- Class .nama sudah diset text-align: left --}}
                    <td class="nama">{{ $santri->full_name }}</td>
                    
                    {{-- LOGIKA KHUSUS: Kolom Tambahan untuk Lisan --}}
                    @if(strtolower($jadwal->kategori_tes) == 'tulis')
                        <td></td> {{-- Kolom Kosong untuk input manual kehadiran --}}
                    @endif

                    <td></td> {{-- Kolom Kosong untuk input nilai --}}
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd-box">
            <p>Penguji / Pengawas,</p>
            <div class="ttd-line"></div>
            <p>( {{ $jadwal->pengawas->nama ?? '.......................' }} )</p>
        </div>
    </div>
</body>
</html>