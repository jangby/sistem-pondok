<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pondok;
use App\Models\User;
use App\Models\PondokStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminPendidikanController extends Controller
{
    // Menampilkan Form
    public function create()
    {
        // Ambil semua pondok aktif untuk dropdown
        $pondoks = Pondok::where('status', 'active')->orderBy('name')->get(); 
        
        if ($pondoks->isEmpty()) {
            return redirect()->route('superadmin.pondoks.index')
                             ->with('error', 'Data Pondok tidak ditemukan.');
        }

        return view('superadmin.admin-pendidikan.create', compact('pondoks'));
    }

    // Proses Simpan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'pondok_id' => 'required|exists:pondoks,id',
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat User Baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'pondok_id' => $validated['pondok_id'], // Pastikan kolom ini ada di tabel users
            ]);

            // 2. Berikan Role 'admin-pendidikan'
            $user->assignRole('admin-pendidikan');

            // 3. Catat sebagai Staff Pondok (Agar tercatat di manajemen staff)
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $validated['pondok_id'],
                // 'role' => 'admin-pendidikan' // Jika ada kolom role di tabel staff, tambahkan ini
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Gagal mendaftarkan Admin Pendidikan: ' . $e->getMessage())
                             ->withInput();
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->route('superadmin.pondoks.index')
                         ->with('success', 'Akun Admin Pendidikan berhasil dibuat.');
    }
}