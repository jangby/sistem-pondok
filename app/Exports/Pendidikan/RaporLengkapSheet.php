<?php

namespace App\Exports\Pendidikan;

use App\Models\Santri;
use App\Models\JadwalUjianDiniyah;
use App\Models\NilaiPesantren;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RaporLengkapSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $mustawa_id;
    protected $nama_mustawa;
    protected $jenis_ujian;
    protected $semester;
    
    protected $jadwals;
    protected $dataNilai;
    protected $nomor = 1;

    public function __construct($mustawa_id, $nama_mustawa, $jenis_ujian, $semester)
    {
        $this->mustawa_id = $mustawa_id;
        $this->nama_mustawa = $nama_mustawa;
        $this->jenis_ujian = $jenis_ujian;
        $this->semester = $semester;

        // Mengambil jadwal ujian aktif untuk dijadikan kolom dinamis mapel di sebelah kanan
        $this->jadwals = JadwalUjianDiniyah::with('mapel')
            ->where('mustawa_id', $this->mustawa_id)
            ->where('jenis_ujian', $this->jenis_ujian)
            ->where('semester', $this->semester)
            ->get();
    }

    public function collection()
    {
        // Mengambil rekap nilai untuk kelas ini
        $this->dataNilai = NilaiPesantren::where('mustawa_id', $this->mustawa_id)
            ->where('jenis_ujian', $this->jenis_ujian)
            ->where('semester', $this->semester)
            ->get();

        // Mengambil daftar santri di kelas ini
        return Santri::with('mustawa')
            ->where('mustawa_id', $this->mustawa_id)
            ->get();
    }

    // GABUNGAN HEADER: BIODATA DI AWAL, NILAI DI AKHIR
    public function headings(): array
    {
        // Kolom A sampai P berisi Biodata dan Rekap Nilai Umum
        $headers = [
            'No',
            'Nama Lengkap',
            'Nomor Induk Santri (NIS)',
            'Nomor Induk Siswa Nasional (NISN)',
            'Tempat, Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Alamat Santri',
            'Diterima di Kelas',
            'Diterima Pada Tahun',
            'Nama Ayah',
            'Nama Ibu',
            'Pekerjaan Ayah',
            'Pekerjaan Ibu',
            'Nilai Sikap & Keterampilan',
            'Absensi Kehadiran',
        ];

        // Kolom berikutnya ke kanan akan diisi nama mapel secara otomatis
        foreach ($this->jadwals as $jadwal) {
            $nama_mapel = $jadwal->mapel->nama_mapel ?? 'Mapel Unknown';
            $kategori = ucfirst($jadwal->kategori_tes); // Tulis, Lisan, Praktek, dll
            $headers[] = $nama_mapel . ' (' . $kategori . ')';
        }

        return $headers;
    }

    // PEMETAAN DATA PER BARIS
    public function map($santri): array
    {
        // 1. Olah data biodata santri
        $tempat_lahir = $santri->tempat_lahir ?? '-';
        $tanggal_lahir = $santri->tanggal_lahir ? Carbon::parse($santri->tanggal_lahir)->locale('id')->translatedFormat('d F Y') : '-';
        $ttl = $tempat_lahir . ', ' . $tanggal_lahir;

        // 2. Olah data nilai santri
        $nilai_santri = $this->dataNilai->where('santri_id', $santri->id);
        $rata_keterampilan = $nilai_santri->avg('nilai_praktek');
        $rata_absensi = $nilai_santri->avg('nilai_kehadiran');

        // Kerangka baris awal (Biodata + Nilai Sikap + Absensi)
        $row = [
            $this->nomor++,
            $santri->full_name ?? '-',
            $santri->nis ?? '-',
            $santri->nisn ?? '-',
            $ttl,
            $santri->jenis_kelamin ?? '-',
            'Islam',
            $santri->alamat ?? '-',
            $santri->mustawa->nama ?? '-',
            $santri->tahun_masuk ?? '-',
            $santri->nama_ayah ?? '-',
            $santri->nama_ibu ?? '-',
            $santri->pekerjaan_ayah ?? '-',
            $santri->pekerjaan_ibu ?? '-',
            $rata_keterampilan ? number_format($rata_keterampilan, 0) : '-',
            $rata_absensi ? number_format($rata_absensi, 0) : '-',
        ];

        // 3. Masukkan nilai mapel ujian ke kolom-kolom kanan secara dinamis
        foreach ($this->jadwals as $jadwal) {
            $nilai_mapel = $nilai_santri->where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)->first();

            if ($nilai_mapel) {
                $kategori = strtolower($jadwal->kategori_tes);
                if ($kategori == 'tulis') {
                    $row[] = $nilai_mapel->nilai_tulis ?? '-';
                } elseif ($kategori == 'lisan') {
                    $row[] = $nilai_mapel->nilai_lisan ?? '-';
                } elseif ($kategori == 'praktek') {
                    $row[] = $nilai_mapel->nilai_praktek ?? '-';
                } elseif ($kategori == 'hafalan') {
                    $row[] = $nilai_mapel->nilai_hafalan ?? '-';
                } else {
                    $row[] = '-';
                }
            } else {
                $row[] = '-';
            }
        }

        return $row;
    }

    public function title(): string
    {
        return substr($this->nama_mustawa ?? 'Kelas', 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}