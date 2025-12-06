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
            $pcName = $request->input('pc_name');
            
            if (!$pcName) {
                return response()->json(['command' => null], 400);
            }

            // Cari PC
            $pc = ComputerLog::where('pc_name', $pcName)->first();

            if ($pc) {
                // PENTING: Update last_seen setiap kali Python nanya ("Ping")
                // Ini yang membuat status jadi "ONLINE" di dashboard
                $pc->update(['last_seen' => now()]); 

                // Cek apakah ada perintah pending (shutdown/logout)
                if ($pc->pending_command) {
                    $command = $pc->pending_command;
                    
                    // Reset perintah agar tidak dieksekusi berulang
                    $pc->update(['pending_command' => null]);
                    
                    return response()->json(['command' => $command]);
                }
            }

            return response()->json(['command' => null]);

        } catch (\Exception $e) {
            Log::error("API Check Command Error: " . $e->getMessage());
            return response()->json(['command' => null], 500);
        }
    }
}