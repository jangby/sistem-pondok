<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PondokStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; // Import Log

class AdminUangJajanController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    // Helper untuk keamanan
    private function checkOwnership(User $adminUjUser)
    {
        $isMyStaff = $adminUjUser->pondokStaff()
                            ->where('pondok_id', $this->getPondokId())
                            ->exists();

        if (!$isMyStaff || !$adminUjUser->hasRole('admin_uang_jajan')) {
            abort(404, 'Data Admin tidak ditemukan');
        }
    }

    /**
     * Menampilkan daftar akun 'admin_uang_jajan'.
     */
    public function index()
    {
        $adminUjUsers = User::whereHas('roles', fn($q) => $q->where('name', 'admin_uang_jajan'))
                            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $this->getPondokId()))
                            ->latest()
                            ->paginate(10);

        return view('adminpondok.uuj.admin.index', compact('adminUjUsers'));
    }

    /**
     * Menampilkan form tambah akun.
     */
    public function create()
    {
        return view('adminpondok.uuj.admin.create');
    }

    /**
     * Menyimpan akun baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
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

            // Beri role 'admin_uang_jajan'
            $user->assignRole('admin_uang_jajan');

            // Tautkan ke pondok
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $this->getPondokId(),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat akun Admin UJ: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Gagal mendaftarkan admin: ' . $e->getMessage())
                             ->withInput();
        }

        return redirect()->route('adminpondok.uuj.admin.index')
                         ->with('success', 'Akun Admin Uang Jajan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit($id)
    {
        $adminUjUser = User::findOrFail($id);
        $this->checkOwnership($adminUjUser); // Keamanan

        return view('adminpondok.uuj.admin.edit', compact('adminUjUser'));
    }

    /**
     * Update data akun.
     */
    public function update(Request $request, $id)
    {
        $adminUjUser = User::findOrFail($id);
        $this->checkOwnership($adminUjUser); // Keamanan

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($adminUjUser->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $adminUjUser->name = $validated['name'];
            $adminUjUser->email = $validated['email'];

            if ($request->filled('password')) {
                $adminUjUser->password = Hash::make($validated['password']);
            }

            $adminUjUser->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                             ->withInput();
        }

        return redirect()->route('adminpondok.uuj.admin.index')
                         ->with('success', 'Data Admin Uang Jajan berhasil diperbarui.');
    }

    /**
     * Hapus akun.
     */
    public function destroy($id)
    {
        $adminUjUser = User::findOrFail($id);
        $this->checkOwnership($adminUjUser); // Keamanan

        try {
            // Menghapus User akan otomatis menghapus 'pondok_staff' (via cascade)
            $adminUjUser->delete();

            return redirect()->route('adminpondok.uuj.admin.index')
                             ->with('success', 'Akun Admin Uang Jajan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('adminpondok.uuj.admin.index')
                             ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}