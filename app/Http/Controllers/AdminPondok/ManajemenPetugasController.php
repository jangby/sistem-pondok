<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ManajemenPetugasController extends Controller
{
    // Tampilkan Daftar Petugas
    public function index()
    {
        // Ambil user yang punya role 'petugas_ppdb'
        $petugas = User::role('petugas_ppdb')->latest()->get();
        return view('adminpondok.ppdb.petugas.index', compact('petugas'));
    }

    // Tampilkan Form Tambah
    public function create()
    {
        return view('adminpondok.ppdb.petugas.create');
    }

    // Simpan Petugas Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'telepon' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'password' => Hash::make($request->password),
        ]);

        // Tetapkan Role Khusus
        $user->assignRole('petugas_ppdb');

        return redirect()->route('adminpondok.ppdb.petugas.index')
            ->with('success', 'Akun Petugas berhasil dibuat.');
    }

    // Hapus Petugas
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Proteksi: Jangan sampai menghapus admin secara tidak sengaja
        if($user->hasRole('admin-pondok')) {
            return back()->with('error', 'Tidak bisa menghapus Admin Utama!');
        }

        $user->delete();

        return back()->with('success', 'Data petugas berhasil dihapus.');
    }
}