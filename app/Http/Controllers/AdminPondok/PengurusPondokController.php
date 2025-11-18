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

class PengurusPondokController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    public function index()
    {
        // Ambil user dengan role 'pengurus_pondok' di pondok ini
        $pengurus = User::role('pengurus_pondok')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $this->getPondokId()))
            ->latest()
            ->paginate(10);

        return view('adminpondok.pengurus.index', compact('pengurus'));
    }

    public function create()
    {
        return view('adminpondok.pengurus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 2. Assign Role
            $user->assignRole('pengurus_pondok');

            // 3. Link ke Pondok
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $this->getPondokId(),
            ]);

            DB::commit();
            return redirect()->route('adminpondok.pengurus.index')->with('success', 'Akun Pengurus berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

    public function edit(User $pengurus)
    {
        // Keamanan: Pastikan dia milik pondok ini
        $isMine = $pengurus->pondokStaff()->where('pondok_id', $this->getPondokId())->exists();
        if (!$isMine || !$pengurus->hasRole('pengurus_pondok')) abort(404);

        return view('adminpondok.pengurus.edit', compact('pengurus'));
    }

    public function update(Request $request, User $pengurus)
    {
        // Keamanan
        $isMine = $pengurus->pondokStaff()->where('pondok_id', $this->getPondokId())->exists();
        if (!$isMine) abort(404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($pengurus->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $pengurus->name = $validated['name'];
        $pengurus->email = $validated['email'];
        if ($request->filled('password')) {
            $pengurus->password = Hash::make($validated['password']);
        }
        $pengurus->save();

        return redirect()->route('adminpondok.pengurus.index')->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(User $pengurus)
    {
        // Keamanan
        $isMine = $pengurus->pondokStaff()->where('pondok_id', $this->getPondokId())->exists();
        if (!$isMine) abort(404);

        $pengurus->delete(); // PondokStaff akan terhapus otomatis via cascade

        return redirect()->route('adminpondok.pengurus.index')->with('success', 'Akun pengurus berhasil dihapus.');
    }
}