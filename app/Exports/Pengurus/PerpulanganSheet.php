<?php

namespace App\Exports\Pengurus;

use App\Models\PerpulanganRecord;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PerpulanganSheet implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
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

    public function query()
    {
        $query = PerpulanganRecord::with(['santri', 'event'])
            ->where('perpulangan_event_id', $this->eventId)
            ->whereHas('santri', function($q) {
                $q->where('jenis_kelamin', $this->gender);
            });

        if ($this->status && $this->status !== 'all') {
            if ($this->status == 'terlambat') {
                 // Logika filter terlambat di query (opsional, bisa juga difilter di aplikasi)
                 // Disini kita ambil semua dulu yang relevan, nanti user bisa filter di excel juga
            } else {
                 // Mapping status string ke integer DB (0, 1, 2)
                 $statusMap = [
                     'belum_jalan' => 0,
                     'sedang_pulang' => 1,
                     'sudah_kembali' => 2
                 ];
                 
                 if (array_key_exists($this->status, $statusMap)) {
                     $query->where('status', $statusMap[$this->status]);
                 }
            }
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Nama Santri',
            'NISM',
            'Kelas',
            'Status',
            'Tanggal Kembali',
            'Keterlambatan', // Ubah jadi string biar jelas
            'Alamat Lengkap',
        ];
    }

    public function map($record): array
    {
        $santri = $record->santri;
        
        // 1. PERBAIKAN STATUS (0,1,2 jadi Teks)
        $statusText = match ($record->status) {
            0 => 'Belum Pulang',
            1 => 'Sedang Pulang',
            2 => 'Sudah Kembali',
            default => 'Unknown'
        };

        // 2. PERBAIKAN HARI TELAT
        $hariTerlambat = 0;
        $batas = Carbon::parse($record->event->tanggal_akhir)->endOfDay(); // Sampai akhir hari

        if ($record->waktu_kembali) {
            // Jika sudah kembali, hitung selisih waktu kembali dengan batas
            $kembali = Carbon::parse($record->waktu_kembali);
            if ($kembali->gt($batas)) {
                $hariTerlambat = $batas->diffInDays($kembali);
            }
        } else {
            // Jika BELUM kembali, cek apakah hari ini sudah lewat batas?
            // Hanya hitung jika statusnya 'sedang_pulang' (1) atau 'belum_pulang' (0) tapi event sudah lewat
            if (Carbon::now()->gt($batas) && $record->status != 2) {
                $hariTerlambat = $batas->diffInDays(Carbon::now());
            }
        }
        
        $keteranganTelat = $hariTerlambat > 0 ? $hariTerlambat . ' Hari' : 'Tepat Waktu';

        // Format Alamat
        $alamat = $santri->alamat ?? '';
        $alamat .= $santri->desa ? ", {$santri->desa}" : '';
        $alamat .= $santri->kecamatan ? ", {$santri->kecamatan}" : '';
        $alamat .= $santri->kabupaten ? ", {$santri->kabupaten}" : ''; // Asumsi ada kabupaten

        return [
            $santri->full_name,
            $santri->nis,
            $santri->kelas->nama_kelas ?? '-',
            $statusText,
            $record->waktu_kembali ? Carbon::parse($record->waktu_kembali)->format('d-m-Y H:i') : '-',
            $keteranganTelat,
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