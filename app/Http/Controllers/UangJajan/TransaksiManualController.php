<?php

namespace App\Http\Controllers\UangJajan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dompet;
use App\Models\Santri;
use App\Services\DompetService; // <-- IMPORT "OTAK" KITA
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TransaksiManualController extends Controller
{
    protected $dompetService;

    // Otomatis 'inject' DompetService
    public function __construct(DompetService $dompetService)
    {
        $this->dompetService = $dompetService;
    }

    // --- LOGIKA TOP-UP ---

    public function createTopup()
    {
        return view('uuj-admin.transaksi.topup');
    }

    public function storeTopup(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'nominal' => 'required|numeric|min:1000',
            'keterangan' => 'required|string|max:100',
        ]);

        $santri = Santri::find($validated['santri_id']);
        $dompet = $santri->dompet;

        // Cek jika santri punya dompet
        if (!$dompet) {
            return redirect()->back()->with('error', 'Santri ini belum memiliki dompet aktif.')->withInput();
        }

        // Panggil "Otak" kita
        $result = $this->dompetService->createTransaksi(
            $dompet,
            (float) $validated['nominal'],
            'topup_manual',
            $validated['keterangan'],
            Auth::user() // Admin yg mencatat
        );

        if ($result === true) {
            return redirect()->route('uuj-admin.dompet.index')
                             ->with('success', 'Top-up saldo berhasil. Saldo baru: Rp ' . number_format($dompet->fresh()->saldo));
        } else {
            return redirect()->back()->with('error', 'Gagal top-up: ' . $result)->withInput();
        }
    }

    // --- LOGIKA TARIK TUNAI ---

    public function createTarik()
    {
        return view('uuj-admin.transaksi.tarik');
    }

    /**
     * API: Cari Santri by Barcode (Untuk Tarik Tunai)
     */
    public function findSantri(Request $request)
    {
        $request->validate(['barcode' => 'required|string']);

        // Cari dompet berdasarkan barcode token
        // Pastikan scope-nya satu pondok (jika multi-tenant)
        $adminPondokId = Auth::user()->pondokStaff->pondok_id ?? null;

        $dompet = Dompet::where('barcode_token', $request->barcode)
            ->when($adminPondokId, function($q) use ($adminPondokId) {
                $q->where('pondok_id', $adminPondokId);
            })
            ->with(['santri', 'santri.kelas'])
            ->first();

        if (!$dompet) {
            return response()->json(['message' => 'Kartu tidak ditemukan.'], 404);
        }

        if ($dompet->status === 'blocked') {
            return response()->json(['message' => 'Kartu ini sedang DIBLOKIR.'], 403);
        }

        return response()->json([
            'status' => 'success',
            'dompet_id' => $dompet->id,
            'nama_santri' => $dompet->santri->full_name,
            'nis' => $dompet->santri->nis,
            'kelas' => $dompet->santri->kelas->nama_kelas ?? '-',
            'saldo' => $dompet->saldo,
            'foto' => null // Bisa ditambahkan jika ada foto santri
        ]);
    }

    /**
     * Proses Tarik Tunai (Dengan PIN)
     */
    public function storeTarik(Request $request)
    {
        $request->validate([
            'dompet_id' => 'required|exists:dompets,id',
            'nominal' => 'required|numeric|min:1000',
            'pin' => 'required|numeric|digits:6', // Validasi PIN
            'keterangan' => 'required|string|max:255',
        ]);

        $dompet = Dompet::findOrFail($request->dompet_id);

        // 1. Verifikasi PIN
        if (!Hash::check($request->pin, $dompet->pin)) {
            return back()->with('error', 'PIN Salah! Transaksi dibatalkan.');
        }

        // 2. Cek Saldo
        if ($dompet->saldo < $request->nominal) {
            return back()->with('error', 'Saldo tidak mencukupi untuk penarikan ini.');
        }

        // 3. Proses Transaksi
        // Gunakan service yang sama dengan POS agar standar
        $userAdmin = Auth::user();
        
        $result = $this->dompetService->createTransaksi(
            $dompet,
            (float) $request->nominal,
            'tarik_tunai', // Tipe transaksi
            $request->keterangan . ' (Oleh Admin: ' . $userAdmin->name . ')',
            $userAdmin,
            null // Warung ID null karena ini admin
        );

        if ($result === true) {
            return redirect()->route('uuj-admin.tarik.create')
                ->with('success', 'Penarikan Tunai Berhasil! Saldo Sisa: Rp ' . number_format($dompet->fresh()->saldo, 0));
        } else {
            return back()->with('error', 'Gagal memproses transaksi: ' . $result);
        }
    }
}