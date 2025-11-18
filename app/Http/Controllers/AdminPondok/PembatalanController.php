<?php
namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranTransaksi;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;

class PembatalanController extends Controller
{
    /**
     * Aksi untuk membatalkan transaksi
     */
    public function cancel(PembayaranTransaksi $transaksi, PaymentService $paymentService)
    {
        // Cek Keamanan (apakah transaksi ini milik pondok si admin?)
        if ($transaksi->pondok_id != Auth::user()->pondokStaff->pondok_id) {
            abort(404);
        }

        $admin = Auth::user();
        $success = $paymentService->cancelPayment($transaksi, $admin);

        if ($success) {
            return redirect()->back()->with('success', 'Pembayaran berhasil dibatalkan.');
        } else {
            return redirect()->back()->with('error', 'Gagal membatalkan pembayaran. Transaksi mungkin sudah disetor.');
        }
    }
}