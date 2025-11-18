<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UujPayout;

class UujPayoutController extends Controller
{
    public function index()
    {
        $payouts = UujPayout::with('pondok')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->latest()
            ->paginate(15);

        return view('superadmin.uuj-payouts.index', compact('payouts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'bukti_transfer' => 'required_if:action,approve|image|max:2048',
            'catatan' => 'nullable|string',
        ]);

        $payout = UujPayout::findOrFail($id);

        if ($request->action == 'approve') {
            $path = $request->file('bukti_transfer')->store('uploads/bukti-payout-uuj', 'public');
            
            $payout->update([
                'status' => 'approved',
                'bukti_transfer' => $path,
                'catatan_admin' => $request->catatan,
                'approved_at' => now(),
            ]);
            
            return back()->with('success', 'Permintaan disetujui.');
        } else {
            $payout->update([
                'status' => 'rejected',
                'catatan_admin' => $request->catatan,
            ]);
            
            return back()->with('success', 'Permintaan ditolak.');
        }
    }
}