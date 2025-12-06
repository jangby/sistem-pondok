<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComputerLog; // Pastikan menggunakan Model yang benar

class ComputerLogController extends Controller
{
    /**
     * Fungsi 1: Menerima Password Baru
     * Endpoint: POST /api/update-pc-password
     * Dipanggil oleh Python saat startup.
     */
    public function store(Request $request)
    {
        // 1. Cek Kunci Keamanan (Wajib sama dengan .env)
        if ($request->header('x-secret-key') !== env('PC_SECRET_KEY', 'default-key')) {
            return response()->json(['message' => 'Unauthorized - Secret Key Salah'], 403);
        }

        // 2. Validasi Input
        $request->validate([
            'pc_name' => 'required|string',
            'password' => 'required|string',
        ]);

        // 3. Simpan atau Update Data Komputer
        // updateOrCreate: Jika PC sudah ada update datanya, jika belum buat baru.
        $computer = ComputerLog::updateOrCreate(
            ['pc_name' => $request->pc_name], // Kunci pencarian
            [
                'password'   => $request->password,
                'ip_address' => $request->ip(), // Simpan IP Client
                'last_seen'  => now(),      // Update waktu terakhir terlihat
            ]
        );

        // 4. (Opsional) Cek sekalian apakah ada perintah pending saat startup
        $command = null;
        if ($computer->pending_command) {
            $command = $computer->pending_command;
            $computer->update(['pending_command' => null]); // Hapus perintah setelah diambil
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Password berhasil disimpan.',
            'command' => $command // Bisa mengembalikan perintah langsung jika ada
        ], 200);
    }

    /**
     * Fungsi 2: Cek Perintah (Polling)
     * Endpoint: POST /api/check-command
     * Dipanggil oleh Python setiap beberapa detik (Looping).
     */
    public function checkCommand(Request $request)
    {
        // 1. Cek Kunci Keamanan
        if ($request->header('x-secret-key') !== env('PC_SECRET_KEY', 'default-key')) {
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