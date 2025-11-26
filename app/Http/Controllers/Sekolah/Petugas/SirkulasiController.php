<?php

namespace App\Http\Controllers\Sekolah\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perpus\Peminjaman;
use App\Models\Perpus\Buku;
use App\Models\Santri;
use App\Models\Perpus\Setting; // Pastikan model Setting Perpus ada
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SirkulasiController extends Controller
{
    /**
     * Helper: Mendapatkan ID Pondok dari user petugas yang login.
     */
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Halaman Utama Sirkulasi (List Peminjaman Aktif & Scan Pengembalian).
     */
    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();

        // LOGIKA BARU: Jika ada input 'scan_kode_buku' (Scan Pengembalian)
        if ($request->has('scan_kode_buku') && $request->scan_kode_buku != '') {
            return $this->processScanReturn($request->scan_kode_buku);
        }

        // Query Data Peminjaman Aktif (Status: dipinjam)
        $peminjaman = Peminjaman::where('pondok_id', $pondokId)
                        ->where('status', 'dipinjam')
                        ->with(['buku', 'santri']) // Eager load relasi
                        ->latest()
                        ->paginate(10);

        return view('sekolah.petugas.sirkulasi.index', compact('peminjaman'));
    }

    /**
     * Memproses Peminjaman Baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'identitas_peminjam' => 'required|exists:santris,nis',
            'kode_buku'          => 'required|exists:perpus_bukus,kode_buku',
            'durasi'             => 'required|integer|min:1',
        ], [
            'identitas_peminjam.exists' => 'NIS Santri tidak ditemukan di database!',
            'kode_buku.exists'          => 'Kode buku tidak valid/tidak ditemukan.',
        ]);

        $pondokId = $this->getPondokId();

        DB::beginTransaction();
        try {
            // 2. Cari Data Buku (Filter by Pondok)
            $buku = Buku::where('pondok_id', $pondokId)
                        ->where('kode_buku', $request->kode_buku)
                        ->first();
            
            if (!$buku) {
                return back()->with('error', 'Buku tidak ditemukan di perpustakaan ini.');
            }

            if ($buku->stok_tersedia < 1) {
                return back()->with('error', 'Stok buku "'. $buku->judul .'" sedang habis!');
            }

            // 3. Cari Data Santri (Filter by Pondok)
            $santri = Santri::where('pondok_id', $pondokId)
                            ->where('nis', $request->identitas_peminjam)
                            ->firstOrFail();

            // 4. Cek Duplikasi Peminjaman (Santri tidak boleh pinjam buku yang sama double)
            $sedangPinjam = Peminjaman::where('pondok_id', $pondokId)
                                ->where('buku_id', $buku->id)
                                ->where('santri_id', $santri->id)
                                ->where('status', 'dipinjam')
                                ->exists();

            if ($sedangPinjam) {
                return back()->with('error', 'Santri ini masih meminjam buku yang sama!');
            }

            // 5. Simpan Transaksi
            Peminjaman::create([
                'kode_transaksi'    => 'PIN-' . now()->format('ymdHis') . rand(100,999),
                'pondok_id'         => $pondokId, // PENTING: ID Pondok harus masuk
                'buku_id'           => $buku->id,
                'santri_id'         => $santri->id,
                'tgl_pinjam'        => Carbon::now(),
                // FIX: Casting (int) agar Carbon tidak error
                'tgl_wajib_kembali' => Carbon::now()->addDays((int) $request->durasi),
                'status'            => 'dipinjam',
                'petugas_pinjam'    => auth()->id(),
            ]);

            // 6. Kurangi Stok Tersedia
            $buku->decrement('stok_tersedia');

            DB::commit();
            
            return back()->with('success', "Berhasil: {$santri->full_name} meminjam buku {$buku->judul}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses peminjaman: ' . $e->getMessage());
        }
    }

    // --- FITUR PENGEMBALIAN (BARU) ---

    /**
     * Helper: Proses Scan Barcode untuk Pengembalian.
     * Mencari transaksi aktif berdasarkan kode buku.
     */
    private function processScanReturn($kodeBuku)
    {
        $pondokId = $this->getPondokId();

        // 1. Cari Buku
        $buku = Buku::where('pondok_id', $pondokId)->where('kode_buku', $kodeBuku)->first();

        if (!$buku) {
            return back()->with('error', 'Buku dengan kode tersebut tidak ditemukan.');
        }

        // 2. Cari Transaksi 'dipinjam' untuk buku ini
        // Kita asumsikan satu buku fisik (kode unik) hanya bisa dipinjam satu orang pada satu waktu.
        $transaksi = Peminjaman::where('pondok_id', $pondokId)
            ->where('buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->first();

        if (!$transaksi) {
            return back()->with('error', 'Buku ini tercatat di sistem, tapi statusnya sedang TIDAK DIPINJAM (tersedia di rak).');
        }

        // 3. Redirect ke Form Pengembalian untuk konfirmasi
        return redirect()->route('sekolah.petugas.sirkulasi.kembali.form', $transaksi->id);
    }

    /**
     * Menampilkan Form Konfirmasi Pengembalian.
     * Menghitung denda keterlambatan secara otomatis.
     */
    public function returnForm($id)
    {
        $pondokId = $this->getPondokId();
        
        // Cari peminjaman dan load relasi buku & santri
        $peminjaman = Peminjaman::where('pondok_id', $pondokId)
                        ->with(['buku', 'santri'])
                        ->findOrFail($id);

        // Hitung Keterlambatan
        $today = Carbon::now();
        $wajibKembali = Carbon::parse($peminjaman->tgl_wajib_kembali);
        
        $terlambat = 0;
        $estimasiDenda = 0;

        if ($today->gt($wajibKembali)) {
            $terlambat = $today->diffInDays($wajibKembali);
            
            // Ambil tarif denda dari Setting (jika ada), default 1000
            $setting = Setting::where('pondok_id', $pondokId)->first();
            $tarifDenda = $setting ? $setting->denda_per_hari : 1000; 
            
            $estimasiDenda = $terlambat * $tarifDenda;
        }

        return view('sekolah.petugas.sirkulasi.return-form', compact('peminjaman', 'terlambat', 'estimasiDenda'));
    }

    /**
     * Proses Simpan Pengembalian (Update status, stok, dan denda).
     */
    public function returnProcess(Request $request, $id)
    {
        $request->validate([
            'kondisi_kembali'     => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'denda_keterlambatan' => 'nullable|numeric|min:0',
            'denda_kerusakan'     => 'nullable|numeric|min:0',
            'catatan'             => 'nullable|string',
        ]);

        $pondokId = $this->getPondokId();
        $peminjaman = Peminjaman::where('pondok_id', $pondokId)->findOrFail($id);

        DB::beginTransaction();
        try {
            $kondisi = $request->kondisi_kembali;
            
            // Tentukan status akhir transaksi
            // Jika buku hilang, statusnya 'hilang'. Jika ada fisiknya (baik/rusak), status 'kembali'.
            $statusAkhir = ($kondisi == 'hilang') ? 'hilang' : 'kembali';

            // 1. Update Data Peminjaman
            $peminjaman->update([
                'tgl_kembali_real'    => Carbon::now(),
                'status'              => $statusAkhir,
                'kondisi_kembali'     => $kondisi,
                'denda_keterlambatan' => $request->denda_keterlambatan ?? 0,
                'denda_kerusakan'     => $request->denda_kerusakan ?? 0,
                'petugas_kembali'     => auth()->id(),
                'catatan'             => $request->catatan
            ]);

            // 2. Update Stok Buku
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
            
            return redirect()->route('sekolah.petugas.sirkulasi.index')
                             ->with('success', 'Buku berhasil dikembalikan. Transaksi selesai.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}