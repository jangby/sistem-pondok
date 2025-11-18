<?php

namespace App\Http\Controllers\UangJajan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UujPayout;
use App\Models\TransaksiDompet;
use Illuminate\Support\Facades\Auth;

class UujPayoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pondokId = $user->pondokStaff->pondok_id;

        // 1. Hitung Pemasukan dari Topup Midtrans (Uang Masuk Real)
        // Hanya menghitung transaksi 'topup_midtrans' yang sukses
        $totalMasukMidtrans = TransaksiDompet::where('tipe', 'topup_midtrans')
            ->whereHas('dompet', function($q) use ($pondokId) {
                $q->where('pondok_id', $pondokId);
            })
            ->sum('nominal'); // Nilai positif

        // 2. Hitung yang sudah ditarik/diajukan
        $totalDitarik = UujPayout::where('pondok_id', $pondokId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('nominal');

        // 3. Saldo Tersedia
        $saldoTersedia = $totalMasukMidtrans - $totalDitarik;

        // 4. Riwayat
        $riwayat = UujPayout::where('pondok_id', $pondokId)
            ->latest()
            ->paginate(10);

        return view('uuj-admin.pencairan.index', compact('saldoTersedia', 'riwayat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:50000',
            'tujuan_transfer' => 'required|string',
        ]);

        $user = Auth::user();
        $pondokId = $user->pondokStaff->pondok_id;

        // Validasi Saldo (Cek ulang manual)
        $totalMasuk = TransaksiDompet::where('tipe', 'topup_midtrans')
            ->whereHas('dompet', fn($q)=>$q->where('pondok_id', $pondokId))
            ->sum('nominal');
        
        $totalKeluar = UujPayout::where('pondok_id', $pondokId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('nominal');

        $saldo = $totalMasuk - $totalKeluar;

        if ($request->nominal > $saldo) {
            return back()->with('error', 'Saldo Midtrans tidak mencukupi.');
        }

        UujPayout::create([
            'pondok_id' => $pondokId,
            'user_id' => $user->id,
            'nominal' => $request->nominal,
            'tujuan_transfer' => $request->tujuan_transfer,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Permintaan pencairan berhasil dikirim ke Super Admin.');
    }
}