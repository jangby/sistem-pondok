<!DOCTYPE html>
<html>
<head>
    <title>{{ $judul }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 3px; text-align: center; }
        th { background-color: #f2f2f2; }
        .nama { text-align: left; width: 150px; }
        .libur { background-color: #ddd; }
    </style>
</head>
<body>
    <h2>{{ $judul }}</h2>
    <h3>{{ $sekolah->nama_sekolah }}</h3>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="nama">Nama Guru</th>
                @for($i=1; $i<=31; $i++)
                    <th>{{ $i }}</th>
                @endfor
                <th colspan="4">Total</th>
            </tr>
            <tr>
                {{-- Baris kosong untuk tanggal --}}
                @for($i=1; $i<=31; $i++) <th></th> @endfor
                <th>H</th> <th>S</th> <th>I</th> <th>A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                @php $h=0; $s=0; $i_cnt=0; $a=0; @endphp
                <tr>
                    <td class="nama">{{ $item['guru']->name }}</td>
                    @for($d=1; $d<=31; $d++)
                        @php
                            $tgl = \Carbon\Carbon::createFromDate($tahun, $bulan, $d);
                            $key = $tgl->format('Y-m-d');
                            $log = $item['logs'][$key] ?? null;
                            $kode = '';
                            
                            if ($log) {
                                if($log->status == 'hadir') { $kode = 'H'; $h++; }
                                elseif($log->status == 'sakit') { $kode = 'S'; $s++; }
                                elseif($log->status == 'izin') { $kode = 'I'; $i_cnt++; }
                                elseif($log->status == 'alpa') { $kode = 'A'; $a++; }
                            }
                        @endphp
                        <td>{{ $kode }}</td>
                    @endfor
                    <td>{{ $h }}</td> <td>{{ $s }}</td> <td>{{ $i_cnt }}</td> <td>{{ $a }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>