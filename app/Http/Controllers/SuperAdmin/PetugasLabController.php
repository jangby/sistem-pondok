<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PetugasLabController extends Controller
{
    /**
     * Menampilkan daftar petugas dalam bentuk Card (Mobile Friendly)
     */
    public function index()
    {
        // Ambil user yang punya role 'petugas_lab'
        $petugas = User::role('petugas_lab')->latest()->get();
        return view('superadmin.petugas-lab.index', compact('petugas'));
    }

    public function create()
    {
        return view('superadmin.petugas-lab.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign Role Spatie
        $user->assignRole('petugas_lab');

        return redirect()->route('superadmin.petugas-lab.index')->with('success', 'Petugas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('superadmin.petugas-lab.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
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

        return redirect()->route('superadmin.petugas-lab.index')->with('success', 'Data petugas diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('superadmin.petugas-lab.index')->with('success', 'Petugas dihapus');
    }
}