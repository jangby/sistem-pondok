<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pondok;
use App\Models\User;
use App\Models\PondokStaff; // <-- PASTIKAN INI DI-IMPORT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminPondokController extends Controller
{
    public function create()
    {
        // Ambil semua pondok untuk dropdown
        $pondoks = Pondok::where('status', 'active')->orderBy('name')->get(); 
        
        // Cek jika tidak ada pondok
        if ($pondoks->isEmpty()) {
            return redirect()->route('superadmin.pondoks.index')
                             ->with('error', 'Silakan tambahkan data Pondok terlebih dahulu.');
        }

        return view('superadmin.admins.create', compact('pondoks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'pondok_id' => 'required|exists:pondoks,id',
        ]);

        DB::beginTransaction();
        try {
            // a. Buat User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // b. Beri role
            $user->assignRole('admin-pondok');

            // c. Tautkan ke pondok
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $validated['pondok_id'],
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Gagal mendaftarkan admin: ' . $e->getMessage())
                             ->withInput();
        }

        // Redirect ke halaman index pondok agar bisa langsung terlihat
        return redirect()->route('superadmin.pondoks.index')
                         ->with('success', 'Admin Pondok baru berhasil didaftarkan.');
    }
}