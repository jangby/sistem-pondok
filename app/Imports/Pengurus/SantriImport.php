<?php

namespace App\Imports\Pengurus;

use App\Models\Santri;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\User;
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
        // Ambil ID pondok dari user yang sedang login (Pengurus)
        $this->pondokId = Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Helper: Handle format tanggal Excel (Angka vs Teks)
     * Mengubah serial number Excel menjadi objek tanggal PHP
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;
        try {
            // Jika format angka (Serial Number Excel), gunakan library phpspreadsheet
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            }
            // Jika format teks biasa (YYYY-MM-DD), gunakan Carbon
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function model(array $row)
    {
        // 1. Cari ID Kelas berdasarkan Nama Kelas di Excel
        $kelas = Kelas::where('pondok_id', $this->pondokId)
            ->where('nama_kelas', $row['nama_kelas'])
            ->first();

        // ==================================================
        // LOGIKA 1: NIS OTOMATIS
        // ==================================================
        $nis = $row['nis'];
        // Ambil tahun masuk dari excel, atau default tahun sekarang
        $tahunMasuk = $row['tahun_masuk'] ?? date('Y'); 

        // Jika NIS kosong di Excel, kita buatkan otomatis
        if (empty($nis)) {
            // Cari santri terakhir di tahun tersebut untuk mendapatkan nomor urut terakhir
            $lastSantri = Santri::where('pondok_id', $this->pondokId)
                ->where('nis', 'like', $tahunMasuk . '%')
                // Order by panjang string dulu agar urutan puluhan/ratusan benar
                ->orderByRaw('LENGTH(nis) DESC') 
                ->orderBy('nis', 'desc')
                ->first();

            // Ambil 4 digit terakhir (nomor urut)
            $lastNo = $lastSantri ? intval(substr($lastSantri->nis, 4)) : 0;
            $newNo = $lastNo + 1;
            
            // Format: TAHUN + 0001 (contoh: 20250001)
            $nis = $tahunMasuk . str_pad($newNo, 4, '0', STR_PAD_LEFT);
        }

        // ==================================================
        // LOGIKA 2: USER & ORANG TUA (SAFE MODE)
        // ==================================================
        $orangTuaId = null;
        $phoneInput = $row['no_hp_wali_wajib_unik'];

        // Hanya proses jika No HP diisi di Excel
        if (!empty($phoneInput)) {
            
            // Bersihkan No HP dari karakter aneh
            $phone = preg_replace('/[^0-9]/', '', $phoneInput);
            
            if (!empty($phone)) {
                // Email Login Dummy: [NoHP]@wali.santri
                $loginEmail = $phone . '@wali.santri';

                // A. Cek Data OrangTua di Database (Profile)
                $orangTua = OrangTua::where('pondok_id', $this->pondokId)
                    ->where('phone', $phone)
                    ->first();

                if (!$orangTua) {
                    // B. Cek/Buat User Login (Tabel 'users')
                    $user = User::where('email', $loginEmail)->first();
                    
                    if (!$user) {
                        $user = User::create([
                            'name'     => $row['nama_wali_akun_app'] ?? 'Wali Santri',
                            'email'    => $loginEmail,
                            'password' => Hash::make('123456'), // Password Default
                        ]);
                        
                        // Pastikan role sudah ada di sistem (Spatie Permission)
                        $user->assignRole('orang-tua');
                    }

                    // C. Buat Profil OrangTua linked ke User
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
        return new Santri([
            'pondok_id' => $this->pondokId,
            
            // Gunakan NIS hasil proses di atas (Manual atau Otomatis)
            'nis' => $nis, 
            'tahun_masuk' => $tahunMasuk,
            
            'full_name' => $row['nama_lengkap'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $this->transformDate($row['tanggal_lahir_yyyy_mm_dd']),
            
            'kelas_id' => $kelas ? $kelas->id : null,
            'orang_tua_id' => $orangTuaId, // Bisa NULL jika No HP kosong
            
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
            // Hapus validasi 'required' pada NIS agar bisa kosong (auto-generate)
            // 'nis' => ['required'], 
            
            'nama_lengkap' => ['required'],
            
            // Ubah jadi nullable agar data santri tetap masuk walau tanpa wali
            'no_hp_wali_wajib_unik' => ['nullable'], 
        ];
    }
}