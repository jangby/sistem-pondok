<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\CalonSantri;
use App\Models\Santri;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Opsional
use App\Models\PpdbTransaction; // Pastikan di import

class PpdbPendaftarController extends Controller
{
    // 1. LIST PENDAFTAR
    public function index(Request $request)
    {
        $query = CalonSantri::with(['user', 'ppdbSetting'])->latest();

        // Filter sederhana
        if ($request->has('status')) {
            $query->where('status_pendaftaran', $request->status);
        }

        $pendaftar = $query->paginate(10);
        return view('adminpondok.ppdb.pendaftar.index', compact('pendaftar'));
    }

    // 2. DETAIL PENDAFTAR (VERIFIKASI)
    public function show($id)
    {
        // Ubah nama variabel dari $calon menjadi $calonSantri
        $calonSantri = CalonSantri::with(['ppdbSetting', 'ppdbSetting.biayas'])->findOrFail($id);
        
        // Kirim ke view dengan nama 'calonSantri'
        return view('adminpondok.ppdb.pendaftar.show', compact('calonSantri'));
    }

    // 3. KONFIRMASI PEMBAYARAN MANUAL (Opsional)
    public function confirmPayment($id)
    {
        $calon = CalonSantri::findOrFail($id);
        
        // Set lunas & update total bayar sesuai tagihan penuh
        $totalBiaya = $calon->total_biaya;
        
        $calon->update([
            'status_pembayaran' => 'lunas',
            'total_sudah_bayar' => $totalBiaya
        ]);

        return back()->with('success', 'Pembayaran dikonfirmasi LUNAS secara manual.');
    }

    // 4. TERIMA SANTRI (MIGRASI DATA)
    // Import class Hash & Str di paling atas file jika belum ada
    // use Illuminate\Support\Facades\Hash;
    // use Illuminate\Support\Str;

    public function approve($id)
    {
        $calon = CalonSantri::findOrFail($id);

        // 1. CEK VALIDASI KELENGKAPAN DATA (Syarat sebelum diterima)
        if (
            empty($calon->nama_ayah) || 
            empty($calon->nama_ibu) || 
            empty($calon->alamat) || 
            empty($calon->file_kk) // Minimal KK harus ada
        ) {
            return back()->with('error', 'Gagal Menerima: Biodata santri/orang tua belum lengkap. Mohon minta wali santri melengkapi data terlebih dahulu.');
        }

        if ($calon->status_pendaftaran == 'diterima') {
            return back()->with('error', 'Santri ini sudah diterima sebelumnya.');
        }

        DB::transaction(function () use ($calon) {
            
            // 2. BUAT AKUN LOGIN ORANG TUA (Otomatis dari Data Ibu)
            // Username: No HP Ibu / Ayah
            // Password Default: 12345678
            $noHp = $calon->no_hp_ibu ?? $calon->no_hp_ayah ?? '0000000000';
            $namaAkun = $calon->nama_ibu ?? $calon->nama_ayah;
            
            // Cek apakah user dengan no hp ini sudah ada?
            $userOrtu = User::where('email', $noHp . '@orangtua.com')->first(); // Pakai dummy email biar unik

            if (!$userOrtu) {
                $userOrtu = User::create([
                    'name' => $namaAkun,
                    'email' => $noHp . '@orangtua.com', // Dummy email unik
                    'telepon' => $noHp,
                    'password' => Hash::make('12345678'), // Password Default
                ]);
                $userOrtu->assignRole('orang-tua');
            }

            // 3. PINDAHKAN DATA KE TABEL ORANG TUA
            $ortu = OrangTua::create([
                'user_id' => $userOrtu->id,
                // SOLUSI ERROR 1364: Isi kolom 'name'
                'name' => $namaAkun, 
                
                // Data Detail
                'nama_ayah' => $calon->nama_ayah,
                'nik_ayah' => $calon->nik_ayah,
                'thn_lahir_ayah' => $calon->thn_lahir_ayah,
                'pendidikan_ayah' => $calon->pendidikan_ayah,
                'pekerjaan_ayah' => $calon->pekerjaan_ayah,
                'penghasilan_ayah' => $calon->penghasilan_ayah,
                'no_hp_ayah' => $calon->no_hp_ayah,
                
                'nama_ibu' => $calon->nama_ibu,
                'nik_ibu' => $calon->nik_ibu,
                'thn_lahir_ibu' => $calon->thn_lahir_ibu,
                'pendidikan_ibu' => $calon->pendidikan_ibu,
                'pekerjaan_ibu' => $calon->pekerjaan_ibu,
                'penghasilan_ibu' => $calon->penghasilan_ibu,
                'no_hp_ibu' => $calon->no_hp_ibu,
                
                'nama_wali' => $calon->nama_wali,
                'no_hp_wali' => $calon->no_hp_wali,
                'no_kk' => $calon->no_kk,
                'alamat' => $calon->alamat, // Opsional jika tabel ortu punya alamat
            ]);

            // 4. GENERATE NIS & TAHUN MASUK
            $tahunMasuk = date('Y');
            
            // Logic NIS: TahunMasuk + 4 Digit Urutan (Contoh: 20250001)
            $lastSantri = Santri::whereYear('created_at', $tahunMasuk)->latest()->first();
            if ($lastSantri) {
                // Ambil 4 digit terakhir, tambah 1
                $lastNis = intval(substr($lastSantri->nis, -4));
                $urutan = $lastNis + 1;
            } else {
                $urutan = 1;
            }
            $nisBaru = $tahunMasuk . sprintf('%04d', $urutan);

            // 5. PINDAHKAN DATA KE TABEL SANTRI
            $santri = Santri::create([
                'user_id' => $calon->user_id,
                'orang_tua_id' => $ortu->id,
                
                // Data Generate Otomatis
                'nis' => $nisBaru,
                'qrcode' => $nisBaru, // QR Code disamakan dengan NIS
                'tahun_masuk' => $tahunMasuk,
                'status_mukim' => 'mukim', // Default
                'tanggal_masuk' => now(),

                // Data Migrasi
                'nisn' => $calon->nisn,
                'nik' => $calon->nik,
                'nama_lengkap' => $calon->full_name,
                'tempat_lahir' => $calon->tempat_lahir,
                'tanggal_lahir' => $calon->tanggal_lahir,
                'jenis_kelamin' => $calon->jenis_kelamin,
                'alamat' => $calon->alamat,
                
                // Data Alamat Rinci (Pastikan tabel santri punya kolom ini, jika tidak, gabungkan ke alamat)
                'rt' => $calon->rt,
                'rw' => $calon->rw,
                'desa' => $calon->desa,
                'kecamatan' => $calon->kecamatan,
                'kabupaten' => $calon->kabupaten,
                'provinsi' => $calon->provinsi,
                'kode_pos' => $calon->kode_pos,
                
                'sekolah_asal' => $calon->sekolah_asal,
                'golongan_darah' => $calon->golongan_darah,
                'riwayat_penyakit' => $calon->riwayat_penyakit,
                
                // File
                'foto' => $calon->foto_santri,
            ]);

            // 6. UPDATE STATUS & ROLE
            $calon->update([
                'status_pendaftaran' => 'diterima',
                'tanggal_diterima' => now()
            ]);

            // Ubah role user santri dari 'calon_santri' jadi 'santri'
            $userSantri = User::find($calon->user_id);
            if($userSantri) {
                $userSantri->syncRoles('santri');
                // Update email santri agar resmi (opsional)
                // $userSantri->update(['email' => $nisBaru.'@santri.com']); 
            }
        });

        return redirect()->route('adminpondok.ppdb.pendaftar.index')->with('success', 'Santri BERHASIL DITERIMA. Akun Orang Tua & Data Santri telah dibuat otomatis.');
    }

