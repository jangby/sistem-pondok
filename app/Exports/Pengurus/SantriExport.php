<?php

namespace App\Exports\Pengurus;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SantriExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $pondokId;
    protected $filters;

    public function __construct($pondokId, $filters)
    {
        $this->pondokId = $pondokId;
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Santri::query()
            ->with(['kelas', 'orangTua']) // Load relasi agar performa cepat
            ->where('pondok_id', $this->pondokId);

        // --- FILTERING (Sama seperti sebelumnya) ---
        if (!empty($this->filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $this->filters['jenis_kelamin']);
        }

        if (!empty($this->filters['kelas_id'])) {
            $query->where('kelas_id', $this->filters['kelas_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query;
    }

    // DISINI KITA MENENTUKAN ISI SETIAP KOLOM
    public function map($santri): array
    {
        return [
            // --- DATA UTAMA ---
            $santri->nis,
            $santri->rfid_uid ?? '-',
            $santri->full_name,
            $santri->jenis_kelamin,
            $santri->tempat_lahir,
            $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d-m-Y') : '-',
            $santri->golongan_darah ?? '-',
            $santri->riwayat_penyakit ?? '-',
            
            // --- AKADEMIK & STATUS ---
            $santri->kelas ? $santri->kelas->nama_kelas : 'Belum Ada Kelas',
            $santri->tahun_masuk,
            $santri->status == 'active' ? 'Aktif' : ($santri->status == 'graduated' ? 'Lulus' : 'Pindah'),

            // --- ALAMAT DOMISILI ---
            $santri->alamat ?? '-',
            $santri->rt ?? '-',
            $santri->rw ?? '-',
            $santri->desa ?? '-',
            $santri->kecamatan ?? '-',
            $santri->kode_pos ?? '-',

            // --- DATA AYAH ---
            $santri->nama_ayah ?? '-',
            $santri->nik_ayah ?? '-',
            $santri->thn_lahir_ayah ?? '-',
            $santri->pendidikan_ayah ?? '-',
            $santri->pekerjaan_ayah ?? '-',
            $santri->penghasilan_ayah ?? '-',

            // --- DATA IBU ---
            $santri->nama_ibu ?? '-',
            $santri->nik_ibu ?? '-',
            $santri->thn_lahir_ibu ?? '-',
            $santri->pendidikan_ibu ?? '-',
            $santri->pekerjaan_ibu ?? '-',
            $santri->penghasilan_ibu ?? '-',

            // --- KONTAK WALISANTRI (AKUN) ---
            $santri->orangTua ? $santri->orangTua->name : '-', // Nama Akun Wali
            $santri->orangTua ? $santri->orangTua->email : '-', // Email Wali
            $santri->orangTua ? $santri->orangTua->phone : '-', // No HP Wali
        ];
    }

    // DISINI JUDUL HEADER EXCELNYA
    public function headings(): array
    {
        return [
            'NIS',
            'RFID UID',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Gol. Darah',
            'Riwayat Penyakit',
            
            'Kelas',
            'Tahun Masuk',
            'Status',

            'Alamat Jalan',
            'RT',
            'RW',
            'Desa/Kelurahan',
            'Kecamatan',
            'Kode Pos',

            'Nama Ayah',
            'NIK Ayah',
            'Thn Lahir Ayah',
            'Pendidikan Ayah',
            'Pekerjaan Ayah',
            'Penghasilan Ayah',

            'Nama Ibu',
            'NIK Ibu',
            'Thn Lahir Ibu',
            'Pendidikan Ibu',
            'Pekerjaan Ibu',
            'Penghasilan Ibu',

            'Nama Akun Wali',
            'Email Wali',
            'No HP Wali',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Bold pada baris pertama (Header)
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}