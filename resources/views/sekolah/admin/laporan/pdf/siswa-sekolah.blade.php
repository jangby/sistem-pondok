<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $judul }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 3px; text-align: center; vertical-align: middle; }
        th { background-color: #e0e0e0; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 2px; }
        .nama { text-align: left; width: 150px; padding-left: 5px; }
        .legend { margin-top: 10px; font-size: 9px; }
        .text-red { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $judul }}</h2>
        <h3>{{ $sekolah->nama_sekolah }}</h3>
    </div>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 20px;">No</th>
                <th rowspan="2" class="nama">Nama Siswa</th>
                <th rowspan="2">Kelas</th>
                @for($i=1; $i<=31; $i++)
                    <th style="width: 16px;">{{ $i }}</th>
                @endfor
                <th colspan="3" style="background-color: #d1e7dd;">Total</th>
            </tr>
            <tr>
                @for($i=1; $i<=31; $i++) <th></th> @endfor
                <th style="width: 20px; background-color: #d1e7dd;">H</th>
                <th style="width: 20px; background-color: #fff3cd;">T</th> {{-- Terlambat --}}
                <th style="width: 20px; background-color: #f8d7da;">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                @php 
                    $h=0; $t=0; $a=0; 
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="nama">{{ $item['santri']->full_name }}</td>
                    <td>{{ $item['santri']->kelas->nama_kelas ?? '-' }}</td>
                    @for($d=1; $d<=31; $d++)
                        @php
                            $isValidDate = checkdate($bulan, $d, $tahun);
                            $kode = '';
                            
                            if ($isValidDate) {
                                $tglObj = \Carbon\Carbon::createFromDate($tahun, $bulan, $d);
                                $key = $tglObj->format('Y-m-d');
                                $log = $item['logs'][$key] ?? null;
                                
                                if ($log) {
                                    if($log->status_masuk == 'tepat_waktu') { 
                                        $kode = 'H'; $h++; 
                                    } elseif($log->status_masuk == 'terlambat') { 
                                        $kode = 'T'; $t++; 
                                    }
                                } else {
                                    // Logika sederhana: jika tidak ada log, anggap '-' atau 'A' jika hari sekolah
                                    // Untuk akurasi lebih baik, perlu cek tabel Hari Libur & Hari Kerja di sini
                                    $kode = '-'; 
                                }
                            }
                        @endphp
                        <td class="{{ $kode == 'T' ? 'text-red' : '' }}">{{ $isValidDate ? $kode : '' }}</td>
                    @endfor
                    <td style="background-color: #d1e7dd; font-weight: bold;">{{ $h }}</td>
                    <td style="background-color: #fff3cd;">{{ $t }}</td>
                    <td style="background-color: #f8d7da;">{{ $a }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <strong>Keterangan:</strong> H=Hadir Tepat Waktu, T=Terlambat, - = Tidak Ada Data Scan
    </div>
</body>
</html>