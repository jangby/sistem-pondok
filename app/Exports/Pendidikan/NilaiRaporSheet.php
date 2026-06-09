<?php

namespace App\Exports\Pendidikan;

use App\Models\Santri;
use App\Models\JadwalUjianDiniyah;
use App\Models\NilaiPesantren;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiRaporSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $mustawa_id;
    protected $nama_mustawa;
    protected $jenis_ujian;
    protected $semester;
    
    protected $jadwals;    // Menyimpan jadwal ujian untuk kolom
    protected $dataNilai;  // Menyimpan koleksi seluruh nilai di kelas ini
    protected $nomor = 1;  // Nomor Urut

    public function __construct($mustawa_id, $nama_mustawa, $jenis_ujian, $semester)
    {
        $this->mustawa_id = $mustawa_id;
        $this->nama_mustawa = $nama_mustawa;
        $this->jenis_ujian = $jenis_ujian;
        $this->semester = $semester;

        // 1. Tarik Jadwal Ujian khusus kelas ini sebagai Header Kolom Nanti
        $this->jadwals = JadwalUjianDiniyah::with('mapel')
            ->where('mustawa_id', $this->mustawa_id)
            ->where('jenis_ujian', $this->jenis_ujian)
            ->where('semester', $this->semester)
            ->get();
    }

    public function collection()
    {
        // 2. Tarik semua nilai di kelas ini untuk mempercepat proses (menghindari N+1 query)
        $this->dataNilai = NilaiPesantren::where('mustawa_id', $this->mustawa_id)
            ->where('jenis_ujian', $this->jenis_ujian)
            ->where('semester', $this->semester)
            ->get();

        // 3. Tarik data Santri di kelas ini
        return Santri::where('mustawa_id', $this->mustawa_id)->get();
    }

    // MEMBUAT HEADER KOLOM ATAS
    public function headings(): array
    {
        $headers = [
            'No',
            'Nama Lengkap',
            'NIS/NISM',
            'Nilai Sikap & Keterampilan',
            'Absensi Kehadiran',
        ];

        // Tambahkan kolom mapel dinamis sesuai jadwal ujian
        foreach ($this->jadwals as $jadwal) {
            // Sudah diubah menjadi nama_mapel
            $nama_mapel = $jadwal->mapel->nama_mapel ?? 'Mapel Unknown'; 
            
            $kategori = ucfirst($jadwal->kategori_tes); 
            $headers[] = $nama_mapel . ' (' . $kategori . ')';
        }

        return $headers;
    }

    // MENGISI DATA KE DALAM BARIS
    public function map($santri): array
    {
        // Ambil semua nilai pesantren milik santri ini saja
        $nilai_santri = $this->dataNilai->where('santri_id', $santri->id);

        // Kalkulasi Rata-rata Keterampilan (dari nilai_praktek) & Absensi (nilai_kehadiran)
        $rata_keterampilan = $nilai_santri->avg('nilai_praktek');
        $rata_absensi = $nilai_santri->avg('nilai_kehadiran');

        $row = [
            $this->nomor++,
            $santri->full_name ?? '-',
            ($santri->nis ?? '-') . ' / ' . ($santri->nisn ?? '-'),
            $rata_keterampilan ? number_format($rata_keterampilan, 0) : '-', // Hilangkan koma dengan format 0
            $rata_absensi ? number_format($rata_absensi, 0) : '-',
        ];

        // Looping mapel untuk menarik nilai sesuai kategori tesnya
        foreach ($this->jadwals as $jadwal) {
            // Cari nilai mapel tertentu untuk santri ini
            $nilai_mapel = $nilai_santri->where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)->first();

            if ($nilai_mapel) {
                // Tarik kolom sesuai kategori tes yang dijadwalkan
                $kategori = strtolower($jadwal->kategori_tes);
                if ($kategori == 'tulis') {
                    $row[] = $nilai_mapel->nilai_tulis ?? '-';
                } elseif ($kategori == 'lisan') {
                    $row[] = $nilai_mapel->nilai_lisan ?? '-';
                } elseif ($kategori == 'praktek') {
                    $row[] = $nilai_mapel->nilai_praktek ?? '-';
                } elseif ($kategori == 'hafalan') {
                    $row[] = $nilai_mapel->nilai_hafalan ?? '-'; // Antisipasi jika ada kategori hafalan
                } else {
                    $row[] = '-';
                }
            } else {
                $row[] = '-'; // Jika ustadz belum input
            }
        }

        return $row;
    }

    // NAMA SHEET DI BAWAH EXCEL
    public function title(): string
    {
        return substr($this->nama_mustawa ?? 'Kelas', 0, 31);
    }

    // BUAT TEBAL HEADER
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}