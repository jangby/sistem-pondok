<?php

namespace App\Imports\Pengurus;

use App\Models\Santri;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\User; // Tambahkan Model User
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class SantriImport implements ToModel, WithHeadingRow, WithValidation
{
    private $pondokId;

    public function __construct()
    {
        $this->pondokId = Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Helper: Handle format tanggal Excel (Angka vs Teks)
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            }
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function model(array $row)
    {
        // 1. Cari ID Kelas
        $kelas = Kelas::where('pondok_id', $this->pondokId)
            ->where('nama_kelas', $row['nama_kelas'])
            ->first();

        // ==================================================
        // PERBAIKAN LOGIKA USER & ORANG TUA
        // ==================================================
        
        // Bersihkan No HP
        $phone = preg_replace('/[^0-9]/', '', $row['no_hp_wali_wajib_unik']);
        
        // Buat Email Login Dummy: [NoHP]@wali.santri
        $loginEmail = $phone . '@wali.santri';

        // A. Cek Data OrangTua di Database Pondok
        $orangTua = OrangTua::where('pondok_id', $this->pondokId)
            ->where('phone', $phone)
            ->first();

        // Jika OrangTua belum ada, kita buat User dulu, baru OrangTua
        if (!$orangTua) {
            
            // B. Cek/Buat User Login (Tabel 'users')
            $user = User::where('email', $loginEmail)->first();
            
            if (!$user) {
                $user = User::create([
                    'name'     => $row['nama_wali_akun_app'],
                    'email'    => $loginEmail,
                    'password' => Hash::make('123456'), // Password Default
                ]);
                
                // Assign Role (Pastikan Spatie Permission terinstall)
                $user->assignRole('orang-tua');
            }

            // C. Buat Profil OrangTua (Tabel 'orang_tuas') linked ke User
            $orangTua = OrangTua::create([
                'pondok_id' => $this->pondokId,
                'user_id'   => $user->id, // <--- PENTING: Link ke tabel users
                'name'      => $row['nama_wali_akun_app'],
                'phone'     => $phone,
                'address'   => $row['alamat'] ?? '-',
            ]);
        }

        // ==================================================
        // SIMPAN DATA SANTRI
        // ==================================================
        return new Santri([
            'pondok_id' => $this->pondokId,
            'nis' => $row['nis'],
            'full_name' => $row['nama_lengkap'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $this->transformDate($row['tanggal_lahir_yyyy_mm_dd']),
            
            'kelas_id' => $kelas ? $kelas->id : null,
            'orang_tua_id' => $orangTua->id,
            'status' => 'active',
            'qrcode_token' => 'S-' . time() . '-' . Str::random(8),

            // Data Detail (Alamat & EMIS)
            'alamat' => $row['alamat'],
            'rt' => $row['rt'],
            'rw' => $row['rw'],
            'desa' => $row['desa'],
            'kecamatan' => $row['kecamatan'],
            'kode_pos' => $row['kode_pos'],

            'nama_ayah' => $row['nama_ayah'],
            'nik_ayah' => $row['nik_ayah'],
            'thn_lahir_ayah' => $row['thn_lahir_ayah'],
            'pendidikan_ayah' => $row['pendidikan_ayah'],
            'pekerjaan_ayah' => $row['pekerjaan_ayah'],
            'penghasilan_ayah' => $row['penghasilan_ayah'],

            'nama_ibu' => $row['nama_ibu'],
            'nik_ibu' => $row['nik_ibu'],
            'thn_lahir_ibu' => $row['thn_lahir_ibu'],
            'pendidikan_ibu' => $row['pendidikan_ibu'],
            'pekerjaan_ibu' => $row['pekerjaan_ibu'],
            'penghasilan_ibu' => $row['penghasilan_ibu'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nis' => ['required'],
            'nama_lengkap' => ['required'],
            'no_hp_wali_wajib_unik' => ['required'],
        ];
    }
}