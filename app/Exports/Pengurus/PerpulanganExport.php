<?php

namespace App\Exports\Pengurus;

use App\Models\PerpulanganRecord;
use App\Models\PerpulanganEvent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PerpulanganExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $eventId;
    protected $status;

    public function __construct($eventId, $status)
    {
        $this->eventId = $eventId;
        $this->status = $status;
    }

    public function collection()
    {
        $query = PerpulanganRecord::with(['santri', 'event'])
            ->where('perpulangan_event_id', $this->eventId);

        if ($this->status && $this->status !== 'all') {
            // Mapping status filter ke status database jika berbeda
            // Asumsi: status di DB sama dengan filter (belum_jalan, sedang_pulang, sudah_kembali, terlambat)
            if ($this->status == 'terlambat') {
                 // Logika khusus untuk terlambat jika di DB ditandai dengan flag is_late
                 $query->where('is_late', true)->orWhere('status', 'terlambat');
            } else {
                 $query->where('status', $this->status);
            }
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Santri',
            'NISM', // Atau NIS
            'Status',
            'Tanggal Kembali',
            'Keterlambatan (Hari)',
            'Alamat Lengkap',
        ];
    }

    public function map($record): array
    {
        $santri = $record->santri;
        
        // Hitung keterlambatan
        $hariTerlambat = 0;
        if ($record->waktu_kembali && $record->event->tanggal_akhir) {
            $batas = Carbon::parse($record->event->tanggal_akhir);
            $kembali = Carbon::parse($record->waktu_kembali);
            
            if ($kembali->gt($batas)) {
                $hariTerlambat = $batas->diffInDays($kembali);
            }
        }

        // Format Alamat
        $alamat = $santri->alamat ?? '';
        $alamat .= $santri->rt ? " RT.{$santri->rt}" : '';
        $alamat .= $santri->rw ? " RW.{$santri->rw}" : '';
        $alamat .= $santri->desa ? ", {$santri->desa}" : '';
        $alamat .= $santri->kecamatan ? ", {$santri->kecamatan}" : '';
        $alamat .= $santri->kode_pos ? " ({$santri->kode_pos})" : '';

        return [
            $santri->full_name,
            $santri->nis,
            ucwords(str_replace('_', ' ', $record->status)),
            $record->waktu_kembali ? Carbon::parse($record->waktu_kembali)->format('d-m-Y H:i') : '-',
            $hariTerlambat . ' Hari',
            $alamat,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}