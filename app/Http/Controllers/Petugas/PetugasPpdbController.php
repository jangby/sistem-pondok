<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CalonSantri;
use App\Models\PpdbSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PetugasPpdbController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'total' => CalonSantri::count(),
            'today' => CalonSantri::whereDate('created_at', today())->count(),
            'smp'   => CalonSantri::where('jenjang', 'SMP')->count(),
            'sma'   => CalonSantri::where('jenjang', 'SMA')->count(),
        ];

        $query = CalonSantri::with('user')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        $santris = $query->paginate(10);
        return view('petugas.dashboard', compact('stats', 'santris'));
    }

    public function create()
    {
        $gelombang = PpdbSetting::where('is_active', true)->first();
        if(!$gelombang) {
            return redirect()->route('petugas.dashboard')->with('error', 'Tidak ada gelombang pendaftaran yang aktif.');
        }
        return view('petugas.pendaftaran.create', compact('gelombang'));
    }

    public function store(Request $request)
    {
        // 1. ATURAN VALIDASI LENGKAP (8 BERKAS)
        $rules = [
            // Biodata & Ortu (Wajib)
            'nama_lengkap'  => 'required|string|max:255',
            'nik'           => 'required|numeric|digits:16|unique:calon_santris,nik',
            'no_kk'         => 'required|numeric|digits:16',
            'nisn'          => 'nullable|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string',
            'tanggal_lahir' => 'required|date',
            'anak_ke'       => 'required|numeric',
            'jumlah_saudara'=> 'required|numeric',
            'alamat'        => 'required|string',
            'rt'            => 'required|numeric',
            'rw'            => 'required|numeric',
            'desa'          => 'required|string',
            'kecamatan'     => 'required|string',
            'kabupaten'     => 'required|string',
            'provinsi'      => 'required|string',
            'kode_pos'      => 'nullable|numeric',
            'sekolah_asal'  => 'required|string',
            'jenjang'       => 'required|string',
            'nama_ayah'     => 'required|string',
            'nik_ayah'      => 'nullable|numeric|digits:16',
            'pekerjaan_ayah'=> 'required|string',
            'nama_ibu'      => 'required|string',
            'nik_ibu'       => 'nullable|numeric|digits:16',
            'pekerjaan_ibu' => 'required|string',
            'penghasilan'   => 'required|string',
            'no_hp'         => 'required|numeric',

            // BERKAS (Semua PDF kecuali Foto)
            'foto_santri'   => 'nullable|image|max:2048', 
            'file_kk'       => 'nullable|mimes:pdf|max:2048',
            'file_akta'     => 'nullable|mimes:pdf|max:2048',
            'file_ijazah'   => 'nullable|mimes:pdf|max:2048',
            'file_skl'      => 'nullable|mimes:pdf|max:2048',
            'file_ktp_ayah' => 'nullable|mimes:pdf|max:2048',
            'file_ktp_ibu'  => 'nullable|mimes:pdf|max:2048',
            'file_kip'      => 'nullable|mimes:pdf|max:2048',
        ];

        $messages = [
            'required' => ':attribute wajib diisi.',
            'numeric'  => ':attribute harus berupa angka.',
            'digits'   => ':attribute harus berjumlah :digits digit.',
            'unique'   => ':attribute sudah terdaftar.',
            'mimes'    => 'Format :attribute harus PDF (Kecuali Foto).',
            'image'    => ':attribute harus berupa gambar (JPG/PNG).',
            'max'      => 'Ukuran :attribute maksimal 2MB.',
        ];

        $attributes = [
            'nama_lengkap' => 'Nama Lengkap',
            'nik' => 'NIK Santri',
            'foto_santri' => 'Pas Foto',
            'file_kk' => 'Kartu Keluarga',
            'file_akta' => 'Akta Kelahiran',
            'file_ijazah' => 'Ijazah',
            'file_skl' => 'SKL',
            'file_ktp_ayah' => 'KTP Ayah',
            'file_ktp_ibu' => 'KTP Ibu',
            'file_kip' => 'KIP/KIS',
        ];

        $request->validate($rules, $messages, $attributes);

        $gelombang = PpdbSetting::where('is_active', true)->first();

        DB::beginTransaction();
        try {
            // A. Create User
            $emailDummy = $request->nik . '@santri.offline';
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $emailDummy,
                'telepon' => $request->no_hp,
                'password' => Hash::make('12345678'),
            ]);
            $user->assignRole('calon_santri');

            // B. Upload Berkas Loop (8 File)
            $paths = [];
            // Folder penyimpanan disamakan dengan controller online: ppdb/foto atau ppdb/berkas
            $uploadConfig = [
                'foto_santri'   => 'ppdb/foto',
                'file_kk'       => 'ppdb/berkas',
                'file_akta'     => 'ppdb/berkas',
                'file_ijazah'   => 'ppdb/berkas',
                'file_skl'      => 'ppdb/berkas',
                'file_ktp_ayah' => 'ppdb/berkas',
                'file_ktp_ibu'  => 'ppdb/berkas',
                'file_kip'      => 'ppdb/berkas',
            ];
            
            foreach ($uploadConfig as $field => $folder) {
                if ($request->hasFile($field)) {
                    $paths[$field] = $request->file($field)->store($folder, 'public');
                } else {
                    $paths[$field] = null;
                }
            }

            // C. Create Calon Santri
            $calonSantri = CalonSantri::create([
                'user_id'           => $user->id,
                'ppdb_setting_id'   => $gelombang->id,
                'full_name'         => $request->nama_lengkap,
                'nik'               => $request->nik,
                'no_kk'             => $request->no_kk,
                'nisn'              => $request->nisn,
                'jenis_kelamin'     => $request->jenis_kelamin,
                'tempat_lahir'      => $request->tempat_lahir,
                'tanggal_lahir'     => $request->tanggal_lahir,
                'anak_ke'           => $request->anak_ke,
                'jumlah_saudara'    => $request->jumlah_saudara,
                
                'alamat'            => $request->alamat,
                'rt'                => $request->rt,
                'rw'                => $request->rw,
                'desa'              => $request->desa,
                'kecamatan'         => $request->kecamatan,
                'kabupaten'         => $request->kabupaten,
                'provinsi'          => $request->provinsi,
                'kode_pos'          => $request->kode_pos,
                
                'sekolah_asal'      => $request->sekolah_asal,
                'jenjang'           => $request->jenjang,
                
                'nama_ayah'         => $request->nama_ayah,
                'nik_ayah'          => $request->nik_ayah,
                'pekerjaan_ayah'    => $request->pekerjaan_ayah,
                'nama_ibu'          => $request->nama_ibu,
                'nik_ibu'           => $request->nik_ibu,
                'pekerjaan_ibu'     => $request->pekerjaan_ibu,
                'penghasilan_ortu'  => $request->penghasilan,
                'no_hp_ayah'        => $request->no_hp,
                'no_hp_santri'      => $request->no_hp, 
                
                // Simpan Path File
                'foto_santri'       => $paths['foto_santri'],
                'file_kk'           => $paths['file_kk'],
                'file_akta'         => $paths['file_akta'],
                'file_ijazah'       => $paths['file_ijazah'],
                'file_skl'          => $paths['file_skl'],
                'file_ktp_ayah'     => $paths['file_ktp_ayah'],
                'file_ktp_ibu'      => $paths['file_ktp_ibu'],
                'file_kip'          => $paths['file_kip'],
                
                'status_pendaftaran'=> 'menunggu_verifikasi',
                'status_pembayaran' => 'belum_bayar',
                'no_pendaftaran'    => $gelombang->tahun_ajaran . sprintf('%04d', $user->id),
            ]);

            DB::commit();
            return redirect()->route('petugas.pendaftaran.success', $calonSantri->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mendaftar: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $calonSantri = CalonSantri::findOrFail($id);
        $gelombang = PpdbSetting::where('is_active', true)->first();
        return view('petugas.pendaftaran.edit', compact('calonSantri', 'gelombang'));
    }

    public function update(Request $request, $id)
    {
        $calonSantri = CalonSantri::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik'          => 'required|numeric|digits:16|unique:calon_santris,nik,'.$id,
            'no_hp'        => 'required|numeric',
            // File nullable saat update
            'foto_santri'   => 'nullable|image|max:2048', 
            'file_kk'       => 'nullable|mimes:pdf|max:2048',
            'file_akta'     => 'nullable|mimes:pdf|max:2048',
            'file_ijazah'   => 'nullable|mimes:pdf|max:2048',
            'file_skl'      => 'nullable|mimes:pdf|max:2048',
            'file_ktp_ayah' => 'nullable|mimes:pdf|max:2048',
            'file_ktp_ibu'  => 'nullable|mimes:pdf|max:2048',
            'file_kip'      => 'nullable|mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = $calonSantri->user;
            $user->name = $request->nama_lengkap;
            if($user->email == $calonSantri->nik . '@santri.offline') {
                $user->email = $request->nik . '@santri.offline';
            }
            $user->save();

            // Config Upload
            $uploadConfig = [
                'foto_santri'   => 'ppdb/foto',
                'file_kk'       => 'ppdb/berkas',
                'file_akta'     => 'ppdb/berkas',
                'file_ijazah'   => 'ppdb/berkas',
                'file_skl'      => 'ppdb/berkas',
                'file_ktp_ayah' => 'ppdb/berkas',
                'file_ktp_ibu'  => 'ppdb/berkas',
                'file_kip'      => 'ppdb/berkas',
            ];
            
            $updatedFiles = [];

            foreach ($uploadConfig as $field => $folder) {
                if ($request->hasFile($field)) {
                    // Hapus file lama
                    if ($calonSantri->$field && Storage::disk('public')->exists($calonSantri->$field)) {
                        Storage::disk('public')->delete($calonSantri->$field);
                    }
                    // Simpan file baru
                    $updatedFiles[$field] = $request->file($field)->store($folder, 'public');
                }
            }

            // Gabungkan data input teks (gunakan except token dll)
            // Cara cepat ambil semua input kecuali file & token
            $inputData = $request->except(['_token', '_method', 'foto_santri', 'file_kk', 'file_akta', 'file_ijazah', 'file_skl', 'file_ktp_ayah', 'file_ktp_ibu', 'file_kip']);
            
            // Mapping nama_lengkap -> full_name, dll jika perlu manual
            $inputData['full_name'] = $request->nama_lengkap;
            $inputData['no_hp_ayah'] = $request->no_hp;
            $inputData['no_hp_santri'] = $request->no_hp;
            $inputData['penghasilan_ortu'] = $request->penghasilan;

            // Merge
            $finalData = array_merge($inputData, $updatedFiles);
            
            $calonSantri->update($finalData);

            DB::commit();
            return redirect()->route('petugas.dashboard')->with('success', 'Data santri berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function success($id)
    {
        $calonSantri = CalonSantri::with('user', 'ppdbSetting')->findOrFail($id);
        $credentials = ['username' => $calonSantri->user->email, 'password' => '12345678'];
        return view('petugas.pendaftaran.success', compact('calonSantri', 'credentials'));
    }
}