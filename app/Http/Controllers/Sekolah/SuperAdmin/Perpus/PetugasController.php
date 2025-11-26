<?php

namespace App\Http\Controllers\Sekolah\SuperAdmin\Perpus;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PondokStaff; // <--- PENTING: Import Model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- Import Auth
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;   // <--- Import DB untuk transaksi

class PetugasController extends Controller
{
    /**
     * Helper: Ambil ID Pondok dari user yang sedang login (Super Admin)
     */
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();

        // Tampilkan hanya petugas milik pondok ini
        $petugas = User::role('petugas_perpus')
            ->whereHas('pondokStaff', function($q) use ($pondokId) {
                $q->where('pondok_id', $pondokId);
            })
            ->latest()
            ->paginate(10);

        return view('sekolah.superadmin.perpus.petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('sekolah.superadmin.perpus.petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $pondokId = $this->getPondokId(); // Ambil ID Pondok pembuat

        DB::beginTransaction(); // Gunakan transaksi database agar aman
        try {
            // 1. Buat User Baru
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2. Assign Role
            $user->assignRole('petugas_perpus');

            // 3. [PERBAIKAN UTAMA] Tautkan User ke Pondok Staff
            PondokStaff::create([
                'user_id'   => $user->id,
                'pondok_id' => $pondokId,
            ]);

            DB::commit();

            return redirect()->route('sekolah.superadmin.perpustakaan.petugas.index')
                ->with('success', 'Akun Petugas Perpustakaan berhasil dibuat dan dihubungkan ke Pondok.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat petugas: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        // Pastikan petugas yang diedit milik pondok ini
        $pondokId = $this->getPondokId();
        $user = User::role('petugas_perpus')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
            ->findOrFail($id);

        return view('sekolah.superadmin.perpus.petugas.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        $user = User::role('petugas_perpus')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
            ->findOrFail($id);

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update([
            'name'  => $request->name,
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

        return redirect()->route('sekolah.superadmin.perpustakaan.petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pondokId = $this->getPondokId();
        $user = User::role('petugas_perpus')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
            ->findOrFail($id);
        
        $user->delete();

        return redirect()->route('sekolah.superadmin.perpustakaan.petugas.index')
            ->with('success', 'Akun petugas berhasil dihapus.');
    }
}