    // 5. TOLAK SANTRI
    public function reject($id)
    {
        $calon = CalonSantri::findOrFail($id);
        $calon->update(['status_pendaftaran' => 'ditolak']);
        return back()->with('success', 'Pendaftaran ditolak.');
    }
    
    // 6. HAPUS DATA (Hanya jika draft/sampah)
    public function destroy($id)
    {
        $calon = CalonSantri::findOrFail($id);
        // Hapus usernya juga agar bersih
        $calon->user->delete(); 
        $calon->delete();
        
        return back()->with('success', 'Data pendaftar dihapus permanen.');
    }

    public function payment($id)
{
    $calonSantri = CalonSantri::with(['ppdbSetting', 'ppdbSetting.biayas'])->findOrFail($id);
    
    // Ambil semua riwayat transaksi (Online & Cash) urutkan dari yang terbaru
    $riwayat = PpdbTransaction::where('calon_santri_id', $id)
        ->whereIn('status', ['paid', 'success', 'settlement']) // Hanya yang sukses
        ->orderBy('created_at', 'desc')
        ->get();

    return view('adminpondok.ppdb.pendaftar.payment', compact('calonSantri', 'riwayat'));
}

public function storePayment(Request $request, $id)
{
    $request->validate([
        'nominal' => 'required|numeric|min:1000',
        'keterangan' => 'nullable|string'
    ]);

    $calonSantri = CalonSantri::findOrFail($id);

    if ($request->nominal > $calonSantri->sisa_tagihan) {
        return back()->with('error', 'Nominal pembayaran melebihi sisa tagihan!');
    }

    DB::beginTransaction();
    try {
        // 1. Buat Transaksi (Tipe: Manual/Cash)
        $transaksi = PpdbTransaction::create([
            'calon_santri_id' => $calonSantri->id,
            'order_id' => 'MANUAL-' . time() . '-' . auth()->id(), // Kode unik manual
            'gross_amount' => $request->nominal,
            'biaya_admin' => 0, // Cash biasanya tanpa admin fee
            'payment_type' => 'cash',
            'payment_code' => '-', // Tidak ada VA
            'status' => 'paid', // Langsung lunas karena cash
            // Simpan ID admin yang input (Opsional jika tabel support)
            // 'user_id' => auth()->id() 
        ]);

        // 2. Update Data Santri
        $calonSantri->total_sudah_bayar += $request->nominal;
        
        // Cek Lunas
        if ($calonSantri->sisa_tagihan <= 0) {
            $calonSantri->status_pembayaran = 'lunas';
        } else {
            $calonSantri->status_pembayaran = 'sebagian';
        }
        
        $calonSantri->save();

        DB::commit();

        // Redirect ke cetak struk atau kembali dengan pesan sukses
        return redirect()->route('adminpondok.ppdb.pendaftar.payment', $id)
            ->with('success', 'Pembayaran tunai berhasil dicatat.')
            ->with('print_url', route('adminpondok.ppdb.pendaftar.payment.print', $transaksi->id));

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

public function printReceipt($transactionId)
{
    $transaksi = PpdbTransaction::with('calonSantri')->findOrFail($transactionId);
    return view('adminpondok.ppdb.pendaftar.struk', compact('transaksi'));
}
}