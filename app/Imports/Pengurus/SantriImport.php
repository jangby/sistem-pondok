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
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SantriImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private $pondokId;
    private $passwordHash;

    public function __construct()
    {
        $user = Auth::user();
        $this->pondokId = $user->pondok_id ?? $user->pondokStaff->pondok_id ?? null;
        $this->passwordHash = Hash::make('123456');
    }

    /**
     * Membersihkan format tanggal
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Helper: Jika data kosong atau strip (-), jadikan NULL
     * Ini PENTING agar tidak terjadi error duplicate entry pada string kosong
     */
    private function nullableValue($value)
    {
        if (is_null($value)) return null;
        $clean = trim((string)$value);
        return ($clean === '' || $clean === '-') ? null : $clean;
    }

    private function normalizePhone($phone)
    {
        if (!$phone) return null;
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }
        return $phone;
    }

    /**
     * Generate NIS Otomatis
     */
    private function generateNis($tahunMasuk)
    {
        $prefix = $tahunMasuk ?? date('Y');
        $lastSantri = Santri::where('pondok_id', $this->pondokId)
            ->where('nis', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(nis) DESC')
            ->orderBy('nis', 'desc')
            ->first();

        if ($lastSantri) {
            $lastSerial = substr($lastSantri->nis, -4);
            $newSerial = intval($lastSerial) + 1;
        } else {
            $newSerial = 1;
        }
        return $prefix . str_pad($newSerial, 4, '0', STR_PAD_LEFT);
    }

    public function model(array $row)
    {
        // 1. Validasi Nama Wajib Ada
        if (empty($row['nama_lengkap'])) {
            return null;
        }

        // 2. Logika Wali Santri
        $noHpWali = $this->normalizePhone($row['no_hp_ayah'] ?? $row['no_hp_ibu']);
        $orangTuaId = null;

        if ($noHpWali) {
            $orangTua = OrangTua::where('pondok_id', $this->pondokId)
                ->where('phone', $noHpWali)->first();

            if (!$orangTua) {
                $userWali = User::create([
                    'name' => $row['nama_ayah'] ?? $row['nama_ibu'] ?? 'Wali Santri',
                    'email' => $noHpWali . '@pondok.app',
                    'password' => $this->passwordHash,
                    'role' => 'wali_santri',
                    'telepon' => $noHpWali,
                ]);

                $orangTua = OrangTua::create([
                    'user_id' => $userWali->id,
                    'pondok_id' => $this->pondokId,
                    'name' => $userWali->name,
                    'phone' => $noHpWali,
                    'address' => $this->nullableValue($row['alamat']),
                    'pekerjaan' => $this->nullableValue($row['pekerjaan_ayah']),
                    'nik' => $this->nullableValue($row['nik_ayah']),
                ]);
            }
            $orangTuaId = $orangTua->id;
        }

        // 3. Cari Kelas & Asrama
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

        // 4. Data Pendukung
        $jk = 'Laki-laki';
        if (!empty($row['jenis_kelamin'])) {
            $val = strtolower($row['jenis_kelamin']);
            if (str_contains($val, 'p') || str_contains($val, 'w')) $jk = 'Perempuan';
        }
        
        $tahunMasuk = $row['tahun_masuk'] ?? date('Y');
        $tglLahir   = $this->transformDate($row['tanggal_lahir_yyyy_mm_dd'] ?? $row['tanggal_lahir'] ?? null);
        
        // Bersihkan NIS, NIK, KK agar jadi NULL jika kosong
        $inputNis = $this->nullableValue($row['nis']);
        $inputNik = $this->nullableValue($row['nik']);
        $inputKk  = $this->nullableValue($row['no_kk']);

        // 5. Cek Santri Existing
        $santri = null;
        if ($inputNis) {
            // Jika NIS diisi, cari by NIS
            $santri = Santri::where('pondok_id', $this->pondokId)->where('nis', $inputNis)->first();
        } else {
            // Jika NIS kosong, cari by Nama + Tgl Lahir
            $santri = Santri::where('pondok_id', $this->pondokId)
                ->where('full_name', $row['nama_lengkap'])
                ->where('tanggal_lahir', $tglLahir)
                ->first();
        }

        // Array Data Santri (Pakai fungsi nullableValue untuk kolom unik/opsional)
        $dataSantri = [
            'orang_tua_id'  => $orangTuaId,
            'kelas_id'      => $kelasId,
            'asrama_id'     => $asramaId,
            
            // Kolom ini sekarang aman dikosongkan (jadi NULL)
            'nisn'          => $this->nullableValue($row['nisn'] ?? null),
            'nik'           => $inputNik,
            'no_kk'         => $inputKk,
            
            'full_name'     => $row['nama_lengkap'],
            'jenis_kelamin' => $jk,
            'tempat_lahir'  => $this->nullableValue($row['tempat_lahir'] ?? null),
            'tanggal_lahir' => $tglLahir,
            'status'        => 'active',
            'tahun_masuk'   => $tahunMasuk,
            'alamat'        => $this->nullableValue($row['alamat'] ?? null),
            
            // Data Orang Tua (Backup di tabel santri)
            'nama_ayah'     => $this->nullableValue($row['nama_ayah'] ?? null),
            'nama_ibu'      => $this->nullableValue($row['nama_ibu'] ?? null),
            'no_hp_ayah'    => $this->normalizePhone($row['no_hp_ayah'] ?? null),
            'no_hp_ibu'     => $this->normalizePhone($row['no_hp_ibu'] ?? null),
        ];

        // 6. Eksekusi Simpan
        if ($santri) {
            $santri->update($dataSantri);
        } else {
            // Logic Generate NIS jika kosong
            $finalNis = $inputNis ? $inputNis : $this->generateNis($tahunMasuk);
            
            $dataSantri['nis']       = $finalNis;
            $dataSantri['pondok_id'] = $this->pondokId;

            Santri::create($dataSantri);
        }

        return null;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required'],
        ];
    }
}