<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\OrangTua;
use App\Models\User; // <-- IMPORT INI
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // <-- IMPORT INI
use Illuminate\Support\Facades\Hash; // <-- IMPORT INI

class OrangTuaController extends Controller
{
    /**
     * Menampilkan daftar orang tua.
     */
    public function index()
    {
        // (Fungsi index Anda sudah ada dan benar)
        $orangTuas = OrangTua::latest()->paginate(10);
        return view('adminpondok.orangtuas.index', compact('orangTuas'));
    }

    /**
     * Menampilkan form tambah orang tua baru.
     */
    public function create()
    {
        // (Fungsi create Anda sudah ada dan benar)
        return view('adminpondok.orangtuas.create');
    }

    public function store(Request $request)
    {
        $pondokId = auth()->user()->pondokStaff->pondok_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // --- DATA BARU ---
            'nik' => 'nullable|string|max:20', 
            'pekerjaan' => 'nullable|string|max:100',
            // -----------------
            'phone' => [
                'nullable', 'string', 'max:20',
                Rule::unique('orang_tuas')->where(fn ($query) => $query->where('pondok_id', $pondokId)),
            ],
            'address' => 'nullable|string',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole('orang-tua');

            OrangTua::create([
                'name' => $validated['name'],
                'nik' => $validated['nik'],           // <-- Tambahan
                'pekerjaan' => $validated['pekerjaan'], // <-- Tambahan
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'user_id' => $user->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('adminpondok.orang-tuas.index')->with('success', 'Orang tua berhasil ditambahkan.');
    }

    public function update(Request $request, OrangTua $orangTua)
    {
        $pondokId = auth()->user()->pondokStaff->pondok_id;
        $user = $orangTua->user;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // --- DATA BARU ---
            'nik' => 'nullable|string|max:20',
            'pekerjaan' => 'nullable|string|max:100',
            // -----------------
            'phone' => [
                'nullable', 'string', 'max:20',
                Rule::unique('orang_tuas')->where(fn ($query) => $query->where('pondok_id', $pondokId))->ignore($orangTua->id),
            ],
            'address' => 'nullable|string',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            $orangTua->update([
                'name' => $validated['name'],
                'nik' => $validated['nik'],             // <-- Tambahan
                'pekerjaan' => $validated['pekerjaan'], // <-- Tambahan
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }

        return redirect()->route('adminpondok.orang-tuas.index')->with('success', 'Data diperbarui.');
    }


    // --- INI FUNGSI BARU YANG KITA PERBAIKI ---

    /**
     * Menampilkan form edit data orang tua.
     */
    public function edit(OrangTua $orangTua)
    {
        // Trait BelongsToPondok otomatis mengamankan
        // Kita 'load' relasi user-nya agar data email bisa ditampilkan di form
        $orangTua->load('user'); 
        
        return view('adminpondok.orangtuas.edit', compact('orangTua'));
    }

    /**
     * Hapus data orang tua.
     */
    public function destroy(OrangTua $orangTua)
    {
        // (Fungsi destroy Anda sudah ada dan benar)
        try {
            if ($orangTua->santris()->count() > 0) {
                return redirect()->route('adminpondok.orang-tuas.index')
                                 ->with('error', 'Gagal! Orang tua ini masih memiliki data santri.');
            }
            
            // Hapus User Login-nya juga
            // Gunakan Transaction untuk keamanan
            DB::beginTransaction();
            $user = $orangTua->user;
            $orangTua->delete(); // Hapus profil orang tua
            $user->delete();     // Hapus data login user
            DB::commit();

            return redirect()->route('adminpondok.orang-tuas.index')
                             ->with('success', 'Data orang tua dan akun login-nya berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adminpondok.orang-tuas.index')
                             ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}