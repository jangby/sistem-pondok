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

class SantriController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();

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

        return view('pengurus.santri.index', compact('santris'));
    }

    public function create()
    {
        $pondokId = $this->getPondokId();
        $orangTuas = OrangTua::where('pondok_id', $pondokId)->orderBy('name')->get();
        $kelas = Kelas::where('pondok_id', $pondokId)->orderBy('nama_kelas')->get();

        return view('pengurus.santri.create', compact('orangTuas', 'kelas'));
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nis' => ['nullable', Rule::unique('santris')->where('pondok_id', $pondokId)], // <--- Ubah jadi nullable
            'tahun_masuk' => 'nullable|digits:4|integer|min:2000|max:'.(date('Y')+1),
            'rfid_uid' => ['nullable', 'string', Rule::unique('santris')->where('pondok_id', $pondokId)], // Validasi RFID unik
            'full_name' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'riwayat_penyakit' => 'nullable|string',
            'kelas_id' => 'nullable|exists:kelas,id',
            'orang_tua_id' => 'required|exists:orang_tuas,id',
            'status' => 'required|in:active,graduated,moved',
        ]);

        $validated['pondok_id'] = $pondokId;
        
        // 2. LOGIKA AUTO GENERATE NIS
        if (empty($request->nis)) {
            $tahun = $request->tahun_masuk;
            
            // Cari santri terakhir di tahun yang sama & pondok yang sama
            // Kita cari yang format depannya mirip tahun masuk (cth: '2025%')
            $lastSantri = Santri::where('pondok_id', $pondokId)
                ->where('nis', 'like', $tahun . '%')
                // Order by panjang string dulu (biar 2025100 tidak dianggap lebih kecil dari 202599)
                ->orderByRaw('LENGTH(nis) DESC') 
                ->orderBy('nis', 'desc')
                ->first();

            if ($lastSantri) {
                // Ambil 4 digit terakhir, ubah jadi integer, tambah 1
                // Contoh NIS: 20250045 -> ambil 0045 -> jadi 45 -> +1 = 46
                $lastNo = intval(substr($lastSantri->nis, 4));
                $newNo = $lastNo + 1;
            } else {
                $newNo = 1;
            }

            // Format ulang: Tahun + 4 Digit Urut (Pad Left dengan 0)
            // Contoh: 2025 + 0046 = 20250046
            $validated['nis'] = $tahun . str_pad($newNo, 4, '0', STR_PAD_LEFT);
        }
        
        // OTOMATIS GENERATE QR CODE SAAT BUAT BARU
        // Format: SANTRI-TIMESTAMP-RANDOM (agar unik)
        $validated['qrcode_token'] = 'S-' . time() . '-' . Str::random(8);

        Santri::create($validated);

        return redirect()->route('pengurus.santri.index')->with('success', 'Data santri berhasil ditambahkan.');
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

    public function update(Request $request, Santri $santri)
    {
        if ($santri->pondok_id != $this->getPondokId()) abort(404);
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nis' => ['required', Rule::unique('santris')->where('pondok_id', $pondokId)->ignore($santri->id)],
            'tahun_masuk' => 'nullable|digits:4|integer|min:2000|max:'.(date('Y')+1),
            'rfid_uid' => ['nullable', 'string', Rule::unique('santris')->where('pondok_id', $pondokId)->ignore($santri->id)], // Validasi RFID
            'full_name' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'riwayat_penyakit' => 'nullable|string',
            'kelas_id' => 'nullable|exists:kelas,id',
            'orang_tua_id' => 'required|exists:orang_tuas,id',
            'status' => 'required|in:active,graduated,moved',
            // Validasi Tambahan (Semua Nullable)
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

        $santri->update($validated);

        return redirect()->route('pengurus.santri.index')->with('success', 'Data santri berhasil diperbarui.');
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
}