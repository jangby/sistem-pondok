<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PondokStaff; // Tambahkan Model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB; // Tambahkan DB Facade

class PetugasPerpulanganController extends Controller
{
    private function getPondokId()
    {
        // Ambil ID pondok dari relasi pondokStaff milik user yang login
        return auth()->user()->pondokStaff->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();

        // FIX: Filter user berdasarkan relasi pondokStaff, bukan kolom pondok_id di tabel users
        $petugas = User::role('petugas_perpulangan')
                    ->whereHas('pondokStaff', function($q) use ($pondokId) {
                        $q->where('pondok_id', $pondokId);
                    })
                    ->latest()
                    ->get();

        return view('pengurus.perpulangan.petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('pengurus.perpulangan.petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $pondokId = $this->getPondokId();

        // Gunakan Transaksi agar data konsisten (User & Staff terbuat bersamaan)
        DB::transaction(function() use ($request, $pondokId) {
            
            // 1. Buat User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // Jangan masukkan 'pondok_id' di sini karena tidak ada di tabel users
            ]);

            // 2. Berikan Role
            $user->assignRole('petugas_perpulangan');

            // 3. Hubungkan User ke Pondok via tabel pondok_staff
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
            ]);
        });

        return redirect()->route('pengurus.perpulangan.petugas.index')
            ->with('success', 'Akun petugas berhasil dibuat.');
    }

    public function edit($id)
    {
        // Pastikan user yang diedit berada di pondok yang sama
        $pondokId = $this->getPondokId();
        
        $user = User::whereHas('pondokStaff', function($q) use ($pondokId) {
                $q->where('pondok_id', $pondokId);
            })
            ->findOrFail($id);

        return view('pengurus.perpulangan.petugas.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        
        $user = User::whereHas('pondokStaff', function($q) use ($pondokId) {
                $q->where('pondok_id', $pondokId);
            })
            ->findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('pengurus.perpulangan.petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pondokId = $this->getPondokId();
        
        $user = User::whereHas('pondokStaff', function($q) use ($pondokId) {
                $q->where('pondok_id', $pondokId);
            })
            ->findOrFail($id);

        // Hapus user (otomatis menghapus data di pondok_staff karena cascade on delete)
        $user->delete();

        return redirect()->route('pengurus.perpulangan.petugas.index')
            ->with('success', 'Akun petugas berhasil dihapus.');
    }
}