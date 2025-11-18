<?php

namespace App\Http\Controllers\UangJajan;

use App\Http\Controllers\Controller;
use App\Models\Dompet;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Untuk generate token

class DompetController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Tampilkan daftar santri & status dompet mereka.
     */
    public function index(Request $request)
    {
        $query = Santri::where('pondok_id', $this->getPondokId())
                        ->with('dompet') // Ambil relasi dompet
                        ->where('status', 'active')
                        ->orderBy('full_name');
        
        // Filter Pencarian
        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->search . '%')
              ->orWhere('nis', 'like', '%' . $request->search . '%');
        });

        $santris = $query->paginate(20)->withQueryString();
        
        return view('uuj-admin.dompet.index', compact('santris'));
    }

    /**
     * Tampilkan halaman konfirmasi aktivasi dompet.
     */
    public function activate(Santri $santri)
    {
        // Keamanan
        if ($santri->pondok_id != $this->getPondokId()) abort(404);
        
        return view('uuj-admin.dompet.create', compact('santri'));
    }

    /**
     * Buat (Aktifkan) dompet baru untuk santri.
     */
    public function store(Request $request, Santri $santri)
    {
        // Keamanan
        if ($santri->pondok_id != $this->getPondokId()) abort(404);
        
        // Cek jika sudah punya
        if ($santri->dompet) {
            return redirect()->route('uuj-admin.dompet.index')->with('error', 'Santri ini sudah memiliki dompet aktif.');
        }

        $validated = $request->validate([
            'pin' => 'required|numeric|digits:6|confirmed',
        ]);

        Dompet::create([
            'santri_id' => $santri->id,
            'pondok_id' => $this->getPondokId(),
            'barcode_token' => Str::random(32), // Generate Barcode unik
            'pin' => Hash::make($validated['pin']), // Hash PIN
            'status' => 'active',
        ]);

        return redirect()->route('uuj-admin.dompet.index')->with('success', 'Dompet untuk ' . $santri->full_name . ' berhasil diaktifkan.');
    }

    /**
     * Tampilkan halaman edit dompet (Ganti PIN, Blokir, Ganti Barcode).
     */
    public function edit(Dompet $dompet)
    {
        // Keamanan (Trait BelongsToPondok otomatis mengamankan)
        $dompet->load('santri');
        return view('uuj-admin.dompet.edit', compact('dompet'));
    }

    /**
     * Update dompet (Ganti PIN, Blokir, Ganti Barcode).
     */
    public function update(Request $request, Dompet $dompet)
    {
        // Keamanan (Trait BelongsToPondok otomatis mengamankan)
        
        $validated = $request->validate([
            'status' => 'required|in:active,blocked',
            'daily_spending_limit' => 'nullable|numeric|min:0',
            'pin' => 'nullable|numeric|digits:6|confirmed', // PIN baru (opsional)
            'regenerate_barcode' => 'nullable|boolean', // Checkbox ganti barcode
        ]);
        
        // 1. Update Status & Limit
        $dompet->status = $validated['status'];
        $dompet->daily_spending_limit = $validated['daily_spending_limit'];

        // 2. Update PIN (jika diisi)
        if ($request->filled('pin')) {
            $dompet->pin = Hash::make($validated['pin']);
        }

        // 3. Ganti Barcode (jika dicentang)
        if ($request->has('regenerate_barcode')) {
            $dompet->barcode_token = Str::random(32); // Buat token baru
        }
        
        $dompet->save();
        
        return redirect()->route('uuj-admin.dompet.index')->with('success', 'Dompet ' . $dompet->santri->full_name . ' berhasil diperbarui.');
    }
}