<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\OrangTua;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Exports\Pengurus\SantriTemplateExport;
use App\Imports\Pengurus\SantriImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Pengurus\SantriExport; // <--- Tambahkan ini
use Illuminate\Support\Facades\Http;

class SantriController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();

        // --- TAMBAHAN: Ambil data kelas untuk filter di modal ---
        $kelas_list = Kelas::where('pondok_id', $pondokId)->orderBy('nama_kelas')->get();
        // -------------------------------------------------------

        $query = Santri::where('pondok_id', $pondokId)
            ->with(['kelas', 'orangTua'])
            ->latest();

        // Fitur Pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $santris = $query->paginate(15)->withQueryString();

        // Jangan lupa passing 'kelas_list' ke view
        return view('pengurus.santri.index', compact('santris', 'kelas_list'));
    }

    public function create()
    {
        $pondokId = $this->getPondokId();
        $orangTuas = OrangTua::where('pondok_id', $pondokId)->orderBy('name')->get();
        $kelas = Kelas::where('pondok_id', $pondokId)->orderBy('nama_kelas')->get();

        return view('pengurus.santri.create', compact('orangTuas', 'kelas'));
    }

    // File: app/Http/Controllers/Pengurus/SantriController.php

public function store(Request $request)
{
    $pondokId = $this->getPondokId();

    // 1. VALIDASI
    $validated = $request->validate([
        'nis' => ['nullable', Rule::unique('santris')->where('pondok_id', $pondokId)],
        'tahun_masuk' => 'required|digits:4|integer|min:2000|max:'.(date('Y')+1),
        'rfid_uid' => ['nullable', 'string', Rule::unique('santris')->where('pondok_id', $pondokId)], 
        'full_name' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'tempat_lahir' => 'nullable|string',
        'tanggal_lahir' => 'nullable|date',
        'golongan_darah' => 'nullable|in:A,B,AB,O',
        'riwayat_penyakit' => 'nullable|string',
        'kelas_id' => 'nullable|exists:kelas,id',
        
        // --- PERUBAHAN DISINI: Jadi nullable (boleh kosong) ---
        'orang_tua_id' => 'nullable|exists:orang_tuas,id', 
        
        'status' => 'required|in:active,graduated,moved',
    ]);

    $validated['pondok_id'] = $pondokId;
    
    // 2. LOGIKA AUTO GENERATE NIS JIKA KOSONG
    if (empty($validated['nis'])) {
        $tahun = $validated['tahun_masuk'];
        
        $lastSantri = Santri::where('pondok_id', $pondokId)
            ->where('nis', 'like', $tahun . '%')
            ->orderByRaw('LENGTH(nis) DESC') 
            ->orderBy('nis', 'desc')
            ->first();

        if ($lastSantri) {
            $lastNo = intval(substr($lastSantri->nis, 4));
            $newNo = $lastNo + 1;
        } else {
            $newNo = 1;
        }

        $validated['nis'] = $tahun . str_pad($newNo, 4, '0', STR_PAD_LEFT);
    }
    
    // Generate Token QR Code
    $validated['qrcode_token'] = 'S-' . time() . '-' . Str::random(8);

    Santri::create($validated);

    return redirect()->route('pengurus.santri.index')
        ->with('success', 'Data santri berhasil ditambahkan' . (empty($request->nis) ? ' (NIS Otomatis: '.$validated['nis'].')' : '.'));
}

