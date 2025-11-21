<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\Ustadz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UstadzController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();

        $query = Ustadz::where('pondok_id', $pondokId)->with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
        }

        $ustadzs = $query->orderBy('nama_lengkap')->paginate(10);

        return view('pendidikan.admin.ustadz.index', compact('ustadzs'));
    }

    public function create()
    {
        return view('pendidikan.admin.ustadz.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Validasi Data Akun
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            
            // Validasi Data Profil
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'spesialisasi' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
        ]);

        $pondokId = $this->getPondokId();

        DB::transaction(function () use ($request, $pondokId) {
            // 1. Buat Akun User Login
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telepon' => $request->no_hp,
            ]);

            // 2. Berikan Role
            $user->assignRole('ustadz');

            // 3. [PENTING] Hubungkan ke Pondok (Ini yang sebelumnya kurang)
            \App\Models\PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
            ]);

            // 4. Buat Profil Ustadz
            Ustadz::create([
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
                'no_hp' => $request->no_hp,
                'spesialisasi' => $request->spesialisasi,
                'alamat' => $request->alamat,
                'is_active' => true,
            ]);
        });

        return redirect()->route('pendidikan.admin.ustadz.index')
            ->with('success', 'Data Ustadz dan Akun Login berhasil dibuat.');
    }

    public function edit(Ustadz $ustadz)
    {
        if ($ustadz->pondok_id != $this->getPondokId()) abort(403);
        
        return view('pendidikan.admin.ustadz.edit', compact('ustadz'));
    }

    public function update(Request $request, Ustadz $ustadz)
    {
        if ($ustadz->pondok_id != $this->getPondokId()) abort(403);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'spesialisasi' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'email' => ['required', 'email', Rule::unique('users')->ignore($ustadz->user_id)],
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($request, $ustadz) {
            // Update Profil
            $ustadz->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
                'no_hp' => $request->no_hp,
                'spesialisasi' => $request->spesialisasi,
                'alamat' => $request->alamat,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            // Update Akun User
            $user = $ustadz->user;
            $user->name = $request->nama_lengkap;
            $user->email = $request->email;
            $user->telepon = $request->no_hp;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();
        });

        return redirect()->route('pendidikan.admin.ustadz.index')
            ->with('success', 'Data Ustadz berhasil diperbarui.');
    }

    public function destroy(Ustadz $ustadz)
    {
        if ($ustadz->pondok_id != $this->getPondokId()) abort(403);

        DB::transaction(function () use ($ustadz) {
            $user = $ustadz->user;
            $ustadz->delete(); // Hapus profil
            if ($user) {
                $user->delete(); // Hapus akun login
            }
        });

        return redirect()->route('pendidikan.admin.ustadz.index')
            ->with('success', 'Data Ustadz berhasil dihapus.');
    }
}