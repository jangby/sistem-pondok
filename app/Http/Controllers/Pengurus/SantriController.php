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
use Maatwebsite\Excel\Validators\ValidationException;

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
        'nik'  => 'nullable|numeric|digits:16|unique:santris,nik', // Wajib angka, 16 digit, unik
        'no_kk'=> 'nullable|numeric|digits:16',
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
    // 1. Cek Validasi Kepemilikan Data
    if ($santri->pondok_id != $this->getPondokId()) abort(404);
    $pondokId = $this->getPondokId();

    // 2. Validasi Data (Dibuat Nullable agar tidak error validasi)
    $request->validate([
        'full_name'     => 'required|string|max:255',
        'nis'           => 'required|numeric|unique:santris,nis,' . $santri->id,
        'nik'           => 'nullable|numeric|unique:santris,nik,' . $santri->id,
        'status'        => 'required',
        // Validasi relasi (tidak wajib diisi)
        'nama_ayah'     => 'nullable|string', 
        'no_hp_ayah'    => 'nullable|string',
        'alamat'        => 'nullable|string',
    ]);

    // 3. MULAI TRANSAKSI DATABASE (Agar data konsisten)
    \DB::beginTransaction();
    try {
        // --- A. UPDATE DATA SANTRI ---
        // Kita hanya ambil data yang benar-benar ada di tabel 'santris' Anda saat ini
        $santri->update([
            'full_name'         => $request->full_name,
            'nis'               => $request->nis,
            'nik'               => $request->nik,
            'no_kk'             => $request->no_kk, // Pastikan kolom ini ada, jika tidak hapus baris ini
            'jenis_kelamin'     => $request->jenis_kelamin,
            'tempat_lahir'      => $request->tempat_lahir,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'golongan_darah'    => $request->golongan_darah,
            'riwayat_penyakit'  => $request->riwayat_penyakit,
            'anak_ke'           => $request->anak_ke,
            'jumlah_saudara'    => $request->jumlah_saudara,
            'tahun_masuk'       => $request->tahun_masuk,
            'status'            => $request->status,
            'kelas_id'          => $request->kelas_id,
            'asrama_id'         => $request->asrama_id,
        ]);

        // --- B. UPDATE DATA ORANG TUA (WALI) ---
        // Logika: Input "Nama Ayah" di form akan kita simpan ke tabel 'orang_tuas'
        // karena di database Anda belum ada kolom 'nama_ayah' di tabel santri.
        
        $dataOrangTua = [
            'name'      => $request->nama_ayah ?? $request->nama_ibu ?? $santri->orangTua->name ?? 'Wali Santri',
            'phone'     => $request->no_hp_ayah ?? $request->no_hp_ibu,
            'address'   => $request->alamat, // Alamat disimpan di tabel orang_tuas
            'pekerjaan' => $request->pekerjaan_ayah ?? $request->pekerjaan_ibu,
            'nik'       => $request->nik_ayah ?? $request->nik_ibu,
        ];

        // Cek apakah santri sudah punya data orang tua?
        if ($santri->orang_tua_id && $santri->orangTua) {
            // Jika ada, UPDATE data yang ada
            $santri->orangTua->update($dataOrangTua);
        } else {
            // Jika belum ada, BUAT BARU
            $dataOrangTua['pondok_id'] = $pondokId;
            $orangTuaBaru = OrangTua::create($dataOrangTua);
            
            // Hubungkan santri ke orang tua baru
            $santri->update(['orang_tua_id' => $orangTuaBaru->id]);
        }

        \DB::commit(); // Simpan perubahan permanen
        return redirect()->route('pengurus.santri.show', $santri->id)
            ->with('success', 'Data santri & wali berhasil diperbarui.');

    } catch (\Exception $e) {
        \DB::rollback(); // Batalkan jika ada error
        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
    }
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

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        // Proses Import
        Excel::import(new SantriImport, $request->file('file'));
        
        return redirect()->back()->with('success', 'Alhamdulillah, data santri berhasil diimport!');

    } catch (ValidationException $e) {
        // MENANGKAP ERROR VALIDASI EXCEL
        $failures = $e->failures();
        $errorMessages = [];
        
        foreach ($failures as $failure) {
            $baris = $failure->row();
            $kolom = $failure->attribute(); // Nama kolom yang error
            $pesan = implode(', ', $failure->errors());
            $errorMessages[] = "Baris {$baris} ({$kolom}): {$pesan}";
        }

        // Tampilkan error ke user (batasi 5 error pertama agar tidak kepanjangan)
        $errorString = implode('<br>', array_slice($errorMessages, 0, 5));
        if (count($errorMessages) > 5) $errorString .= '<br>...dan kesalahan lainnya.';

        return redirect()->back()->with('error', 'Gagal Import Data:<br>' . $errorString);

    } catch (\Exception $e) {
        // ERROR UMUM LAINNYA
        return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
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
}