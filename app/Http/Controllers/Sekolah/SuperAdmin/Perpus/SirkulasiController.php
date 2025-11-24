<?php

namespace App\Http\Controllers\Sekolah\SuperAdmin\Perpus;

use App\Http\Controllers\Controller;
use App\Models\Perpus\Buku;
use App\Models\Perpus\Peminjaman;
use App\Models\Perpus\Setting;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SirkulasiController extends Controller
{
    /**
     * Helper untuk mendapatkan ID Pondok dari user yang sedang login.
     */
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Menampilkan daftar peminjaman yang sedang aktif (belum dikembalikan).
     */
    public function index(Request $request)
    {
        $query = Peminjaman::where('pondok_id', $this->getPondokId())
            ->where('status', 'dipinjam') // Hanya ambil yang statusnya 'dipinjam'
            ->with(['santri', 'buku']);

        // Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('santri', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('buku', function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('kode_buku', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan tanggal wajib kembali terdekat (agar yang mau telat muncul duluan)
        $peminjamans = $query->orderBy('tgl_wajib_kembali', 'asc')->paginate(10);
        
        return view('sekolah.superadmin.perpus.sirkulasi.index', compact('peminjamans'));
    }

    /**
     * Menampilkan form untuk transaksi peminjaman baru.
     */
    public function create()
    {
        // Ambil setting perpustakaan untuk menentukan tanggal otomatis kembali
        $setting = Setting::getRules($this->getPondokId());
        $defaultKembali = Carbon::now()->addDays($setting->batas_hari_pinjam)->format('Y-m-d');
        
        return view('sekolah.superadmin.perpus.sirkulasi.create', compact('defaultKembali'));
    }

    /**
     * Memproses penyimpanan data peminjaman baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_santri' => 'required|string', // Bisa NIS, QR Token, atau RFID UID
            'kode_buku' => 'required|string|exists:perpus_bukus,kode_buku',
            'tgl_wajib_kembali' => 'required|date|after_or_equal:today',
        ]);

        $pondokId = $this->getPondokId();

        // 1. Cari Data Santri
        // Menggunakan nama kolom yang sesuai dengan migrasi: qrcode_token dan rfid_uid
        $santri = Santri::where('pondok_id', $pondokId)
            ->where(function($q) use ($request) {
                $q->where('nis', $request->kode_santri)
                  ->orWhere('qrcode_token', $request->kode_santri) 
                  ->orWhere('rfid_uid', $request->kode_santri);
            })->first();

        if (!$santri) {
            return back()->with('error', 'Data Santri tidak ditemukan. Silakan cek kembali kartu.');
        }

        // 2. Cari Data Buku
        $buku = Buku::where('pondok_id', $pondokId)
            ->where('kode_buku', $request->kode_buku)
            ->first();

        // 3. Validasi Stok Buku
        if ($buku->stok_tersedia < 1) {
            return back()->with('error', "Stok buku '{$buku->judul}' sedang kosong / habis dipinjam.");
        }

        // 4. Validasi Duplikasi Peminjaman
        // Cek apakah santri ini sedang meminjam buku yang SAMA dan belum dikembalikan?
        $isDuplicate = Peminjaman::where('santri_id', $santri->id)
            ->where('buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($isDuplicate) {
            return back()->with('error', "Santri atas nama {$santri->name} masih meminjam buku ini dan belum mengembalikannya.");
        }

        DB::beginTransaction();
        try {
            // Simpan Transaksi Peminjaman
            Peminjaman::create([
                'kode_transaksi' => 'PIN-' . date('ymd') . rand(1000,9999),
                'pondok_id' => $pondokId,
                'santri_id' => $santri->id,
                'buku_id' => $buku->id,
                'petugas_pinjam' => Auth::id(),
                'tgl_pinjam' => Carbon::now(),
                'tgl_wajib_kembali' => $request->tgl_wajib_kembali,
                'status' => 'dipinjam',
            ]);

            // Kurangi Stok Tersedia Buku
            $buku->decrement('stok_tersedia');

            DB::commit();
            return redirect()->route('sekolah.superadmin.perpustakaan.sirkulasi.index')
                             ->with('success', "Peminjaman berhasil dicatat untuk {$santri->name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Scan Pengembalian (Cari Transaksi berdasarkan Barcode Buku).
     */
    public function returnIndex(Request $request)
    {
        $peminjaman = null;

        // Jika admin sudah scan barcode buku
        if ($request->has('kode_buku') && $request->kode_buku != '') {
            $pondokId = $this->getPondokId();
            
            // Cari buku berdasarkan barcode
            $buku = Buku::where('pondok_id', $pondokId)
                ->where('kode_buku', $request->kode_buku)
                ->first();

            if ($buku) {
                // Cari transaksi 'dipinjam' yang terkait dengan buku ini
                $peminjaman = Peminjaman::where('buku_id', $buku->id)
                    ->where('status', 'dipinjam')
                    ->with('santri')
                    ->first();
                
                if (!$peminjaman) {
                    session()->flash('error', 'Buku ini ditemukan, tetapi tidak ada data sedang dipinjam (Mungkin sudah dikembalikan atau stok rak).');
                }
            } else {
                session()->flash('error', 'Data buku tidak ditemukan di sistem.');
            }
        }

        return view('sekolah.superadmin.perpus.sirkulasi.return-scan', compact('peminjaman'));
    }

    /**
     * Form Konfirmasi Pengembalian (Menampilkan Denda jika ada).
     */
    public function returnForm(Peminjaman $peminjaman)
    {
        // Hitung Keterlambatan
        $today = Carbon::now();
        $wajibKembali = Carbon::parse($peminjaman->tgl_wajib_kembali);
        
        $terlambatHari = 0;
        $dendaKeterlambatan = 0;

        // Jika hari ini lebih besar dari tgl wajib kembali
        if ($today->gt($wajibKembali)) {
            $terlambatHari = $today->diffInDays($wajibKembali);
            
            // Ambil setting tarif denda
            $rules = Setting::getRules($this->getPondokId());
            $dendaKeterlambatan = $terlambatHari * $rules->denda_per_hari;
        }

        return view('sekolah.superadmin.perpus.sirkulasi.return-form', compact('peminjaman', 'terlambatHari', 'dendaKeterlambatan'));
    }

    /**
     * Proses Simpan Pengembalian (Update status, stok, dan denda).
     */
    public function returnProcess(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'kondisi_kembali' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'denda_kerusakan' => 'nullable|numeric|min:0',
            'denda_keterlambatan' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $kondisi = $request->kondisi_kembali;
            
            // Tentukan status akhir transaksi
            // Jika buku hilang, statusnya 'hilang'. Jika ada fisiknya (baik/rusak), status 'kembali'.
            $statusAkhir = ($kondisi == 'hilang') ? 'hilang' : 'kembali';

            // Update data peminjaman
            $peminjaman->update([
                'tgl_kembali_real' => Carbon::now(),
                'status' => $statusAkhir,
                'kondisi_kembali' => $kondisi,
                'denda_keterlambatan' => $request->denda_keterlambatan,
                'denda_kerusakan' => $request->denda_kerusakan ?? 0,
                'petugas_kembali' => Auth::id(),
                'catatan' => $request->catatan,
            ]);

            // Logic Update Stok Buku
            if ($statusAkhir == 'kembali') {
                // Jika buku dikembalikan (kondisi baik/rusak), kembalikan stok tersedia ke rak
                $peminjaman->buku->increment('stok_tersedia');
            } else {
                // Jika buku HILANG:
                // Stok tersedia TIDAK ditambah (karena memang tidak kembali).
                // TAPI, Stok TOTAL harus dikurangi 1 karena aset berkurang permanen.
                $peminjaman->buku->decrement('stok_total');
            }

            DB::commit();
            return redirect()->route('sekolah.superadmin.perpustakaan.sirkulasi.index')
                             ->with('success', 'Proses pengembalian buku berhasil diselesaikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}