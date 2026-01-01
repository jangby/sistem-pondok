<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\PpdbSetting;
use App\Models\User;
use App\Models\CalonSantri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use App\Models\PpdbTransaction;
use Midtrans\Config;
use Midtrans\Snap;
use App\Services\MidtransService; 
use Illuminate\Support\Facades\Log;

class PpdbController extends Controller
{
    public function index()
    {
        $ppdbActive = PpdbSetting::active()->first();
        return view('welcome', compact('ppdbActive'));
    }

    public function register()
    {
        $ppdbActive = PpdbSetting::active()->first();

        if (!$ppdbActive) {
            return redirect()->route('welcome')->with('error', 'Pendaftaran sedang ditutup.');
        }

        return view('auth.register-ppdb', compact('ppdbActive'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', 'string', 'in:SMP,SMA,TAKHOSUS'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $ppdbActive = PpdbSetting::active()->first();
        if (!$ppdbActive) {
            return back()->with('error', 'Maaf, pendaftaran sudah ditutup saat Anda mengisi form.');
        }

        DB::transaction(function () use ($request, $ppdbActive) {
            // 2. Buat User Baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'telepon' => $request->no_hp, // Pastikan kolom 'telepon' ada di tabel users (cek migrasi Anda)
                'password' => Hash::make($request->password),
            ]);

            // 3. Assign Role (PENTING: Pastikan role 'calon_santri' sudah ada di database)
            $user->assignRole('calon_santri');

            // 4. Buat Data Calon Santri (Draft)
            // Generate No Pendaftaran: TAHUN + ID_USER (Contoh: 2025005)
            $noPendaftaran = $ppdbActive->tahun_ajaran . sprintf('%04d', $user->id);
            // Bersihkan tahun ajaran dari karakter non-angka
            $noPendaftaran = preg_replace('/[^0-9]/', '', $noPendaftaran); 

            CalonSantri::create([
                'user_id' => $user->id,
                'jenjang' => $request->jenjang,
                'ppdb_setting_id' => $ppdbActive->id,
                'no_pendaftaran' => $noPendaftaran,
                
                // PENTING: Gunakan 'full_name' bukan 'nama_lengkap'
                'full_name' => $user->name, 
                
                'status_pendaftaran' => 'draft',
                'status_pembayaran' => 'belum_bayar',
                
                // Isi default value untuk kolom NOT NULL lainnya agar tidak error
                'tempat_lahir' => '-',
                'tanggal_lahir' => now(),
                'jenis_kelamin' => 'L',
                'nama_ayah' => '-',
                'nama_ibu' => '-',
                'alamat' => '-', // Default alamat
                'rt' => '00',    // Default RT
                'rw' => '00',    // Default RW
                'desa' => '-',
                'kecamatan' => '-',
                'kabupaten' => '-',
                'provinsi' => '-',
                'kode_pos' => '00000',
            ]);

            Auth::login($user);
        });

        return redirect()->route('dashboard');
    }

    private function getCalonSantriOrRedirect($user)
    {
        $calonSantri = $user->calonSantri;

        if (!$calonSantri) {
            $ppdbActive = PpdbSetting::active()->first();
            
            if ($ppdbActive) {
                $noPendaftaran = preg_replace('/[^0-9]/', '', $ppdbActive->tahun_ajaran) . sprintf('%04d', $user->id);
                
                $calonSantri = CalonSantri::create([
                    'user_id' => $user->id,
                    'ppdb_setting_id' => $ppdbActive->id,
                    'no_pendaftaran' => $noPendaftaran,
                    
                    // PERBAIKAN DISINI JUGA
                    'full_name' => $user->name, 
                    
                    'status_pendaftaran' => 'draft',
                    'status_pembayaran' => 'belum_bayar',
                    // Default values
                    'tempat_lahir' => '-',
                    'tanggal_lahir' => now(),
                    'jenis_kelamin' => 'L',
                    'nama_ayah' => '-',
                    'nama_ibu' => '-',
                    'alamat' => '-',
                    'rt' => '00',
                    'rw' => '00',
                    'desa' => '-',
                    'kecamatan' => '-',
                    'kabupaten' => '-',
                    'provinsi' => '-',
                    'kode_pos' => '00000',
                ]);
            } else {
                return null;
            }
        }
        
        return $calonSantri;
    }

