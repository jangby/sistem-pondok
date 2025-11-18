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

class BendaharaController extends Controller
{
    /**
     * Dapatkan ID pondok admin yang sedang login
     */
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Helper untuk keamanan: Cek apakah user bendahara ini
     * milik pondok admin yang sedang login.
     */
    private function checkOwnership(User $bendaharaUser)
    {
        $isMyBendahara = $bendaharaUser->pondokStaff()
                            ->where('pondok_id', $this->getPondokId())
                            ->exists();

        if (!$isMyBendahara || !$bendaharaUser->hasRole('bendahara')) {
            abort(404, 'Data Bendahara tidak ditemukan');
        }
    }


    /**
     * Menampilkan daftar akun bendahara.
     */
    public function index()
    {
        // Ambil semua user yang punya role 'bendahara'
        // DAN terhubung ke pondok admin ini melalui 'pondokStaff'
        $bendaharaUsers = User::whereHas('roles', fn($q) => $q->where('name', 'bendahara'))
                            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $this->getPondokId()))
                            ->latest()
                            ->paginate(10);

        return view('adminpondok.bendahara.index', compact('bendaharaUsers'));
    }

    /**
     * Menampilkan form tambah akun bendahara.
     */
    public function create()
    {
        return view('adminpondok.bendahara.create');
    }

    /**
     * Menyimpan akun bendahara baru.
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
            // 1. Buat User baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 2. Beri role 'bendahara'
            $user->assignRole('bendahara');

            // 3. Tautkan ke pondok admin ini
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $this->getPondokId(),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat akun bendahara: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Gagal mendaftarkan bendahara: ' . $e->getMessage())
                             ->withInput();
        }

        return redirect()->route('adminpondok.bendahara.index')
                         ->with('success', 'Akun Bendahara berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit akun bendahara.
     */
    public function edit($id)
    {
        $bendaharaUser = User::findOrFail($id);
        $this->checkOwnership($bendaharaUser); // Keamanan

        return view('adminpondok.bendahara.edit', compact('bendaharaUser'));
    }

    /**
     * Update data akun bendahara.
     */
    public function update(Request $request, $id)
    {
        $bendaharaUser = User::findOrFail($id);
        $this->checkOwnership($bendaharaUser); // Keamanan

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($bendaharaUser->id), // Abaikan email dia sendiri
            ],
            'password' => 'nullable|string|min:8|confirmed', // Password opsional
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data user
            $bendaharaUser->name = $validated['name'];
            $bendaharaUser->email = $validated['email'];

            // 2. Hanya update password jika diisi
            if ($request->filled('password')) {
                $bendaharaUser->password = Hash::make($validated['password']);
            }

            $bendaharaUser->save();

            // 3. Data PondokStaff tidak perlu di-update (sudah benar)

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                             ->withInput();
        }

        return redirect()->route('adminpondok.bendahara.index')
                         ->with('success', 'Data Bendahara berhasil diperbarui.');
    }

    /**
     * Hapus akun bendahara.
     */
    public function destroy($id)
    {
        $bendaharaUser = User::findOrFail($id);
        $this->checkOwnership($bendaharaUser); // Keamanan

        try {
            // Migrasi 'pondok_staff' kita sudah 'cascadeOnDelete',
            // jadi kita hanya perlu menghapus User-nya.
            $bendaharaUser->delete();

            return redirect()->route('adminpondok.bendahara.index')
                             ->with('success', 'Akun Bendahara berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('adminpondok.bendahara.index')
                             ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}