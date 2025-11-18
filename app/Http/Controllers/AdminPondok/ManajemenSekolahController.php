<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PondokStaff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules; // <-- Import Rules Password

class ManajemenSekolahController extends Controller
{
    // Helper untuk mengambil Pondok ID
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    // Helper untuk keamanan
    private function checkOwnership(User $user)
    {
        $isMyStaff = $user->pondokStaff()
                            ->where('pondok_id', $this->getPondokId())
                            ->exists();

        if (!$isMyStaff || !$user->hasRole('super-admin-sekolah')) {
            abort(404, 'Data Admin Sekolah tidak ditemukan');
        }
    }

    /**
     * Tampilkan halaman manajemen (daftar Super Admin Sekolah)
     */
    public function index() 
    {
        $pondokId = $this->getPondokId();
        
        // Ambil user dengan role 'super-admin-sekolah' di pondok ini
        $users = User::role('super-admin-sekolah')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
            ->latest()
            ->paginate(10);
            
        return view('adminpondok.sekolah.index', compact('users')); 
    }

    /**
     * Tampilkan form create Super Admin Sekolah
     */
    public function create() 
    {
        return view('adminpondok.sekolah.create');
    }

    /**
     * Simpan Super Admin Sekolah baru
     */
    public function store(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'telepon' => 'required|numeric|min:10',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat User baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'password' => Hash::make($validated['password']),
            ]);

            // 2. Beri role 'super-admin-sekolah'
            $user->assignRole('super-admin-sekolah');

            // 3. Tautkan ke pondok_staff
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $this->getPondokId(),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat akun Super Admin Sekolah: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Gagal mendaftarkan admin: ' . $e->getMessage())
                             ->withInput();
        }
        
        return redirect()->route('adminpondok.manajemen-sekolah.index')
                         ->with('success', 'Akun Super Admin Sekolah berhasil ditambahkan.');
    }
    
    /**
     * Tampilkan form edit
     */
    public function edit(User $user)
    {
        $this->checkOwnership($user);
        return view('adminpondok.sekolah.edit', compact('user'));
    }

    /**
     * Update data akun
     */
    public function update(Request $request, User $user)
    {
        $this->checkOwnership($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id), // Abaikan email dia sendiri
            ],
            'telepon' => 'required|numeric|min:10',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->telepon = $validated['telepon'];

            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                             ->withInput();
        }

        return redirect()->route('adminpondok.manajemen-sekolah.index')
                         ->with('success', 'Data akun berhasil diperbarui.');
    }

    /**
     * Hapus akun
     */
    public function destroy(User $user)
    {
        $this->checkOwnership($user);

        try {
            // Menghapus User akan otomatis menghapus 'pondok_staff' (via cascade)
            $user->delete();

            return redirect()->route('adminpondok.manajemen-sekolah.index')
                             ->with('success', 'Akun Super Admin Sekolah berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('adminpondok.manajemen-sekolah.index')
                             ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}