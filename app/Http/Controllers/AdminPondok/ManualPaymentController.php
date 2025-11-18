<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Services\PaymentService; // <-- IMPORT SERVICE KITA
use Illuminate\Support\Facades\Auth;

class ManualPaymentController extends Controller
{
    protected $paymentService;

    // Otomatis 'inject' service kita
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Simpan pembayaran manual dari admin
     */
    public function store(Request $request, Tagihan $tagihan)
    {
        // Trait di Tagihan otomatis mengamankan

        $validated = $request->validate([
            'nominal_bayar' => 'required|numeric|min:1|max:' . $tagihan->nominal_tagihan,
            'catatan' => 'required|string|max:100',
        ]);

        // Panggil Service untuk memproses
        $success = $this->paymentService->processManualPayment(
            $tagihan,
            (float) $validated['nominal_bayar'],
            Auth::user(), // Admin yang sedang login
            $validated['catatan']
        );

        if ($success) {
            return redirect()->route('adminpondok.tagihan.show', $tagihan->id)
                             ->with('success', 'Pembayaran manual berhasil dicatat.');
        } else {
            return redirect()->route('adminpondok.tagihan.show', $tagihan->id)
                             ->with('error', 'Gagal mencatat pembayaran. Terjadi kesalahan server.');
        }
    }
}