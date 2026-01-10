<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Penulis; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class PenulisAccountController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        
        // AMBIL ID PONDOK (Logic: Cek User -> Cek Staff)
        $pondokId = $admin->pondok_id ?? $admin->pondokStaff?->pondok_id;

        if (!$pondokId) {
            abort(403, 'Akun Admin ini tidak terhubung ke data Pondok.');
        }

        // Ambil penulis milik pondok ini
        $penulis = User::role('penulis')
            ->whereHas('penulis', function($q) use ($pondokId) {
                $q->where('pondok_id', $pondokId);
            })
            ->get();

        return view('adminpondok.penulis.index', compact('penulis'));
    }

    public function create()
    {
        return view('adminpondok.penulis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // --- BAGIAN PENTING (PERBAIKAN) ---
        $admin = Auth::user();
        
        // Kita cari ID Pondok dari relasi 'pondokStaff' (seperti akun pengurus)
        // Jika di users null, dia akan ambil dari tabel staff
        $pondokId = $admin->pondok_id ?? $admin->pondokStaff?->pondok_id;

        // Validasi terakhir agar tidak error 'Integrity constraint'
        if (!$pondokId) {
            return back()->withErrors(['msg' => 'Gagal: Data Pondok ID tidak ditemukan pada akun Admin Anda.']);
        }
        // -----------------------------------

        // 1. Buat User Login
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'pondok_id' -> Tidak perlu diisi di sini agar tidak error
        ]);

        $user->assignRole('penulis');

        // 2. Simpan ke Tabel Profil Penulis
        Penulis::create([
            'user_id' => $user->id,
            'pondok_id' => $pondokId, // <--- Ini sekarang sudah pasti ada isinya
        ]);

        return redirect()->route('penulis.index')
            ->with('success', 'Akun Penulis berhasil dibuat.');
    }

    public function destroy($id)
    {
        $admin = Auth::user();
        $pondokId = $admin->pondok_id ?? $admin->pondokStaff?->pondok_id;

        $user = User::findOrFail($id);
        
        // Pastikan hanya menghapus penulis milik pondok sendiri
        if($user->penulis && $user->penulis->pondok_id == $pondokId) {
            $user->delete();
            return redirect()->back()->with('success', 'Akun penulis berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus akun.');
    }
}