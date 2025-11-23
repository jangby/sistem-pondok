<?php

namespace App\Imports\Pengurus;

use App\Models\Santri;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;

class SantriImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    private $pondokId;
    private $passwordHash;

    public function __construct()
    {
        $this->pondokId = Auth::user()->pondokStaff->pondok_id;
        
        // OPTIMASI: Hash password default '123456' sekali saja di awal
        // Ini mencegah 'Time Limit Exceeded' karena bcrypt yang berat
        $this->passwordHash = Hash::make('123456');
    }

    /**
     * Helper: Ubah format tanggal Excel (Angka Serial) atau Teks menjadi objek Carbon
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;
        try {
            // Jika format angka (Serial Number Excel, misal: 44567)
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            }
            // Jika format teks biasa (YYYY-MM-DD)
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function model(array $row)
    {
        // Bungkus dalam Transaksi Database agar Aman
        return DB::transaction(function () use ($row) {
            
            // 1. Cari ID Kelas berdasarkan Nama Kelas
            $kelas = Kelas::where('pondok_id', $this->pondokId)
                ->where('nama_kelas', $row['nama_kelas'])
                ->first();

            // ==================================================
            // LOGIKA 1: NIS OTOMATIS (DENGAN LOCKING)
            // ==================================================
            $nis = $row['nis'];
            $tahunMasuk = $row['tahun_masuk'] ?? date('Y'); // Default tahun sekarang

            // Jika kolom NIS kosong, sistem buatkan otomatis
            if (empty($nis)) {
                // Lock tabel untuk mencegah race condition (duplikasi nomor saat import barengan)
                $lastSantri = Santri::where('pondok_id', $this->pondokId)
                    ->where('nis', 'like', $tahunMasuk . '%')
                    ->orderByRaw('LENGTH(nis) DESC') // Urutkan panjang string dulu (agar 100 > 99)
                    ->orderBy('nis', 'desc')
                    ->lockForUpdate() // <--- KUNCI RAHASIA ANTI BENTROK
                    ->first();

                // Ambil 4 digit terakhir, ubah ke integer, tambah 1
                $lastNo = $lastSantri ? intval(substr($lastSantri->nis, 4)) : 0;
                $newNo = $lastNo + 1;
                
                // Format: TAHUN + 4 DIGIT URUT (Contoh: 20250001)
                $nis = $tahunMasuk . str_pad($newNo, 4, '0', STR_PAD_LEFT);
            }

            // ==================================================
            // LOGIKA 2: USER & ORANG TUA (SAFE MODE)
            // ==================================================
            $orangTuaId = null;
            $phoneInput = $row['no_hp_wali_wajib_unik'];

            // Hanya proses pembuatan akun jika No HP diisi
            if (!empty($phoneInput)) {
                // Bersihkan karakter non-angka
                $phone = preg_replace('/[^0-9]/', '', $phoneInput);
                
                if (!empty($phone)) {
                    $loginEmail = $phone . '@wali.santri';

                    // Cek apakah data Orang Tua sudah ada?
                    $orangTua = OrangTua::where('pondok_id', $this->pondokId)
                        ->where('phone', $phone)
                        ->lockForUpdate() // Lock juga baris ini
                        ->first();

                    if (!$orangTua) {
                        // Cek apakah User Login sudah ada?
                        $user = User::where('email', $loginEmail)->first();
                        
                        if (!$user) {
                            // Buat User Baru
                            $user = User::create([
                                'name'     => $row['nama_wali_akun_app'] ?? 'Wali Santri',
                                'email'    => $loginEmail,
                                'password' => $this->passwordHash, // Pakai hash yang sudah disiapkan
                            ]);
                            $user->assignRole('orang-tua');
                        }

                        // Buat Profil Orang Tua linked ke User
                        $orangTua = OrangTua::create([
                            'pondok_id' => $this->pondokId,
                            'user_id'   => $user->id,
                            'name'      => $row['nama_wali_akun_app'] ?? 'Wali Santri',
                            'phone'     => $phone,
                            'address'   => $row['alamat'] ?? '-',
                        ]);
                    }
                    $orangTuaId = $orangTua->id;
                }
            }

            // ==================================================
            // LOGIKA 3: SIMPAN DATA SANTRI
            // ==================================================
            // Gunakan create() langsung untuk memastikan data tersimpan sebelum lock dilepas
            $santri = Santri::create([
                'pondok_id' => $this->pondokId,
                'nis' => $nis, // NIS Manual atau Otomatis
                'tahun_masuk' => $tahunMasuk,
                'full_name' => $row['nama_lengkap'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $this->transformDate($row['tanggal_lahir_yyyy_mm_dd']),
                
                'kelas_id' => $kelas ? $kelas->id : null,
                'orang_tua_id' => $orangTuaId, // Bisa NULL jika No HP kosong
                
                'status' => 'active',
                'qrcode_token' => 'S-' . time() . '-' . Str::random(8), // Token QR Unik

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

            return $santri;
        });
    }

    public function rules(): array
    {
        return [
            // 'nis' => ['required'], // Dihapus agar boleh kosong (auto-generate)
            'nama_lengkap' => ['required'],
            'no_hp_wali_wajib_unik' => ['nullable'], // Boleh kosong (tanpa wali)
        ];
    }

    // Proses per 100 baris untuk menghemat memori
    public function chunkSize(): int
    {
        return 100;
    }
}