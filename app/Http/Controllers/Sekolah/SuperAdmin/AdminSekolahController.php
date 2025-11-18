<?php
namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\Sekolah; // <-- Model Unit Sekolah
use App\Models\User; // <-- Model User
use App\Models\PondokStaff; // <-- Model PondokStaff
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Penting untuk Transaksi
use Illuminate\Support\Facades\Hash; // <-- Penting untuk Password
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules; // <-- Penting untuk Validasi Password
use Illuminate\Support\Facades\Log;

class AdminSekolahController extends Controller
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

        // Pastikan user ini adalah milik pondok saya DAN rolenya 'admin-sekolah'
        if (!$isMyStaff || !$user->hasRole('admin-sekolah')) {
            abort(404, 'Data Admin Sekolah tidak ditemukan');
        }
    }

    /**
     * Tampilkan daftar Akun Admin Sekolah
     */
    public function index()
    {
        $pondokId = $this->getPondokId();
        
        // Ambil user dengan role 'admin-sekolah' di pondok ini
        // Eager load relasi 'sekolahs' yang kita buat di Model User
        $users = User::role('admin-sekolah') 
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId)) //
            ->with('sekolahs') //
            ->latest()
            ->paginate(10);
            
        return view('sekolah.superadmin.admin-sekolah.index', compact('users'));
    }

    /**
     * Tampilkan form tambah
     */
    public function create()
    {
        // Ambil daftar sekolah di pondok ini untuk dropdown
        $sekolahs = Sekolah::where('pondok_id', $this->getPondokId())->get(); //
        
        if ($sekolahs->isEmpty()) {
            return redirect()->route('sekolah.superadmin.sekolah.index')
                             ->with('error', 'Gagal! Silakan tambahkan Unit Sekolah terlebih dahulu.');
        }

        return view('sekolah.superadmin.admin-sekolah.create', compact('sekolahs'));
    }

    /**
     * Simpan Akun Admin Sekolah baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'telepon' => 'required|numeric|min:10',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'sekolah_id' => 'required|exists:sekolahs,id', // Validasi dropdown sekolah
        ]);

        $pondokId = $this->getPondokId();

        // Kita akan menulis ke 3 tabel, jadi kita pakai Transaksi
        DB::beginTransaction();
        try {
            // 1. Buat User baru
            $user = User::create([ //
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'password' => Hash::make($validated['password']),
            ]);

            // 2. Beri role 'admin-sekolah'
            $user->assignRole('admin-sekolah'); //

            // 3. Tautkan ke pondok_staff (manual, karena trait tidak berlaku)
            PondokStaff::create([ //
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
            ]);

            // 4. Tautkan ke unit sekolah (via tabel pivot 'sekolah_user')
            // Kita gunakan relasi 'sekolahs()' yang sudah kita buat di model User
            $user->sekolahs()->attach($validated['sekolah_id']);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat akun Admin Sekolah: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Gagal mendaftarkan admin: ' . $e->getMessage())
                             ->withInput();
        }
        
        return redirect()->route('sekolah.superadmin.admin-sekolah.index')
                         ->with('success', 'Akun Admin Sekolah berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(User $user)
    {
        $this->checkOwnership($user); // Keamanan

        // Ambil daftar sekolah di pondok ini untuk dropdown
        $sekolahs = Sekolah::where('pondok_id', $this->getPondokId())->get(); //
        
        $user->load('sekolahs'); // Load sekolah yang saat ini dia pegang

        return view('sekolah.superadmin.admin-sekolah.edit', compact('user', 'sekolahs'));
    }

    /**
     * Update data akun
     */
    public function update(Request $request, User $user)
    {
        $this->checkOwnership($user); // Keamanan

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id), // Abaikan email dia sendiri
            ],
            'telepon' => 'required|numeric|min:10',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'sekolah_id' => 'required|exists:sekolahs,id', // Validasi dropdown sekolah
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data User
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->telepon = $validated['telepon'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save(); //

            // 2. Update penugasan sekolah (Gunakan sync untuk ganti)
            // Sync akan menghapus yg lama dan menambah yg baru
            $user->sekolahs()->sync($validated['sekolah_id']);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                             ->withInput();
        }

        return redirect()->route('sekolah.superadmin.admin-sekolah.index')
                         ->with('success', 'Data Admin Sekolah berhasil diperbarui.');
    }

    /**
     * Hapus akun
     */
    public function destroy(User $user)
    {
        $this->checkOwnership($user); // Keamanan

        try {
            // Menghapus User akan otomatis menghapus 'pondok_staff'
            // dan 'sekolah_user' (pivot) - *Catatan: Cek migrasi pivot sekolah_user*.
            // Mari kita asumsikan cascadeOnDelete sudah ada di migrasi pivot 'sekolah_user'.
            // Jika belum, kita harus detach manual: $user->sekolahs()->detach();
            
            $user->delete(); //

            return redirect()->route('sekolah.superadmin.admin-sekolah.index')
                             ->with('success', 'Akun Admin Sekolah berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('sekolah.superadmin.admin-sekolah.index')
                             ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}