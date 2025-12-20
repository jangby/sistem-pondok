<?php

namespace App\Imports\Pengurus;

use App\Models\Santri;
use App\Models\Kelas;
use App\Models\Asrama;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows; // Tambahan penting

class SantriImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private $pondokId;
    private $passwordHash;

    public function __construct()
    {
        // Ambil ID Pondok (Support login sebagai Admin atau Staff)
        $user = Auth::user();
        $this->pondokId = $user->pondok_id ?? $user->pondokStaff->pondok_id ?? null;
        
        // Hash password default sekali saja untuk performa
        $this->passwordHash = Hash::make('123456');
    }

    /**
     * Membersihkan format tanggal dari Excel
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;

        try {
            // Cek 1: Jika value adalah Angka (Serial Date Excel, misal: 44562)
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            // Cek 2: Jika value adalah String Teks (misal: "2024-05-20" atau "20/05/2024")
            // Gunakan Carbon untuk parsing teks tanggal
            return \Carbon\Carbon::parse($value)->format('Y-m-d');

        } catch (\Exception $e) {
            // Jika gagal parsing (format ngawur), kembalikan null agar tidak error sistem
            return null;
        }
    }

    /**
     * Membersihkan No HP (hapus spasi, strip, ubah 62 jadi 0)
     */
    private function normalizePhone($phone)
    {
        if (!$phone) return null;
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }
        return $phone;
    }

    public function model(array $row)
    {
        // Debugging: Cek apakah data masuk (bisa dilihat di storage/logs/laravel.log)
        // Log::info('Importing Row:', $row);

        // 1. Validasi Manual Sederhana
        if (empty($row['nama_lengkap'])) {
            return null; // Skip jika nama kosong
        }

        // 2. Logika Wali Santri (Cari atau Buat Baru)
        $noHpWali = $this->normalizePhone($row['no_hp_ayah'] ?? $row['no_hp_ibu']);
        // Jika HP kosong, gunakan format dummy agar unik: nis.random@no-hp.com
        $nisDummy = $row['nis'] ?? rand(1000,9999);
        
        $orangTuaId = null;

        // Jika ada No HP, kita coba cari data orang tua yang sudah ada
        if ($noHpWali) {
            $orangTua = OrangTua::where('pondok_id', $this->pondokId)
                ->where('phone', $noHpWali)->first();

            if (!$orangTua) {
                // Buat User Login untuk Wali
                $userWali = User::create([
                    'name' => $row['nama_ayah'] ?? $row['nama_ibu'] ?? 'Wali Santri',
                    'email' => $noHpWali . '@pondok.app', // Email dummy
                    'password' => $this->passwordHash,
                    'role' => 'wali_santri',
                    'telepon' => $noHpWali,
                ]);

                // Buat Data Orang Tua
                $orangTua = OrangTua::create([
                    'user_id' => $userWali->id,
                    'pondok_id' => $this->pondokId,
                    'name' => $userWali->name,
                    'phone' => $noHpWali,
                    'address' => $row['alamat'] ?? '-',
                    'pekerjaan' => $row['pekerjaan_ayah'] ?? '-',
                    'nik' => $row['nik_ayah'] ?? null,
                ]);
            }
            $orangTuaId = $orangTua->id;
        }

        // 3. Cari Kelas & Asrama (Berdasarkan Nama)
        $kelasId = null;
        if (!empty($row['nama_kelas'])) {
            $kelas = Kelas::where('pondok_id', $this->pondokId)
                ->where('nama_kelas', $row['nama_kelas'])->first();
            $kelasId = $kelas ? $kelas->id : null;
        }

        $asramaId = null;
        if (!empty($row['nama_asrama'])) {
            $asrama = Asrama::where('pondok_id', $this->pondokId)
                ->where('nama_asrama', $row['nama_asrama'])->first();
            $asramaId = $asrama ? $asrama->id : null;
        }

        // 4. Normalisasi Jenis Kelamin
        $jk = 'Laki-laki'; // Default
        if (!empty($row['jenis_kelamin'])) {
            $val = strtolower($row['jenis_kelamin']);
            if (str_contains($val, 'p') || str_contains($val, 'w')) $jk = 'Perempuan';
        }

        // 5. Simpan Data Santri
        Santri::updateOrCreate(
            [
                'nis'       => $row['nis'],             // Kunci Unik: NIS
                'pondok_id' => $this->pondokId          // Kunci Unik: Pondok
            ],
            [
                // Update/Isi data lainnya
                'orang_tua_id'  => $orangTuaId,
                'kelas_id'      => $kelasId,
                'asrama_id'     => $asramaId,
                
                'nisn'          => $row['nisn'] ?? null,
                'nik'           => $row['nik'] ?? null,
                'no_kk'         => $row['no_kk'] ?? null,
                'full_name'     => $row['nama_lengkap'] ?? 'Tanpa Nama',
                'jenis_kelamin' => $jk, // Pastikan variabel $jk dari logika di atasnya
                'tempat_lahir'  => $row['tempat_lahir'] ?? null,
                
                // PENTING: Gunakan nama key sesuai fix sebelumnya (tanggal_lahir_yyyy_mm_dd atau tanggal_lahir)
                // Sesuaikan dengan header Excel Anda. Jika pakai solusi saya sebelumnya, pakai key yang benar.
                'tanggal_lahir' => $this->transformDate($row['tanggal_lahir_yyyy_mm_dd'] ?? $row['tanggal_lahir'] ?? null),
                
                'status'        => 'active',
                'tahun_masuk'   => $row['tahun_masuk'] ?? date('Y'),

                // Data Pelengkap
                'anak_ke'       => is_numeric($row['anak_ke'] ?? null) ? $row['anak_ke'] : null,
                'jumlah_saudara'=> is_numeric($row['jumlah_saudara'] ?? null) ? $row['jumlah_saudara'] : null,
                'golongan_darah'=> $row['golongan_darah'] ?? null,
                'riwayat_penyakit'=> $row['riwayat_penyakit'] ?? null,

                // Alamat
                'alamat'        => $row['alamat'] ?? null,
                'rt'            => $row['rt'] ?? null,
                'rw'            => $row['rw'] ?? null,
                'desa'          => $row['desa'] ?? null,
                'kecamatan'     => $row['kecamatan'] ?? null,
                'kode_pos'      => $row['kode_pos'] ?? null,
                
                // Data Ortu (untuk backup di tabel santri)
                'nama_ayah'         => $row['nama_ayah'] ?? null,
                'nik_ayah'          => $row['nik_ayah'] ?? null,
                'thn_lahir_ayah'    => is_numeric($row['thn_lahir_ayah'] ?? null) ? $row['thn_lahir_ayah'] : null,
                'pendidikan_ayah'   => $row['pendidikan_ayah'] ?? null,
                'pekerjaan_ayah'    => $row['pekerjaan_ayah'] ?? null,
                'penghasilan_ayah'  => $row['penghasilan_ayah'] ?? null,
                'no_hp_ayah'        => $row['no_hp_ayah'] ?? null,

                'nama_ibu'          => $row['nama_ibu'] ?? null,
                'nik_ibu'           => $row['nik_ibu'] ?? null,
                'thn_lahir_ibu'     => is_numeric($row['thn_lahir_ibu'] ?? null) ? $row['thn_lahir_ibu'] : null,
                'pendidikan_ibu'    => $row['pendidikan_ibu'] ?? null,
                'pekerjaan_ibu'     => $row['pekerjaan_ibu'] ?? null,
                'penghasilan_ibu'   => $row['penghasilan_ibu'] ?? null,
                'no_hp_ibu'         => $row['no_hp_ibu'] ?? null,
            ]
        );

        // KEMBALIKAN NULL
        // Karena kita sudah menyimpan data secara manual pakai updateOrCreate,
        // kita return null agar library Excel tidak mencoba meng-insert ulang (yang bikin error duplicate).
        return null;
    }

    /**
     * Aturan Validasi (Longgar agar tidak mudah gagal)
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required'], // Hanya nama yang wajib
            // 'nis' => ['unique:santris,nis'], // Matikan dulu unique check agar tidak error jika ada duplikat di Excel
        ];
    }
}