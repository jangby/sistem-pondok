<?php
namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\Sekolah;
use App\Models\Sekolah\Guru; // <-- Model Profil Guru
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
    // Helper untuk mengambil Pondok ID
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id; //
    }
    
    // Helper untuk Keamanan
    private function checkOwnership(User $user)
    {
        $isMyStaff = $user->pondokStaff()
                            ->where('pondok_id', $this->getPondokId())
                            ->exists(); //

        // Pastikan user ini adalah milik pondok saya DAN rolenya 'guru'
        if (!$isMyStaff || !$user->hasRole('guru')) {
            abort(404, 'Data Guru tidak ditemukan');
        }
    }

    /**
     * Tampilkan daftar Akun Guru
     */
    public function index()
    {
        $pondokId = $this->getPondokId();
        
        // Ambil user dengan role 'guru' di pondok ini
        // Eager load relasi 'guru' (profil) dan 'sekolahs' (penugasan)
        $users = User::role('guru')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId)) //
            ->with(['guru', 'sekolahs']) //
            ->latest()
            ->paginate(10);
            
        return view('sekolah.superadmin.guru.index', compact('users'));
    }

    /**
     * Tampilkan form tambah guru
     */
    public function create()
    {
        $sekolahs = Sekolah::where('pondok_id', $this->getPondokId())->get(); //
        
        if ($sekolahs->isEmpty()) {
            return redirect()->route('sekolah.superadmin.sekolah.index')
                             ->with('error', 'Gagal! Silakan tambahkan Unit Sekolah terlebih dahulu.');
        }

        return view('sekolah.superadmin.guru.create', compact('sekolahs'));
    }

    /**
     * Simpan Akun Guru baru
     */
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

            // Validasi Penugasan
            'sekolah_ids' => 'required|array|min:1', // Harus array dan minimal pilih 1
            'sekolah_ids.*' => 'exists:sekolahs,id',
        ]);

        // Kita akan menulis ke 4 tabel, WAJIB Transaksi
        DB::beginTransaction();
        try {
            // 1. Buat User baru
            $user = User::create([ //
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 2. Beri role 'guru'
            $user->assignRole('guru'); //

            // 3. Tautkan ke pondok_staff (manual)
            PondokStaff::create([ //
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
            ]);

            // 4. Buat Profil Guru (tabel gurus)
            Guru::create([ //
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
                'nip' => $request->nip,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
            ]);

            // 5. Tautkan ke unit sekolah (pivot 'sekolah_user')
            $user->sekolahs()->attach($validated['sekolah_ids']); //

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

    /**
     * Tampilkan form edit
     */
    public function edit(User $user)
    {
        $this->checkOwnership($user); // Keamanan

        // Ambil data untuk dropdown
        $sekolahs = Sekolah::where('pondok_id', $this->getPondokId())->get(); //
        
        // Load relasi profil guru & penugasan sekolah
        $user->load(['guru', 'sekolahs']); //

        return view('sekolah.superadmin.guru.edit', compact('user', 'sekolahs'));
    }

    /**
     * Update data akun guru
     */
    public function update(Request $request, User $user)
    {
        $this->checkOwnership($user); // Keamanan
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            // Validasi User
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            
            // Validasi Profil Guru
            'nip' => ['nullable', 'string', 'max:255', Rule::unique('gurus')->where(fn ($q) => $q->where('pondok_id', $pondokId))->ignore($user->guru->id ?? null)],
            'telepon' => 'required|numeric|min:10',
            'alamat' => 'nullable|string',

            // Validasi Penugasan (sekarang bisa array)
            'sekolah_ids' => 'required|array',
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
            $user->save(); //

            // 2. Update atau Buat Profil Guru
            Guru::updateOrCreate( //
                ['user_id' => $user->id], // Cari berdasarkan user_id
                [ // Update/Buat dengan data ini
                    'pondok_id' => $pondokId,
                    'nip' => $request->nip,
                    'telepon' => $request->telepon,
                    'alamat' => $request->alamat,
                ]
            );

            // 3. Update penugasan sekolah (Sync)
            $user->sekolahs()->sync($validated['sekolah_ids']); //

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

    /**
     * Hapus akun guru
     */
    public function destroy(User $user)
    {
        $this->checkOwnership($user); // Keamanan

        try {
            // Menghapus User akan otomatis menghapus:
            // 1. Relasi pondok_staff (via cascade)
            // 2. Profil guru (via cascade)
            // 3. Relasi sekolah_user (via cascade)
            $user->delete(); //

            return redirect()->route('sekolah.superadmin.guru.index')
                             ->with('success', 'Akun Guru berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('sekolah.superadmin.guru.index')
                             ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}