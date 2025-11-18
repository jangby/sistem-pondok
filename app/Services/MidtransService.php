<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\CoreApi;

class MidtransService
{
    /**
     * Inisialisasi konfigurasi Midtrans.
     */
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Membuat transaksi Core API (misal: VA, QRIS).
     *
     * @param array $params Parameter transaksi
     * @return mixed Respon dari Midtrans
     */
    public function createTransaction(array $params)
    {
        try {
            // Panggil CoreApi::charge dari library Midtrans
            $response = CoreApi::charge($params);
            return $response;
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return null; // Kembalikan null jika gagal
        }
    }

    /**
     * Memeriksa status transaksi di Midtrans.
     */
    public function getTransactionStatus($orderId)
    {
        try {
            return CoreApi::status($orderId);
        } catch (\Exception $e) {
            \Log::error('Midtrans Status Check Error: ' . $e->getMessage());
            return null;
        }
    }
}