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
    protected $jenis_kelamin;
    protected $label_jk;

    // Menerima parameter dari BiodataRaporExport
    public function __construct($mustawa_id, $nama_mustawa, $jenis_kelamin, $label_jk)
    {
        $this->mustawa_id = $mustawa_id;
        $this->nama_mustawa = $nama_mustawa;
        $this->jenis_kelamin = $jenis_kelamin;
        $this->label_jk = $label_jk;
    }

    // Mengambil data Santri dengan relasi Orang Tua dan Kelas
    public function collection()
    {
        return Santri::with(['orangTua', 'mustawa'])
            ->where('mustawa_id', $this->mustawa_id)
            ->where('jenis_kelamin', $this->jenis_kelamin)
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
            'Pekerjaan Ibu/Wali',
        ];
    }

    // Memasukkan data dari database ke kolom-kolom Excel
    public function map($santri): array
    {
        // Format Tempat & Tanggal Lahir dengan aman
        $tempat_lahir = $santri->tempat_lahir ?? '-';
        $tanggal_lahir = $santri->tanggal_lahir ? Carbon::parse($santri->tanggal_lahir)->translatedFormat('d F Y') : '-';
        $ttl = $tempat_lahir . ', ' . $tanggal_lahir;

        // Amankan data orang tua jika belum diisi di sistem (agar tidak error null)
        $alamat = $santri->orangTua->alamat ?? $santri->alamat ?? '-';
        $nama_ayah = $santri->orangTua->nama_ayah ?? '-';
        $nama_ibu = $santri->orangTua->nama_ibu ?? '-';
        $pekerjaan_ayah = $santri->orangTua->pekerjaan_ayah ?? '-';
        $pekerjaan_ibu = $santri->orangTua->pekerjaan_ibu ?? $santri->orangTua->pekerjaan_wali ?? '-';

        return [
            $santri->full_name ?? '-',     // Sudah diubah ke full_name
            $santri->nis ?? '-',
            $santri->nisn ?? '-',
            $ttl,
            $santri->jenis_kelamin ?? '-',
            'Islam',
            $alamat,
            $santri->mustawa->nama ?? '-', // Sudah diubah ke ->nama
            $santri->tahun_masuk ?? '-',
            $nama_ayah,
            $nama_ibu,
            $pekerjaan_ayah,
            $pekerjaan_ibu,
        ];
    }

    // Nama Tab/Sheet di bawah Excel
    public function title(): string
    {
        // Max 31 Karakter, contoh: "1 A - Putra"
        $title = ($this->nama_mustawa ?? 'Kelas') . ' - ' . $this->label_jk;
        return substr($title, 0, 31);
    }

    // Styling Baris pertama (Tebal/Bold)
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}