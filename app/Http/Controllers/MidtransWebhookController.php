<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembayaranTransaksi;
use App\Services\PaymentService;
use App\Services\DompetService; // <-- Import DompetService
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // <-- Import DB (Perbaikan error 500)
use App\Notifications\PembayaranLunasNotification; // <-- Import Notif Tagihan
use App\Notifications\TopupLunasNotification;      // <-- Import Notif Top-up

class MidtransWebhookController extends Controller
{
    protected $paymentService;
    protected $dompetService; // <-- Deklarasikan property

    /**
     * Inject kedua service saat controller dibuat.
     */
    public function __construct(
        PaymentService $paymentService, 
        DompetService $dompetService // <-- Inject dependency
    ) {
        $this->paymentService = $paymentService;
        $this->dompetService = $dompetService; // <-- Assign ke property

        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
    }

    /**
     * Tangani notifikasi webhook dari Midtrans.
     */
    public function handle(Request $request)
    {
        try {
            // 1. Buat objek Notifikasi dari library Midtrans
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error('Webhook Midtrans Gagal: Gagal membuat objek Notifikasi. ' . $e->getMessage());
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 2. Ambil data payload
        $status = $notif->transaction_status;
        $orderId = $notif->order_id; // Ini adalah midtrans_order_id kita
        $paymentType = $notif->payment_type;
        $settlementTime = $notif->settlement_time ?? now();

        // 3. Cari Transaksi di database kita
        $transaksi = PembayaranTransaksi::where('midtrans_order_id', $orderId)->first();

        if (!$transaksi) {
            Log::warning("Webhook Midtrans: Transaksi tidak ditemukan untuk Order ID {$orderId}");
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // 4. Cek Idempotency (Apakah sudah diproses?)
        if ($transaksi->status_verifikasi == 'verified') {
            Log::info("Webhook Midtrans: Transaksi {$orderId} sudah diverifikasi sebelumnya.");
            return response()->json(['message' => 'OK, already processed'], 200);
        }

        // 5. Mulai DB Transaction
        DB::beginTransaction();
        try {
            if ($status == 'settlement' || $status == 'capture') {
                // --- PEMBAYARAN SUKSES ---
                
                // Update transaksi kita (Termasuk perbaikan 'catatan_verifikasi')
                $transaksi->status_verifikasi = 'verified';
                $transaksi->tanggal_bayar = $settlementTime;
                $transaksi->catatan_verifikasi = 'Pembayaran Midtrans Berhasil (' . $notif->payment_type . ')';
                $transaksi->save();

                // ===========================================
                // LOGIKA PEMISAH (Top-up vs Bayar Tagihan)
                // ===========================================
                if ($transaksi->tagihan_id) {
                    // Ini Bayar Tagihan, panggil PaymentService
                    // (PaymentService sudah otomatis kirim notif tagihan lunas)
                    $this->paymentService->processPaymentAllocation($transaksi);
                    
                } elseif ($transaksi->dompet_id) {
                    // Ini Top-up Dompet, panggil DompetService
                    $nominalTopup = $transaksi->total_bayar - $transaksi->biaya_admin;
                    
                    $this->dompetService->createTransaksi(
                        $transaksi->dompet,
                        $nominalTopup,
                        'topup_midtrans',
                        'Top-up via Midtrans (ID: ' . $transaksi->midtrans_order_id . ')',
                        $transaksi->orangTua->user
                    );
                    
                    // Kirim Notifikasi Top-up Berhasil
                    try {
                        // Kita 'fresh()' dompetnya agar dapat saldo TERBARU
                        $transaksi->orangTua->notify(new TopupLunasNotification($transaksi, $transaksi->dompet->fresh()));
                    } catch (\Exception $e) {
                        Log::error('Gagal kirim Notifikasi Top-up WA: ' . $e->getMessage());
                    }
                }
                
            } else if ($status == 'cancel' || $status == 'expire' || $status == 'deny') {
                // --- PEMBAYARAN GAGAL ---
                $transaksi->status_verifikasi = 'rejected';
                $transaksi->catatan_verifikasi = "Pembayaran Midtrans gagal/dibatalkan (Status: {$status})";
                $transaksi->save();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Webhook Midtrans: Gagal memproses Order ID {$orderId}. Error: " . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }

        // 7. Kirim respon OK ke Midtrans
        return response()->json(['message' => 'OK'], 200);
    }
}