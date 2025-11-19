<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $judul }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 3px; text-align: center; vertical-align: middle; }
        th { background-color: #e0e0e0; font-weight: bold; font-size: 9px; }
        .header { text-align: center; margin-bottom: 20px; }
        .nama { text-align: left; width: 150px; padding-left: 5px; }
        .legend { margin-top: 15px; font-size: 9px; }
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
                {{-- Header Pertemuan --}}
                <th colspan="{{ count($pertemuan) }}">Riwayat Pertemuan (Tgl / Mapel)</th>
                {{-- Header Total (PERBAIKAN: Sekarang mencakup H, S, I, A) --}}
                <th colspan="4">Total Kehadiran</th>
            </tr>
            <tr>
                {{-- Loop Tanggal Pertemuan --}}
                @foreach($pertemuan as $p)
                    <th style="min-width: 25px;">
                        {{ $p->tanggal->format('d/m') }}<br>
                        <span style="font-size: 8px;">
                            {{ substr($p->jadwalPelajaran->mataPelajaran->nama_mapel, 0, 3) }}
                        </span>
                    </th>
                @endforeach
                
                {{-- Kolom Summary --}}
                <th style="width: 25px; background-color: #d1e7dd;">H</th>
                <th style="width: 25px; background-color: #fff3cd;">S</th>
                <th style="width: 25px; background-color: #cff4fc;">I</th>
                <th style="width: 25px; background-color: #f8d7da;">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                @php 
                    // Inisialisasi Counter
                    $h=0; $s=0; $i_cnt=0; $a=0; 
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="nama">{{ $item['santri']->full_name }}</td>
                    
                    {{-- Loop Data per Pertemuan --}}
                    @foreach($pertemuan as $p)
                        @php
                            $log = $item['logs'][$p->id] ?? null;
                            $status = '-';
                            $style = '';
                            
                            if ($log) {
                                if ($log->status == 'hadir') { 
                                    $status = 'v'; // Simbol Ceklis/Hadir
                                    $h++; 
                                } elseif ($log->status == 'sakit') { 
                                    $status = 'S'; 
                                    $s++; 
                                    $style = 'background-color: #fff3cd;';
                                } elseif ($log->status == 'izin') { 
                                    $status = 'I'; 
                                    $i_cnt++; 
                                    $style = 'background-color: #cff4fc;';
                                } elseif ($log->status == 'alpa') { 
                                    $status = 'A'; 
                                    $a++; 
                                    $style = 'background-color: #f8d7da; color: red; font-weight: bold;';
                                }
                            } else {
                                // Jika tidak ada data absensi (mungkin belum absen)
                                // Kita hitung sebagai Alpa atau strip tergantung kebijakan
                                $status = '-';
                            }
                        @endphp
                        <td style="{{ $style }}">{{ $status }}</td>
                    @endforeach

                    {{-- Kolom Total (PERBAIKAN) --}}
                    <td style="background-color: #d1e7dd; font-weight: bold;">{{ $h }}</td>
                    <td style="background-color: #fff3cd;">{{ $s }}</td>
                    <td style="background-color: #cff4fc;">{{ $i_cnt }}</td>
                    <td style="background-color: #f8d7da; color: red; font-weight: bold;">{{ $a }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Keterangan (Legend) --}}
    <div class="legend">
        <strong>Keterangan Kode:</strong><br>
        v = Hadir (Masuk Kelas)<br>
        S = Sakit (Tercatat di UKS/Surat)<br>
        I = Izin (Disetujui)<br>
        A = Alpa (Tanpa Keterangan)<br>
        - = Belum ada data absensi
    </div>
</body>
</html>