<?php

namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\Sekolah;
use App\Models\Sekolah\Guru; // Model Profil Guru
use App\Models\User;
use App\Models\PondokStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    // === HELPER FUNCTIONS ===

    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }
    
    private function checkOwnership(User $user)
    {
        $isMyStaff = $user->pondokStaff()
                            ->where('pondok_id', $this->getPondokId())
                            ->exists();

        if (!$isMyStaff || !$user->hasRole('guru')) {
            abort(404, 'Data Guru tidak ditemukan');
        }
    }

    // === CRUD METHODS ===

    /**
     * Tampilkan daftar Akun Guru
     */
    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // 1. Ambil List Sekolah (Untuk Dropdown di Modal)
        $sekolahs = Sekolah::where('pondok_id', $pondokId)->orderBy('nama_sekolah')->get();
        
        // 2. Query User Guru
        $query = User::role('guru')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
            ->with(['guru', 'sekolahs']);

        // 3. Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('guru', function($q2) use ($search) {
                      $q2->where('nip', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
            
        return view('sekolah.superadmin.guru.index', compact('users', 'sekolahs'));
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            // Validasi User
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // Validasi Profil Guru
            'nip' => ['nullable', 'string', 'max:255', Rule::unique('gurus')->where(fn ($q) => $q->where('pondok_id', $pondokId))],
            'telepon' => 'required|numeric|min:10', 
            'alamat' => 'nullable|string',
            'tipe_jam_kerja' => 'required|in:full_time,flexi',
            
            // PERBAIKAN: Validasi RFID (Unique Global)
            'rfid_uid' => 'nullable|string|unique:gurus,rfid_uid',

            // Validasi Penugasan (Multi-Select)
            'sekolah_ids' => 'required|array|min:1',
            'sekolah_ids.*' => 'exists:sekolahs,id',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat User baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telepon' => $request->telepon, // Simpan telepon juga di tabel users
            ]);

            // 2. Beri role 'guru'
            $user->assignRole('guru');

            // 3. Tautkan ke pondok_staff (manual)
            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
            ]);

            // 4. Buat Profil Guru (tabel gurus)
            Guru::create([
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
                'nip' => $request->nip,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'tipe_jam_kerja' => $request->tipe_jam_kerja,
                'rfid_uid' => $request->rfid_uid, // <--- PERBAIKAN: Simpan RFID
            ]);

            // 5. Tautkan ke unit sekolah (pivot 'sekolah_user')
            $user->sekolahs()->attach($validated['sekolah_ids']);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat akun Guru: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Gagal mendaftarkan guru: ' . $e->getMessage())
                             ->withInput();
        }
        
        return redirect()->route('sekolah.superadmin.guru.index')
                         ->with('success', 'Akun Guru berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $this->checkOwnership($user); // Keamanan
        $pondokId = $this->getPondokId();
        
        // PERBAIKAN: Ambil profil guru terkait user ini (bisa null jika data lama rusak)
        $guruProfile = $user->guru; 
        $guruId = $guruProfile ? $guruProfile->id : null;

        $validated = $request->validate([
            // Validasi User
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            
            // Validasi Profil Guru
            'nip' => ['nullable', 'string', 'max:255', Rule::unique('gurus')->where(fn ($q) => $q->where('pondok_id', $pondokId))->ignore($guruId)],
            'telepon' => 'required|numeric|min:10',
            'alamat' => 'nullable|string',
            'tipe_jam_kerja' => 'required|in:full_time,flexi',
            
            // PERBAIKAN: Validasi RFID (Ignore diri sendiri)
            'rfid_uid' => ['nullable', 'string', Rule::unique('gurus', 'rfid_uid')->ignore($guruId)],

            // Validasi Penugasan (Multi-Select)
            'sekolah_ids' => 'required|array|min:1',
            'sekolah_ids.*' => 'exists:sekolahs,id',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data User
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            $user->telepon = $request->telepon; 
            $user->save();

            // 2. Update atau Buat Profil Guru
            Guru::updateOrCreate(
                ['user_id' => $user->id], 
                [ 
                    'pondok_id' => $pondokId,
                    'nip' => $request->nip,
                    'telepon' => $request->telepon,
                    'alamat' => $request->alamat,
                    'tipe_jam_kerja' => $request->tipe_jam_kerja,
                    'rfid_uid' => $request->rfid_uid, // <--- PERBAIKAN: Update RFID
                ]
            );

            // 3. Update penugasan sekolah (Sync)
            $user->sekolahs()->sync($validated['sekolah_ids']);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                             ->withInput();
        }

        return redirect()->route('sekolah.superadmin.guru.index')
                         ->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->checkOwnership($user); // Keamanan

        try {
            $user->delete();
            return redirect()->route('sekolah.superadmin.guru.index')
                             ->with('success', 'Akun Guru berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('sekolah.superadmin.guru.index')
                             ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}