<?php

namespace App\Http\Controllers\UangJajan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WarungPayout;
use Illuminate\Support\Facades\Storage;

class WarungPayoutController extends Controller
{
    public function index()
    {
        $payouts = WarungPayout::with('warung')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->latest()
            ->paginate(15);

        return view('uuj-admin.payout.index', compact('payouts'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|max:2048',
            'catatan_admin' => 'nullable|string',
        ]);

        $payout = WarungPayout::findOrFail($id);

        if ($payout->status != 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        // PERBAIKAN: Simpan ke folder 'uploads/bukti-payout' di disk 'public'
        // Hasil $path akan menjadi: "uploads/bukti-payout/namafileacak.jpg"
        $path = $request->file('bukti_transfer')->store('uploads/bukti-payout', 'public');

        $payout->update([
            'status' => 'approved',
            // Simpan path murni saja, biar fleksibel saat dipanggil
            'bukti_transfer' => $path, 
            'catatan_admin' => $request->catatan_admin,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Penarikan disetujui dan bukti tersimpan.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string',
        ]);

        $payout = WarungPayout::findOrFail($id);

        if ($payout->status != 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $payout->update([
            'status' => 'rejected',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Penarikan ditolak. Saldo dikembalikan.');
    }
}