    // Tambahkan method ini di dalam class PpdbController
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ambil data calon santri & setting ppdb terkait
        // Pastikan model User sudah ada relasi 'calonSantri'
        $calonSantri = $user->calonSantri()->with('ppdbSetting')->first();
        
        return view('ppdb.dashboard', compact('calonSantri'));
    }

    // 1. Menampilkan Form Biodata
    public function biodata()
    {
        $user = Auth::user();
        $calonSantri = $user->calonSantri;

        // Cek jika sudah diterima, tidak boleh edit lagi
        if ($calonSantri->status_pendaftaran == 'diterima') {
            return redirect()->route('dashboard')->with('error', 'Data tidak dapat diubah karena sudah diterima.');
        }

        return view('ppdb.biodata', compact('calonSantri'));
    }

    // 2. Menyimpan Perubahan Biodata
    public function updateBiodata(Request $request)
    {
        $user = Auth::user();
        $calonSantri = $user->calonSantri;

        // Validasi
        $request->validate([
            // ... validasi data diri (full_name, nik, dll) biarkan sama ...
            
            // VALIDASI FILE (Semua PDF kecuali Foto)
            'foto_santri'   => 'nullable|image|max:2048', // Foto tetap Image
            'file_kk'       => 'nullable|mimes:pdf|max:2048',
            'file_akta'     => 'nullable|mimes:pdf|max:2048',
            'file_ijazah'   => 'nullable|mimes:pdf|max:2048',
            'file_skl'      => 'nullable|mimes:pdf|max:2048',
            'file_ktp_ayah' => 'nullable|mimes:pdf|max:2048',
            'file_ktp_ibu'  => 'nullable|mimes:pdf|max:2048',
            'file_kip'      => 'nullable|mimes:pdf|max:2048',
        ], [
            'mimes' => 'Format file harus PDF.',
            'max'   => 'Ukuran file maksimal 2MB.',
            'image' => 'File harus berupa gambar (JPG/PNG).',
        ]);

        $data = $request->except(['_token', '_method', 'tab', 'foto_santri', 'file_kk', 'file_akta', 'file_ijazah', 'file_skl', 'file_ktp_ayah', 'file_ktp_ibu', 'file_kip']);

        // Helper function untuk upload agar kodingan tidak berulang
        $uploadFile = function($field, $folder) use ($request, &$data) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store("ppdb/$folder", 'public');
            }
        };

        // Proses Upload
        $uploadFile('foto_santri', 'foto');
        $uploadFile('file_kk', 'berkas');
        $uploadFile('file_akta', 'berkas');
        $uploadFile('file_ijazah', 'berkas');
        $uploadFile('file_skl', 'berkas');
        $uploadFile('file_ktp_ayah', 'berkas');
        $uploadFile('file_ktp_ibu', 'berkas');
        $uploadFile('file_kip', 'berkas');

        $calonSantri->update($data);

        // Update status jika draft
        if ($calonSantri->status_pendaftaran == 'draft') {
            $calonSantri->update(['status_pendaftaran' => 'menunggu_verifikasi']);
        }

        // Redirect kembali ke tab 'berkas'
        return redirect()->route('dashboard')->with('success', 'Berkas berhasil diunggah!');
    }

    // --- FITUR PEMBAYARAN MIDTRANS ---

    // 1. Tampilkan Halaman/Form Bayar (Updated)
    public function payment()
    {
        $user = Auth::user();
        $calonSantri = $user->calonSantri;
        
        // Cek jika belum ada data santri
        if(!$calonSantri) {
             return redirect()->route('dashboard');
        }

        $calonSantri->load('ppdbSetting', 'ppdbSetting.biayas');

        if ($calonSantri->sisa_tagihan <= 0) {
            return redirect()->route('dashboard')->with('success', 'Pembayaran Anda sudah LUNAS.');
        }

        // Kita kirim sisa tagihan ke view untuk perhitungan JS
        return view('ppdb.payment', compact('calonSantri'));
    }

    // 2. Proses Pembayaran dengan Core API (PENGGANTI SNAP)
    public function processPayment(Request $request, MidtransService $midtransService)
    {
        $user = Auth::user();
        $calonSantri = $user->calonSantri;

        // Validasi Nominal & Metode Bayar
        $request->validate([
            'nominal_bayar' => 'required|numeric|min:10000',
            // Validasi metode pembayaran yang diizinkan
            'payment_method' => 'required|in:bca_va,bni_va,mandiri_bill,qris,gopay', 
        ]);

        $nominal = $request->nominal_bayar;
        $biayaAdmin = 5000; // Biaya admin (sesuaikan dengan kebijakan pondok)
        $totalBayar = $nominal + $biayaAdmin;

        if ($nominal > $calonSantri->sisa_tagihan) {
            return back()->with('error', 'Nominal melebihi sisa tagihan.');
        }

        // Generate Order ID Unik
        $orderId = 'PPDB-' . $calonSantri->id . '-' . time();
        $paymentMethod = $request->payment_method;

        // Siapkan Parameter Midtrans (Sama seperti Controller OrangTua)
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalBayar,
            ],
            'customer_details' => [
                'first_name' => $calonSantri->full_name,
                'email' => $user->email,
                'phone' => $user->telepon,
            ],
            'item_details' => [
                [
                    'id' => 'TAGIHAN-PPDB',
                    'price' => $nominal,
                    'quantity' => 1,
                    'name' => 'Pembayaran PPDB',
                ],
                [
                    'id' => 'ADMIN-FEE',
                    'price' => $biayaAdmin,
                    'quantity' => 1,
                    'name' => 'Biaya Admin',
                ]
            ]
        ];

        // Switch Case Metode Pembayaran
        $paymentTypeDb = $paymentMethod; // Default simpan slug metode
        switch ($paymentMethod) {
            case 'bca_va':
                $params['payment_type'] = 'bank_transfer';
                $params['bank_transfer'] = ['bank' => 'bca'];
                break;
            case 'bni_va':
                $params['payment_type'] = 'bank_transfer';
                $params['bank_transfer'] = ['bank' => 'bni'];
                break;
            case 'mandiri_bill':
                $params['payment_type'] = 'echannel';
                $params['echannel'] = [
                    'bill_info1' => 'PEMBAYARAN PPDB',
                    'bill_info2' => 'SANTRI: ' . $calonSantri->full_name,
                ];
                break;
            case 'qris':
                $params['payment_type'] = 'qris';
                break;
            case 'gopay':
                $params['payment_type'] = 'gopay';
                break;
        }

        try {
            // Panggil Service (Core API)
            $response = $midtransService->createTransaction($params);

            if (!$response) {
                return back()->with('error', 'Gagal menghubungi gateway pembayaran.');
            }

            // Ambil Data VA / QR / URL dari respon Midtrans
            $paymentCode = null;
            $paymentUrl = null;

            if (isset($response->va_numbers)) { // BCA / BNI
                $paymentCode = $response->va_numbers[0]->va_number;
            } elseif (isset($response->bill_key)) { // Mandiri
                $paymentCode = $response->biller_code . '|' . $response->bill_key;
            } elseif (isset($response->actions)) {
                // Untuk QRIS / Gopay biasanya ada di actions
                foreach ($response->actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $paymentUrl = $action->url; // URL Gambar QR
                    }
                     if ($action->name === 'deeplink-redirect') {
                        $paymentUrl = $action->url; // Link aplikasi Gojek
                    }
                }
            }

            // Simpan Transaksi
            $transaksi = PpdbTransaction::create([
                'calon_santri_id' => $calonSantri->id,
                'order_id' => $orderId,
                'gross_amount' => $nominal, // Nominal murni tagihan
                'biaya_admin' => $biayaAdmin,
                'payment_type' => $paymentMethod,
                'payment_code' => $paymentCode, // Simpan No VA disini
                'payment_url' => $paymentUrl,   // Simpan URL QRIS disini
                'status' => 'pending',
            ]);

            // Redirect ke Halaman Instruksi
            return redirect()->route('ppdb.payment.instruksi', $transaksi->id);

        } catch (\Exception $e) {
            Log::error('PPDB Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // 3. Halaman Instruksi Pembayaran (Menampilkan VA/QR)
    public function instruksi($id)
    {
        $transaksi = PpdbTransaction::with('calonSantri')->findOrFail($id);
        
        // Cek Hak Akses (Milik user yang login)
        if($transaksi->calonSantri->user_id != Auth::id()){
            abort(403);
        }

        return view('ppdb.instruksi', compact('transaksi'));
    }

    // 3. Callback Setelah Bayar (Finish Redirect)
    public function paymentSuccess(Request $request)
    {
        return redirect()->route('dashboard')->with('success', 'Transaksi sedang diproses. Status akan update otomatis beberapa saat lagi.');
    }
}