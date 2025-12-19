<?php

namespace App\Exports\Pengurus;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SantriExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $pondokId;
    protected $filters;

    public function __construct($pondokId, $filters = [])
    {
        $this->pondokId = $pondokId;
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Santri::query()
            ->with(['kelas', 'asrama']) // Eager load relasi
            ->where('pondok_id', $this->pondokId);

        // Filter Optional (Jika ada filter dari halaman index)
        if (!empty($this->filters['kelas_id'])) {
            $query->where('kelas_id', $this->filters['kelas_id']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query;
    }

    // Mapping data dari Database ke Kolom Excel
    public function map($santri): array
    {
        return [
            $santri->nis,
            $santri->nisn,
            $santri->nik . ' ', // Tambah spasi agar tidak dianggap scientific number oleh Excel
            $santri->no_kk . ' ',
            $santri->tahun_masuk,
            $santri->full_name,
            $santri->jenis_kelamin,
            $santri->tempat_lahir,
            $santri->tanggal_lahir ? date('Y-m-d', strtotime($santri->tanggal_lahir)) : '-',
            $santri->anak_ke,
            $santri->jumlah_saudara,
            $santri->golongan_darah,
            $santri->riwayat_penyakit,
            $santri->kelas->nama_kelas ?? '-',
            $santri->asrama->nama_asrama ?? '-',
            $santri->alamat,
            $santri->rt,
            $santri->rw,
            $santri->desa,
            $santri->kecamatan,
            $santri->kode_pos,
            
            // Data Ayah
            $santri->nama_ayah,
            $santri->nik_ayah . ' ',
            $santri->thn_lahir_ayah,
            $santri->pendidikan_ayah,
            $santri->pekerjaan_ayah,
            $santri->penghasilan_ayah,
            $santri->no_hp_ayah . ' ',

            // Data Ibu
            $santri->nama_ibu,
            $santri->nik_ibu . ' ',
            $santri->thn_lahir_ibu,
            $santri->pendidikan_ibu,
            $santri->pekerjaan_ibu,
            $santri->penghasilan_ibu,
            $santri->no_hp_ibu . ' ',
            
            // Status
            $santri->status == 'active' ? 'Aktif' : ($santri->status == 'graduated' ? 'Lulus' : 'Keluar'),
        ];
    }

    public function headings(): array
    {
        return [
            'NIS', 'NISN', 'NIK', 'No KK', 
            'Tahun Masuk', 'Nama Lengkap', 'Jenis Kelamin', 
            'Tempat Lahir', 'Tanggal Lahir', 
            'Anak Ke', 'Jumlah Saudara', 'Golongan Darah', 'Riwayat Penyakit',
            'Kelas', 'Asrama',
            'Alamat', 'RT', 'RW', 'Desa', 'Kecamatan', 'Kode Pos',
            'Nama Ayah', 'NIK Ayah', 'Thn Lahir Ayah', 'Pendidikan Ayah', 'Pekerjaan Ayah', 'Penghasilan Ayah', 'No HP Ayah',
            'Nama Ibu', 'NIK Ibu', 'Thn Lahir Ibu', 'Pendidikan Ibu', 'Pekerjaan Ibu', 'Penghasilan Ibu', 'No HP Ibu',
            'Status Santri'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style Header Bold
        $sheet->getStyle('A1:AJ1')->getFont()->setBold(true);
        
        // Border seluruh data
        $sheet->getStyle('A1:AJ' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        return [];
    }
}