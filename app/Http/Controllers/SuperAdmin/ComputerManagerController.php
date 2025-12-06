<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ComputerLog;
use Illuminate\Http\Request;

class ComputerManagerController extends Controller
{
    public function index()
    {
        // Ambil data komputer, urutkan dari yang terbaru update
        $computers = ComputerLog::orderBy('last_seen', 'desc')->get();
        
        return view('superadmin.computer-manager.index', compact('computers'));
    }

    public function sendCommand(Request $request, $id)
{
    $computer = Computer::findOrFail($id);

    // Validasi input agar hanya menerima 'shutdown' atau 'logout'
    $request->validate([
        'command' => 'required|in:shutdown,logout',
    ]);

    // Update status perintah
    $computer->update([
        'pending_command' => $request->command
    ]);

    $aksi = $request->command == 'shutdown' ? 'dimatikan' : 'dilogout';
    
    return back()->with('success', "Perintah $request->command berhasil dikirim ke " . $computer->pc_name . ". PC akan $aksi dalam beberapa detik.");
}
}