<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// PERBAIKAN 1: Import Model yang benar
use App\Models\ComputerLog; 

class ComputerManagerController extends Controller
{
    public function index()
    {
        // PERBAIKAN 2: Gunakan ComputerLog, bukan Computer
        $computers = ComputerLog::orderBy('last_seen', 'desc')->get();
        
        return view('superadmin.computer-manager.index', compact('computers'));
    }

    public function sendCommand(Request $request, $id)
    {
        // PERBAIKAN 3: Gunakan ComputerLog di sini juga
        $computer = ComputerLog::findOrFail($id);

        $request->validate([
            'command' => 'required|in:shutdown,logout,restart',
        ]);

        $computer->update([
            'pending_command' => $request->command
        ]);

        $aksi = $request->command == 'shutdown' ? 'dimatikan' : ($request->command == 'restart' ? 'direstart' : 'dilogout');
        
        return back()->with('success', "Perintah $request->command berhasil dikirim ke " . $computer->pc_name . ". PC akan $aksi sebentar lagi.");
    }
}