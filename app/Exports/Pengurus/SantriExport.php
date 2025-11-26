<?php

namespace App\Exports\Pengurus;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SantriExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
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
            ->with(['kelas', 'orangTua'])
            ->where('pondok_id', $this->pondokId);

        // Filter Logic
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

    public function map($santri): array
    {
        return [
            $santri->nis,
            $santri->rfid_uid ?? '-',
            $santri->full_name,
            $santri->jenis_kelamin,
            $santri->tempat_lahir,
            $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d-m-Y') : '-',
            $santri->golongan_darah ?? '-',
            $santri->riwayat_penyakit ?? '-',
            
            $santri->kelas ? $santri->kelas->nama_kelas : 'Belum Ada Kelas',
            $santri->tahun_masuk,
            $santri->status == 'active' ? 'Aktif' : ($santri->status == 'graduated' ? 'Lulus' : 'Pindah'),

            $santri->alamat ?? '-',
            $santri->rt ?? '-',
            $santri->rw ?? '-',
            $santri->desa ?? '-',
            $santri->kecamatan ?? '-',
            $santri->kode_pos ?? '-',

            $santri->nama_ayah ?? '-',
            $santri->nik_ayah ?? '-',
            $santri->pekerjaan_ayah ?? '-',
            
            $santri->nama_ibu ?? '-',
            $santri->nik_ibu ?? '-',
            $santri->pekerjaan_ibu ?? '-',

            $santri->orangTua ? $santri->orangTua->name : '-',
            $santri->orangTua ? $santri->orangTua->phone : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'NIS', 'RFID UID', 'Nama Lengkap', 'L/P', 'Tempat Lahir', 'Tgl Lahir', 'Gol. Darah', 'Riwayat Penyakit',
            'Kelas', 'Angkatan', 'Status',
            'Alamat', 'RT', 'RW', 'Desa', 'Kecamatan', 'Kode Pos',
            'Nama Ayah', 'NIK Ayah', 'Pekerjaan Ayah',
            'Nama Ibu', 'NIK Ibu', 'Pekerjaan Ibu',
            'Nama Wali (Akun)', 'No HP Wali'
        ];
    }

    /**
     * DISINI KITA MENGATUR DESAIN AGAR TERLIHAT PROFESIONAL
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // 1. Tentukan Range Data (Dari A1 sampai kolom terakhir & baris terakhir)
                $lastColumn = $sheet->getHighestColumn();
                $lastRow = $sheet->getHighestRow();
                $range = 'A1:' . $lastColumn . $lastRow;

                // 2. Styling Header (Baris 1)
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFF'], // Teks Putih
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '059669'], // Warna Emerald 600 (Hijau Profesional)
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];
                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);
                
                // Set Tinggi Baris Header agar tidak terlalu gepeng
                $sheet->getRowDimension(1)->setRowHeight(30);

                // 3. Styling Border untuk SEMUA Sel
                $borderStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Hitam
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER, // Semua data vertikal tengah
                    ],
                ];
                $sheet->getStyle($range)->applyFromArray($borderStyle);

                // 4. Perataan Khusus (Alignment)
                // Kolom NIS (A), L/P (D), Tgl Lahir (F), Gol Darah (G), Kelas (I), Angkatan (J), Status (K) -> Rata Tengah
                $centerColumns = ['A', 'D', 'F', 'G', 'I', 'J', 'K'];
                foreach ($centerColumns as $col) {
                    $sheet->getStyle($col . '2:' . $col . $lastRow)
                          ->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // 5. Wrap Text untuk Alamat (biar kalau panjang turun ke bawah)
                // Kolom Alamat ada di index 'L'
                $sheet->getStyle('L2:L' . $lastRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}