<?php

namespace App\Exports\Pendidikan;

use App\Models\Santri;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BiodataRaporSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $mustawa_id;
    protected $nama_mustawa;

    // Sekarang hanya menerima 2 parameter utama
    public function __construct($mustawa_id, $nama_mustawa)
    {
        $this->mustawa_id = $mustawa_id;
        $this->nama_mustawa = $nama_mustawa;
    }

    // Mengambil data Santri berdasarkan Kelas tanpa memisahkan Putra/Putri lagi
    public function collection()
    {
        return Santri::with(['mustawa'])
            ->where('mustawa_id', $this->mustawa_id)
            ->get();
    }

    // Judul Kolom (Header Baris ke-1)
    public function headings(): array
    {
        return [
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
        ];
    }

    // Memasukkan data dari database ke kolom-kolom Excel
    public function map($santri): array
    {
        // Format Tempat & Tanggal Lahir
        $tempat_lahir = $santri->tempat_lahir ?? '-';
        $tanggal_lahir = $santri->tanggal_lahir ? Carbon::parse($santri->tanggal_lahir)->translatedFormat('d F Y') : '-';
        $ttl = $tempat_lahir . ', ' . $tanggal_lahir;

        return [
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
        ];
    }

    // Nama Tab/Sheet di bawah Excel langsung menggunakan Nama Kelas aslinya
    public function title(): string
    {
        // Maksimal nama tab excel adalah 31 karakter
        return substr($this->nama_mustawa ?? 'Kelas', 0, 31);
    }

    // Styling Baris pertama (Tebal/Bold)
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}