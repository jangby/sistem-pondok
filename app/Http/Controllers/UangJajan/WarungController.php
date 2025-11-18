<?php

namespace App\Http\Controllers\UangJajan;

use App\Http\Controllers\Controller;
use App\Models\Warung;
use App\Models\User;
use App\Models\PondokStaff; // Untuk menghubungkan user ke pondok
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class WarungController extends Controller
{
    private function getPondokId()
    {
        // Admin Uang Jajan login, ambil pondok_id-nya
        return Auth::user()->pondokStaff->pondok_id;
    }

    private function checkOwnership(Warung $warung)
    {
        if ($warung->pondok_id != $this->getPondokId()) {
            abort(404);
        }
    }

    public function index()
    {
        // Trait di Model Warung otomatis memfilter
        $warungs = Warung::with('user')->latest()->paginate(10);
        return view('uuj-admin.warung.index', compact('warungs'));
    }

    public function create()
    {
        return view('uuj-admin.warung.create');
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama_warung' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat User baru
            $user = User::create([
                'name' => $validated['nama_warung'], // Nama user = nama warung
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 2. Beri role 'pos_warung'
            $user->assignRole('pos_warung');

            // 3. Tautkan User ke Pondok (wajib agar middleware 'cek.langganan' lolos)
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
            ]);

            // 4. Buat Warung (Trait BelongsToPondok otomatis isi pondok_id)
            Warung::create([
                'pondok_id' => $pondokId,
                'user_id' => $user->id,
                'nama_warung' => $validated['nama_warung'],
                'status' => 'active',
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat Warung: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mendaftarkan warung: ' . $e->getMessage());
        }

        return redirect()->route('uuj-admin.warung.index')
                         ->with('success', 'Akun Warung/POS berhasil ditambahkan.');
    }

    public function edit(Warung $warung)
    {
        $this->checkOwnership($warung);
        $warung->load('user'); // Ambil data user-nya
        return view('uuj-admin.warung.edit', compact('warung'));
    }

    public function update(Request $request, Warung $warung)
    {
        $this->checkOwnership($warung);
        $user = $warung->user;

        $validated = $request->validate([
            'nama_warung' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Warung
            $warung->update([
                'nama_warung' => $validated['nama_warung'],
                'status' => $validated['status'],
            ]);

            // 2. Update User
            $user->name = $validated['nama_warung'];
            $user->email = $validated['email'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }

        return redirect()->route('uuj-admin.warung.index')
                         ->with('success', 'Data Warung/POS berhasil diperbarui.');
    }

    public function destroy(Warung $warung)
    {
        $this->checkOwnership($warung);
        try {
            DB::beginTransaction();
            $user = $warung->user;
            $warung->delete(); // Hapus warung
            $user->delete();   // Hapus akun login user (PondokStaff akan ter-delete otomatis)
            DB::commit();

            return redirect()->route('uuj-admin.warung.index')
                             ->with('success', 'Akun Warung/POS berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('uuj-admin.warung.index')
                             ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}