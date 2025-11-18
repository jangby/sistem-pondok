<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dompet;
use App\Models\Warung;
use App\Services\DompetService; // <-- "OTAK" KITA
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\WarungPayout;

class PosController extends Controller
{
    protected $dompetService;

    public function __construct(DompetService $dompetService)
    {
        $this->dompetService = $dompetService;
    }

    /**
     * Tampilkan halaman kasir.
     */
    public function index()
    {
        return view('pos.index');
    }

    /**
     * API: Cari santri berdasarkan barcode_token.
     */
    public function findSantri(Request $request)
    {
        $request->validate(['barcode' => 'required|string']);

        $pondokId = Auth::user()->pondokStaff->pondok_id;

        $dompet = Dompet::where('barcode_token', $request->barcode)
                        ->where('pondok_id', $pondokId)
                        ->with('santri')
                        ->first();

        if (!$dompet) {
            return response()->json(['message' => 'Barcode tidak ditemukan.'], 404);
        }
        if ($dompet->status == 'blocked') {
            return response()->json(['message' => 'Kartu/Dompet ini DIBLOKIR.'], 403);
        }

        return response()->json([
            'nama_santri' => $dompet->santri->full_name,
            'saldo' => $dompet->saldo,
            'dompet_id' => $dompet->id, // Kirim ID dompet untuk form
        ]);
    }

    /**
     * Proses Transaksi Jajan.
     */
    public function processTransaction(Request $request, DompetService $dompetService)
    {
        $validated = $request->validate([
            'dompet_id' => 'required|exists:dompets,id',
            'nominal' => 'required|numeric|min:100', // Minimal jajan 100
            'pin' => 'required|numeric|digits:6',
        ]);

        $dompet = Dompet::find($validated['dompet_id']);
        $userWarung = Auth::user();
        $warung = $userWarung->warung; // Asumsi relasi user->warung ada

        // Keamanan: Cek silang kepemilikan
        $pondokId = $userWarung->pondokStaff->pondok_id;
        if ($dompet->pondok_id != $pondokId || $warung->pondok_id != $pondokId) {
            return back()->with('error', 'Kesalahan keamanan: Dompet dan Warung tidak cocok.');
        }

        // Keamanan: Verifikasi PIN Santri
        if (!Hash::check($validated['pin'], $dompet->pin)) {
            return back()->with('error', 'PIN Santri Salah!');
        }

        // Panggil "Otak" kita (DompetService)
        $result = $dompetService->createTransaksi(
            $dompet,
            (float) $validated['nominal'],
            'jajan',
            'Jajan di ' . $warung->nama_warung,
            $userWarung, // User pencatat (si warung)
            $warung
        );

        if ($result === true) {
            return redirect()->route('pos.index')
                             ->with('success', 'Transaksi Berhasil! Saldo baru: Rp ' . number_format($dompet->fresh()->saldo, 0));
        } else {
            return redirect()->route('pos.index')
                             ->with('error', 'Transaksi GAGAL: ' . $result);
        }
    }

    public function dashboard()
    {
        $warung = Auth::user()->warung;
        
        // PERBAIKAN: Gunakan abs() agar nilai negatif (-10000) jadi positif (10000)
        $omsetHariIni = abs($warung->transaksiDompets()
            ->where('tipe', 'jajan')
            ->whereDate('created_at', now())
            ->sum('nominal'));
            
        $transaksiHariIni = $warung->transaksiDompets()
            ->where('tipe', 'jajan')
            ->whereDate('created_at', now())
            ->count();

        return view('pos.dashboard', compact('warung', 'omsetHariIni', 'transaksiHariIni'));
    }

    /**
     * Halaman Riwayat Transaksi Jajan (Dengan Filter)
     */
    public function history(Request $request)
    {
        $warung = Auth::user()->warung;
        
        // 1. Base Query
        $query = $warung->transaksiDompets()
            ->where('tipe', 'jajan')
            ->with('dompet.santri'); // Eager load untuk performa

        // 2. Filter Pencarian (Nama Santri)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('dompet.santri', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // 3. Filter Tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        } else {
            // Default: Tampilkan bulan ini saja agar query ringan
            // (Opsional: Hapus baris ini jika ingin menampilkan semua sejarah)
            // $query->whereMonth('created_at', now()->month);
        }

        // 4. Hitung Ringkasan (Berdasarkan Filter di atas)
        // Kita clone query agar tidak mengganggu pagination
        $summaryQuery = clone $query;
        $totalOmset = abs($summaryQuery->sum('nominal'));
        $totalTransaksi = $summaryQuery->count();

        // 5. Ambil Data (Paginate)
        $transaksis = $query->latest()->paginate(20)->withQueryString();

        return view('pos.history', compact('transaksis', 'totalOmset', 'totalTransaksi'));
    }

    /**
     * Halaman Pengajuan Penarikan
     */
    public function payout()
    {
        $warung = Auth::user()->warung;
        $riwayatPenarikan = $warung->payouts()->latest()->paginate(10);
        
        return view('pos.payout', compact('warung', 'riwayatPenarikan'));
    }

    /**
     * Proses Simpan Pengajuan Penarikan
     */
    public function storePayout(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $warung = Auth::user()->warung;
        $saldoSaatIni = $warung->saldo; // Menggunakan accessor getSaldoAttribute

        if ($request->nominal > $saldoSaatIni) {
            return back()->with('error', 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($saldoSaatIni,0));
        }

        // Cek apakah ada penarikan pending? (Opsional, biar gak spam)
        $adaPending = $warung->payouts()->where('status', 'pending')->exists();
        if($adaPending) {
             return back()->with('error', 'Masih ada permintaan penarikan yang menunggu konfirmasi.');
        }

        \App\Models\WarungPayout::create([
            'warung_id' => $warung->id,
            'nominal' => $request->nominal,
            'status' => 'pending',
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Permintaan penarikan berhasil dikirim.');
    }

    /**
     * Halaman Detail Penarikan (Show)
     */
    public function showPayout($id)
    {
        $warung = Auth::user()->warung;
        
        // Cari data payout, pastikan milik warung yang sedang login (security check)
        $payout = $warung->payouts()->findOrFail($id);

        return view('pos.payout-show', compact('payout'));
    }
}