<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComputerLog;

class ComputerLogController extends Controller
{
    public function store(Request $request)
{
    // --- DEBUGGING AREA (HAPUS NANTI) ---
    $kunci_dari_python = $request->header('x-secret-key');
    $kunci_di_env_laravel = env('PC_SECRET_KEY', 'default-key');

    // Jika kunci TIDAK SAMA, kita kembalikan pesan detail biar ketahuan bedanya
    if ($kunci_dari_python !== $kunci_di_env_laravel) {
        return response()->json([
            'message' => 'Unauthorized (Debugging Mode)',
            'debug_info' => [
                'server_receive' => $kunci_dari_python, // Apa yang diterima server?
                'server_expect'  => $kunci_di_env_laravel // Apa yang diharapkan server?
            ]
        ], 403);
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