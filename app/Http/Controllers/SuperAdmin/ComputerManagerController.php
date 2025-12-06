<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComputerLog; 
use Illuminate\Support\Facades\Log;

class ComputerManagerController extends Controller
{
    /**
     * Menampilkan daftar komputer di halaman Admin
     */
    public function index()
    {
        $computers = ComputerLog::orderBy('last_seen', 'desc')->get();
        return view('superadmin.computer-manager.index', compact('computers'));
    }

    /**
     * Mengirim perintah (Shutdown/Logout) dari tombol di Web
     */
    public function sendCommand(Request $request, $id)
    {
        $computer = ComputerLog::findOrFail($id);

        $request->validate([
            'command' => 'required|in:shutdown,logout,restart',
        ]);

        // Simpan perintah ke database agar nanti diambil oleh Python
        $computer->update([
            'pending_command' => $request->command
        ]);

        $aksi = $request->command == 'shutdown' ? 'dimatikan' : ($request->command == 'restart' ? 'direstart' : 'dilogout');
        
        return back()->with('success', "Perintah $request->command berhasil dikirim ke " . $computer->pc_name . ". PC akan $aksi sebentar lagi.");
    }

    /**
     * [API] Fungsi ini dipanggil oleh Script Python Client
     * untuk mengecek apakah ada perintah baru.
     */
    public function checkCommand(Request $request)
    {
        try {
            // 1. Ambil input nama PC dari Python
            $pcName = $request->input('pc_name'); 
            
            if (!$pcName) {
                return response()->json(['command' => null, 'message' => 'PC Name tidak dikirim'], 400);
            }

            // 2. Cari PC di Database
            $pc = ComputerLog::where('pc_name', $pcName)->first();

            // Jika PC tidak ditemukan, berikan respon 404 (aman, tidak akan bikin script crash)
            if (!$pc) {
                return response()->json(['command' => null, 'message' => 'PC belum terdaftar'], 404);
            }

            // 3. Update status 'last_seen' agar kita tahu PC ini sedang online
            $pc->update(['last_seen' => now()]);

            // 4. Cek apakah Admin mengirim perintah (pending_command)
            $pendingCommand = $pc->pending_command;

            if ($pendingCommand) {
                // PENTING: Hapus perintah setelah diambil agar tidak dieksekusi berkali-kali
                $pc->update(['pending_command' => null]);

                // Kirim perintah ke Python (shutdown/logout)
                return response()->json([
                    'status' => 'success',
                    'command' => $pendingCommand
                ]);
            }

            // Jika tidak ada perintah, kirim null
            return response()->json([
                'status' => 'success',
                'command' => null
            ]);

        } catch (\Exception $e) {
            // Log error untuk developer
            Log::error("API Check Command Error: " . $e->getMessage());
            
            // Return JSON error agar Python bisa membacanya
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}