public function update(Request $request, Santri $santri)
{
    if ($santri->pondok_id != $this->getPondokId()) abort(404);
    $pondokId = $this->getPondokId();

    $validated = $request->validate([
        'nis' => ['nullable', Rule::unique('santris')->where('pondok_id', $pondokId)->ignore($santri->id)],
        'tahun_masuk' => 'required|digits:4|integer|min:2000|max:'.(date('Y')+1),
        'rfid_uid' => ['nullable', 'string', Rule::unique('santris')->where('pondok_id', $pondokId)->ignore($santri->id)],
        'full_name' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'tempat_lahir' => 'nullable|string',
        'tanggal_lahir' => 'nullable|date',
        'golongan_darah' => 'nullable|in:A,B,AB,O',
        'riwayat_penyakit' => 'nullable|string',
        'kelas_id' => 'nullable|exists:kelas,id',
        
        // --- PERUBAHAN DISINI: Jadi nullable juga saat edit ---
        'orang_tua_id' => 'nullable|exists:orang_tuas,id',
        
        'status' => 'required|in:active,graduated,moved',
        
        // Validasi Data Pelengkap
        'alamat' => 'nullable|string',
        'rt' => 'nullable|string|max:5',
        'rw' => 'nullable|string|max:5',
        'desa' => 'nullable|string',
        'kecamatan' => 'nullable|string',
        'kode_pos' => 'nullable|string|max:10',
        'nama_ayah' => 'nullable|string',
        'thn_lahir_ayah' => 'nullable|digits:4',
        'pendidikan_ayah' => 'nullable|string',
        'pekerjaan_ayah' => 'nullable|string',
        'penghasilan_ayah' => 'nullable|string',
        'nik_ayah' => 'nullable|string|max:20',
        'nama_ibu' => 'nullable|string',
        'thn_lahir_ibu' => 'nullable|digits:4',
        'pendidikan_ibu' => 'nullable|string',
        'pekerjaan_ibu' => 'nullable|string',
        'penghasilan_ibu' => 'nullable|string',
        'nik_ibu' => 'nullable|string|max:20',
    ]);
    
    // Logika NIS otomatis jika dikosongkan saat edit (opsional, biasanya jarang berubah)
    if (empty($validated['nis'])) {
        // Gunakan NIS lama jika user mengosongkan field NIS saat edit
        $validated['nis'] = $santri->nis; 
    }

    $santri->update($validated);

    return redirect()->route('pengurus.santri.index')->with('success', 'Data santri berhasil diperbarui.');
}

    public function show(Santri $santri)
    {
        if ($santri->pondok_id != $this->getPondokId()) abort(404);
        return view('pengurus.santri.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        if ($santri->pondok_id != $this->getPondokId()) abort(404);

        $pondokId = $this->getPondokId();
        $orangTuas = OrangTua::where('pondok_id', $pondokId)->orderBy('name')->get();
        $kelas = Kelas::where('pondok_id', $pondokId)->orderBy('nama_kelas')->get();

        return view('pengurus.santri.edit', compact('santri', 'orangTuas', 'kelas'));
    }

    /**
     * Generate ulang QR Code jika kartu hilang/rusak.
     */
    public function regenerateQR(Santri $santri)
    {
        if ($santri->pondok_id != $this->getPondokId()) abort(404);

        // Buat token baru
        $newToken = 'S-' . time() . '-' . Str::random(8);
        
        $santri->update(['qrcode_token' => $newToken]);

        return back()->with('success', 'QR Code berhasil digenerate ulang.');
    }

    /**
     * Download Template Excel
     */
    public function downloadTemplate()
    {
        return Excel::download(new SantriTemplateExport, 'template_import_santri.xlsx');
    }

    /**
     * Proses Impor Data
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new SantriImport, $request->file('file'));
            return redirect()->route('pengurus.santri.index')->with('success', 'Data Santri berhasil diimpor!');
        } catch (\Exception $e) {
            // Tangkap error jika format tanggal salah atau validasi gagal
            return redirect()->back()->with('error', 'Gagal impor: ' . $e->getMessage());
        }
    }

    /**
     * Fitur Darurat: Hapus data santri yang tidak punya kelas (akibat gagal import)
     * Hanya menghapus data yang dibuat HARI INI agar aman.
     */
    public function cleanupFailedImport()
    {
        $pondokId = $this->getPondokId();
        
        // Hitung dulu berapa yang akan dihapus (untuk pesan sukses)
        $count = Santri::where('pondok_id', $pondokId)
            ->whereNull('kelas_id') // Hanya yang tidak punya kelas
            ->whereDate('created_at', \Carbon\Carbon::today()) // Hanya yang dibuat HARI INI
            ->count();

        if ($count == 0) {
            return redirect()->back()->with('error', 'Tidak ditemukan data santri tanpa kelas yang dibuat hari ini.');
        }

        // Lakukan penghapusan
        Santri::where('pondok_id', $pondokId)
            ->whereNull('kelas_id')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->delete();

        return redirect()->back()->with('success', "Berhasil menghapus {$count} data santri yang tidak memiliki kelas.");
    }

    public function export(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // Ambil filter dari request modal
        $filters = [
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,
            'status' => $request->status,
        ];

        // Nama file dinamis ada tanggalnya
        $fileName = 'Data_Santri_' . date('d-m-Y_H-i') . '.xlsx';

        return Excel::download(new SantriExport($pondokId, $filters), $fileName);
    }

    public function storeFace(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'image'     => 'required' // Base64 string dari webcam
        ]);

        $santri = Santri::findOrFail($request->santri_id);

        // 1. Decode Image Base64 ke File Temporary
        try {
            $image_parts = explode(";base64,", $request->image);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'face_reg_' . time() . '.jpg';
            $filePath = sys_get_temp_dir() . '/' . $fileName;
            file_put_contents($filePath, $image_base64);

            // 2. Kirim ke Python Service (Port 5000)
            // Pastikan service python (app.py) sudah jalan
            $response = Http::attach(
                'image', file_get_contents($filePath), $fileName
            )->post('http://127.0.0.1:5000/get-embedding');

            // Hapus file temp agar server bersih
            unlink($filePath);

            $result = $response->json();

            if (isset($result['status']) && $result['status'] == 'success') {
                // 3. Simpan Encoding Wajah ke Database
                $santri->update([
                    'face_embedding' => $result['embedding'] // Ini JSON string dari Python
                ]);

                return response()->json([
                    'status' => 'success', 
                    'message' => 'Wajah berhasil didaftarkan! Sistem siap mengenali santri ini.'
                ]);
            } else {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Gagal mendeteksi wajah. Pastikan wajah terlihat jelas dan pencahayaan cukup.'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }
}