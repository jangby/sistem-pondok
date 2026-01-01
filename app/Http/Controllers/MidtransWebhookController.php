<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembayaranTransaksi;
use App\Models\PpdbTransaction; // <-- Model PPDB Transaksi
use App\Models\CalonSantri;     // <-- Model Calon Santri
use App\Services\PaymentService;
use App\Services\DompetService;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Notifications\TopupLunasNotification;

class MidtransWebhookController extends Controller
{
    protected $paymentService;
    protected $dompetService;

    public function __construct(
        PaymentService $paymentService, 
        DompetService $dompetService
    ) {
        $this->paymentService = $paymentService;
        $this->dompetService = $dompetService;

        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
    }

    public function handle(Request $request)
    {
        try {
            // 1. Ambil Notifikasi dari Midtrans
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;
            $grossAmount = $notif->gross_amount;
            $statusCode = $notif->status_code;

            // 2. Validasi Keamanan (Signature Key)
            // Hash: order_id + status_code + gross_amount + server_key
            $serverKey = config('midtrans.server_key');
            $inputSignature = $notif->signature_key;
            $mySignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

            if ($inputSignature !== $mySignature) {
                Log::warning("Security Alert: Invalid Signature Key pada Order ID {$orderId}");
                return response()->json(['message' => 'Invalid Signature'], 403);
            }

            // 3. Logika Percabangan (PPDB vs Sistem Lama)
            if (str_starts_with($orderId, 'PPDB-')) {
                // === HANDLE PEMBAYARAN PPDB ===
                return $this->handlePpdbPayment($notif, $transaction, $fraud);
            } else {
                // === HANDLE PEMBAYARAN SPP / DOMPET (Sistem Lama) ===
                return $this->handleExistingSystemPayment($notif, $transaction);
            }

        } catch (\Exception $e) {
            Log::error('Webhook Midtrans Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Logika Khusus Pembayaran PPDB
     */
    private function handlePpdbPayment($notif, $status, $fraud)
    {
        $orderId = $notif->order_id;
        
        // Cari Transaksi PPDB
        $transaksi = PpdbTransaction::where('order_id', $orderId)->first();
        if (!$transaksi) {
            return response()->json(['message' => 'PPDB Order not found'], 404);
        }

        // Cek Idempotency (Jika sudah sukses, jangan diproses lagi)
        if ($transaksi->status == 'success' || $transaksi->status == 'paid') {
            return response()->json(['message' => 'Already processed'], 200);
        }

        DB::beginTransaction();
        try {
            if ($status == 'capture') {
                if ($fraud == 'challenge') {
                    $transaksi->update(['status' => 'pending']);
                } else {
                    $transaksi->update(['status' => 'success']); // atau 'paid'
                    $this->updateSaldoPpdb($transaksi);
                }
            } elseif ($status == 'settlement') {
                $transaksi->update(['status' => 'success']); // atau 'paid'
                $this->updateSaldoPpdb($transaksi);
            } elseif ($status == 'pending') {
                $transaksi->update(['status' => 'pending']);
            } elseif ($status == 'deny' || $status == 'expire' || $status == 'cancel') {
                $transaksi->update(['status' => 'failed']);
            }

            DB::commit();
            return response()->json(['message' => 'PPDB Payment Processed'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PPDB Payment DB Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Helper Update Saldo & Status Calon Santri
     */
    private function updateSaldoPpdb($transaksi)
    {
        $calonSantri = $transaksi->calonSantri;
        if ($calonSantri) {
            // Tambah total bayar
            // (Pastikan gross_amount angka murni, hilangkan .00 jika string)
            $amount = (float) $transaksi->gross_amount; 

            // Hindari double count logic (opsional, tergantung app Anda)
            // Di sini kita update sisa tagihan/total bayar
            $calonSantri->total_sudah_bayar += $amount;

            // Update status lunas
            if ($calonSantri->sisa_tagihan <= 0) {
                $calonSantri->status_pembayaran = 'lunas';
            } else {
                $calonSantri->status_pembayaran = 'sebagian';
            }

            $calonSantri->save();
        }
    }

    /**
     * Logika Pembayaran Lama (SPP, Tagihan, Dompet)
     */
    private function handleExistingSystemPayment($notif, $status)
    {
        $orderId = $notif->order_id;
        $settlementTime = $notif->settlement_time ?? now();

        // Cari Transaksi Lama
        $transaksi = PembayaranTransaksi::where('midtrans_order_id', $orderId)->first();

        if (!$transaksi) {
            Log::warning("Transaction not found for Order ID {$orderId}");
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaksi->status_verifikasi == 'verified') {
            return response()->json(['message' => 'Already verified'], 200);
        }

        DB::beginTransaction();
        try {
            if ($status == 'settlement' || $status == 'capture') {
                // --- SUKSES ---
                $transaksi->status_verifikasi = 'verified';
                $transaksi->tanggal_bayar = $settlementTime;
                $transaksi->catatan_verifikasi = 'Pembayaran Midtrans Berhasil (' . $notif->payment_type . ')';
                $transaksi->save();

                if ($transaksi->tagihan_id) {
                    // Bayar Tagihan
                    $this->paymentService->processPaymentAllocation($transaksi);
                } elseif ($transaksi->dompet_id) {
                    // Topup Dompet
                    $nominalTopup = $transaksi->total_bayar - $transaksi->biaya_admin;
                    
                    $this->dompetService->createTransaksi(
                        $transaksi->dompet,
                        $nominalTopup,
                        'topup_midtrans',
                        'Top-up via Midtrans (ID: ' . $transaksi->midtrans_order_id . ')',
                        $transaksi->orangTua->user
                    );
                    
                    // Notif WA
                    try {
                        $transaksi->orangTua->notify(new TopupLunasNotification($transaksi, $transaksi->dompet->fresh()));
                    } catch (\Exception $e) {
                        Log::error('WA Notification Error: ' . $e->getMessage());
                    }
                }

            } else if ($status == 'cancel' || $status == 'expire' || $status == 'deny') {
                // --- GAGAL ---
                $transaksi->status_verifikasi = 'rejected';
                $transaksi->catatan_verifikasi = "Pembayaran Midtrans gagal (Status: {$status})";
                $transaksi->save();
            }

            DB::commit();
            return response()->json(['message' => 'Payment Processed'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Existing Payment DB Error: " . $e->getMessage());
            throw $e;
        }
    }
}