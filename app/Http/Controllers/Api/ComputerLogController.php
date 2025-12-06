<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComputerLog;

class ComputerLogController extends Controller
{
    public function store(Request $request)
    {
        // Simple Security check (Pastikan di .env ada PC_SECRET_KEY=rahasia123)
        if ($request->header('x-secret-key') !== env('PC_SECRET_KEY', 'default-key')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'pc_name' => 'required',
            'password' => 'required',
        ]);

        // Simpan atau Update jika PC sudah ada
        ComputerLog::updateOrCreate(
            ['pc_name' => $request->pc_name], // Cari berdasarkan nama PC
            [
                'password' => $request->password,
                'ip_address' => $request->ip(),
                'last_seen' => now(),
            ]
        );

        return response()->json(['status' => 'success'], 200);
    }
}