<?php

namespace App\Exports\Pengurus;

use App\Models\PerpulanganRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerpulanganSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $eventId;
    protected $status;
    protected $gender;
    protected $genderLabel;

    public function __construct($eventId, $status, $gender, $genderLabel)
    {
        $this->eventId = $eventId;
        $this->status = $status;
        $this->gender = $gender;
        $this->genderLabel = $genderLabel;
    }

    public function collection()
    {
        $query = PerpulanganRecord::with(['santri.kelas', 'event'])
            ->where('perpulangan_event_id', $this->eventId)
            ->whereHas('santri', function($q) {
                $q->where('jenis_kelamin', $this->gender);
            });

        if ($this->status && $this->status !== 'all') {
            $statusMap = [
                'belum_jalan' => 0,
                'sedang_pulang' => 1,
                'sudah_kembali' => 2
            ];

            if ($this->status != 'terlambat' && array_key_exists($this->status, $statusMap)) {
                 $query->where('status', $statusMap[$this->status]);
            }
        }

        return $query->get();
    }

    public function headings(): array
    {
        // PERBAIKAN: Hanya kolom yang diminta
        return [
            'Nama Santri',
            'Kelas',
            'Alamat Lengkap',
        ];
    }

    public function map($record): array
    {
        $santri = $record->santri;

        // Format Alamat
        $alamat = $santri->alamat ?? '';
        $alamat .= $santri->rt ? " RT.{$santri->rt}" : '';
        $alamat .= $santri->rw ? " RW.{$santri->rw}" : '';
        $alamat .= $santri->desa ? " Ds.{$santri->desa}" : '';
        $alamat .= $santri->kecamatan ? " Kec.{$santri->kecamatan}" : '';
        $alamat .= $santri->kabupaten ? " {$santri->kabupaten}" : '';

        // PERBAIKAN: Return data sesuai headings baru
        return [
            $santri->full_name,
            $santri->kelas->nama_kelas ?? '-',
            $alamat,
        ];
    }

    public function title(): string
    {
        return $this->genderLabel;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}