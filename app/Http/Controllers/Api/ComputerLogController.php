<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComputerLog;
use Illuminate\Support\Facades\Log; // PERBAIKAN 1: Import Class Log

class ComputerLogController extends Controller
{
    /**
     * Fungsi 1: Menerima Password Baru
     * Endpoint: POST /api/update-pc-password
     * Dipanggil oleh Python saat startup.
     */
    public function store(Request $request)
    {
        // 1. Cek Security Key
        $secretKey = $request->header('x-secret-key');
        
        if ($secretKey !== 'rahasia123') { 
            return response()->json([
                'status' => 'error', 
                'message' => 'Akses ditolak: Secret Key salah'
            ], 403);
        }

        // 2. Validasi Input
        $request->validate([
            'pc_name' => 'required|string',
            'password' => 'nullable|string',
        ]);

        try {
            // PERBAIKAN 2 (CARA 1): Siapkan data dulu sebelum disimpan
            // Ini mencegah error MySQL karena kolom password tidak boleh kosong saat Create
            
            $dataToSave = [
                'ip_address' => $request->ip(),
                'last_seen'  => now(),
            ];

            // Jika Python mengirim password, masukkan ke array data utama
            if ($request->filled('password')) {
                $dataToSave['password'] = $request->password;
            }

            // 3. Eksekusi Create atau Update
            // Laravel akan otomatis menggunakan 'password' jika ini data baru (Create)
            // Atau mengupdate 'password' jika data lama (Update) dan password ada di array
            $pc = ComputerLog::updateOrCreate(
                ['pc_name' => $request->pc_name], // Kunci pencarian
                $dataToSave                       // Data yang disimpan
            );

            // Log info (Opsional)
            if ($request->filled('password')) {
                Log::info("Password untuk PC {$request->pc_name} berhasil disinkronkan.");
            }

            // 4. Berikan Respon Sukses
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
            // Catat error ke file log Laravel
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
     * Dipanggil oleh Python setiap beberapa detik.
     */
    public function checkCommand(Request $request)
    {
        // PERBAIKAN 3: Konsistensi Secret Key (Samakan dengan fungsi store)
        if ($request->header('x-secret-key') !== 'rahasia123') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pc_name = $request->input('pc_name');
        
        // Cari PC
        $computer = ComputerLog::where('pc_name', $pc_name)->first();

        // Cek perintah
        if ($computer && $computer->pending_command) {
            $command = $computer->pending_command;

            // Hapus perintah agar tidak dijalankan berulang kali
            $computer->update([
                'pending_command' => null,
                'last_seen' => now()
            ]);

            return response()->json(['command' => $command]);
        }

        // Update status online
        if ($computer) {
            $computer->update(['last_seen' => now()]);
        }

        return response()->json(['command' => null]);
    }
}