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
        if (!$value) return null;
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // Jika gagal parsing, biarkan null (jangan error)
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
        return new Santri([
            'pondok_id'     => $this->pondokId,
            'orang_tua_id'  => $orangTuaId,
            'kelas_id'      => $kelasId,
            'asrama_id'     => $asramaId,
            
            // Data Utama
            'nis'           => $row['nis'] ?? (date('ym') . rand(1000, 9999)), // Auto NIS jika kosong
            'nisn'          => $row['nisn'],
            'nik'           => $row['nik'],
            'no_kk'         => $row['no_kk'],
            'full_name'     => $row['nama_lengkap'],
            'jenis_kelamin' => $jk,
            'tempat_lahir'  => $row['tempat_lahir'],
            'tanggal_lahir' => $this->transformDate($row['tanggal_lahir']),
            'status'        => 'active',
            'tahun_masuk'   => $row['tahun_masuk'] ?? date('Y'),

            // Data Pelengkap
            'anak_ke'       => is_numeric($row['anak_ke'] ?? null) ? $row['anak_ke'] : null,
            'jumlah_saudara'=> is_numeric($row['jumlah_saudara'] ?? null) ? $row['jumlah_saudara'] : null,
            'golongan_darah'=> $row['golongan_darah'],
            'riwayat_penyakit'=> $row['riwayat_penyakit'],

            // Alamat & Ortu (Tabel Santri Baru)
            'alamat'        => $row['alamat'],
            'rt'            => $row['rt'],
            'rw'            => $row['rw'],
            'desa'          => $row['desa'],
            'kecamatan'     => $row['kecamatan'],
            'kode_pos'      => $row['kode_pos'],
            
            'nama_ayah'         => $row['nama_ayah'],
            'nik_ayah'          => $row['nik_ayah'],
            'thn_lahir_ayah'    => is_numeric($row['thn_lahir_ayah'] ?? null) ? $row['thn_lahir_ayah'] : null,
            'pendidikan_ayah'   => $row['pendidikan_ayah'],
            'pekerjaan_ayah'    => $row['pekerjaan_ayah'],
            'penghasilan_ayah'  => $row['penghasilan_ayah'],
            'no_hp_ayah'        => $row['no_hp_ayah'], // Penting

            'nama_ibu'          => $row['nama_ibu'],
            'nik_ibu'           => $row['nik_ibu'],
            'thn_lahir_ibu'     => is_numeric($row['thn_lahir_ibu'] ?? null) ? $row['thn_lahir_ibu'] : null,
            'pendidikan_ibu'    => $row['pendidikan_ibu'],
            'pekerjaan_ibu'     => $row['pekerjaan_ibu'],
            'penghasilan_ibu'   => $row['penghasilan_ibu'],
            'no_hp_ibu'         => $row['no_hp_ibu'], // Penting
        ]);
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