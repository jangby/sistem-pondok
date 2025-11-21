<?php

namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PondokStaff; // <-- Tambahkan Model ini
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class AdminPendidikanController extends Controller
{
    /**
     * Helper untuk mendapatkan Pondok ID dari user yang sedang login
     */
    private function getPondokId()
    {
        // Mengambil ID pondok dari relasi staff user yang login
        return auth()->user()->pondokStaff->pondok_id;
    }

    /**
     * Menampilkan daftar Admin Pendidikan (Madin).
     */
    public function index()
    {
        $pondokId = $this->getPondokId();

        // PERBAIKAN: Menggunakan whereHas untuk cek relasi ke pondok_staff
        $admins = User::role('admin-pendidikan')
                      ->whereHas('pondokStaff', function($q) use ($pondokId) {
                          $q->where('pondok_id', $pondokId);
                      })
                      ->latest()
                      ->paginate(10);

        return view('sekolah.superadmin.admin-pendidikan.index', compact('admins'));
    }

    /**
     * Form tambah Admin Pendidikan baru.
     */
    public function create()
    {
        return view('sekolah.superadmin.admin-pendidikan.create');
    }

    /**
     * Proses simpan data.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telepon' => ['nullable', 'string', 'max:20'], 
        ]);

        $pondokId = $this->getPondokId();

        DB::transaction(function () use ($request, $pondokId) {
            // 1. Buat User (Tanpa kolom pondok_id)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telepon' => $request->telepon,
            ]);

            // 2. Assign Role
            $user->assignRole('admin-pendidikan');

            // 3. PERBAIKAN: Hubungkan ke Pondok via tabel pondok_staff
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
            ]);
        });

        return redirect()->route('sekolah.superadmin.admin-pendidikan.index')
                         ->with('success', 'Akun Kepala Madin / Admin Pendidikan berhasil dibuat.');
    }

    /**
     * Form edit data.
     */
    public function edit($id)
    {
        $pondokId = $this->getPondokId();

        // Pastikan user yang diedit adalah milik pondok ini
        $user = User::whereHas('pondokStaff', function($q) use ($pondokId) {
                        $q->where('pondok_id', $pondokId);
                    })
                    ->findOrFail($id);

        return view('sekolah.superadmin.admin-pendidikan.edit', compact('user'));
    }

    /**
     * Proses update data.
     */
    public function update(Request $request, $id)
    {
        $pondokId = $this->getPondokId();

        $user = User::whereHas('pondokStaff', function($q) use ($pondokId) {
                        $q->where('pondok_id', $pondokId);
                    })
                    ->findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'telepon' => $request->telepon,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('sekolah.superadmin.admin-pendidikan.index')
                         ->with('success', 'Data Admin Pendidikan berhasil diperbarui.');
    }

    /**
     * Hapus akun.
     */
    public function destroy($id)
    {
        $pondokId = $this->getPondokId();

        $user = User::whereHas('pondokStaff', function($q) use ($pondokId) {
                        $q->where('pondok_id', $pondokId);
                    })
                    ->findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Hapus data staff dulu (opsional, biasanya cascade, tapi lebih aman dihapus manual/otomatis via model)
        $user->pondokStaff()->delete(); 
        $user->delete();

        return redirect()->route('sekolah.superadmin.admin-pendidikan.index')
                         ->with('success', 'Akun Admin Pendidikan berhasil dihapus.');
    }
}