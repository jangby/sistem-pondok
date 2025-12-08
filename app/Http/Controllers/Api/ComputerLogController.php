<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComputerLog; // Pastikan menggunakan Model yang benar
use Illuminate\Support\Facades\Log;

class ComputerLogController extends Controller
{
    /**
     * Fungsi 1: Menerima Password Baru
     * Endpoint: POST /api/update-pc-password
     * Dipanggil oleh Python saat startup.
     */
    public function store(Request $request)
    {
        // 1. Cek Security Key (Wajib sama dengan di Python)
        // Pastikan SECRET_KEY di file .env atau hardcoded di sini sama dengan di script Python
        $secretKey = $request->header('x-secret-key');
        
        // Ganti 'rahasia123' dengan key yang Anda pakai di script Python
        if ($secretKey !== 'rahasia123') { 
            return response()->json([
                'status' => 'error', 
                'message' => 'Akses ditolak: Secret Key salah'
            ], 403);
        }

        // 2. Validasi Input dari Python
        $request->validate([
            'pc_name' => 'required|string',
            'password' => 'nullable|string', // Password opsional (dikirim saat ganti pass)
        ]);

        try {
            // 3. Logic Inti: Cari atau Buat Komputer Baru
            // updateOrCreate akan mencari berdasarkan 'pc_name'.
            // Jika ketemu -> Update data. Jika tidak -> Buat baru.
            $pc = ComputerLog::updateOrCreate(
                ['pc_name' => $request->pc_name], // Kunci pencarian (WHERE)
                [
                    'ip_address' => $request->ip(),   // Update IP terbaru
                    'last_seen'  => now(),            // PENTING: Update waktu agar status jadi ONLINE
                ]
            );

            // 4. Update Password (Hanya jika Python mengirim password baru)
            // Ini menangani kasus rotasi password otomatis
            if ($request->filled('password')) {
                $pc->update([
                    'password' => $request->password
                ]);
                
                Log::info("Password untuk PC {$request->pc_name} berhasil diperbarui via API.");
            }

            // 5. Berikan Respon Sukses ke Python
            return response()->json([
                'status' => 'success',
                'message' => 'Sinkronisasi berhasil',
                'data' => [
                    'pc_name' => $pc->pc_name,
                    'status' => 'Online',
                    'updated_at' => $pc->updated_at
                ]
            ], 200);

        } catch (\Exception $e) {
            // Catat error di storage/logs/laravel.log untuk debugging
            Log::error("API Error [ComputerLog]: " . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan di server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fungsi 2: Cek Perintah (Polling)
     * Endpoint: POST /api/check-command
     * Dipanggil oleh Python setiap beberapa detik (Looping).
     */
    public function checkCommand(Request $request)
    {
        // 1. Cek Kunci Keamanan
        if ($request->header('x-secret-key') !== 'rahasia123') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pc_name = $request->input('pc_name');

        // 2. Cari Komputer di Database
        $computer = ComputerLog::where('pc_name', $pc_name)->first();

        // 3. Cek apakah ada perintah (shutdown/logout) yang menunggu?
        if ($computer && $computer->pending_command) {
            $command = $computer->pending_command;

            // PENTING: Kosongkan kolom pending_command setelah dibaca
            // Agar komputer tidak mati terus-menerus saat dinyalakan lagi.
            $computer->update([
                'pending_command' => null,
                'last_seen' => now() // Sekalian update status online
            ]);

            // Kirim perintah ke Python
            return response()->json(['command' => $command]);
        }

        // Jika tidak ada perintah, update saja status 'last_seen' biar ketahuan Online
        if ($computer) {
            $computer->update(['last_seen' => now()]);
        }

        return response()->json(['command' => null]);
    }
}