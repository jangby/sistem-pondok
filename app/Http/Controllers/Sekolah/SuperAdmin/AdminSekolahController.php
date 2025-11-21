<?php
namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\Sekolah;
use App\Models\User;
use App\Models\PondokStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class AdminSekolahController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }
    
    private function checkOwnership(User $user)
    {
        $isMyStaff = $user->pondokStaff()
                            ->where('pondok_id', $this->getPondokId())
                            ->exists();

        if (!$isMyStaff || !$user->hasRole('admin-sekolah')) {
            abort(404, 'Data Admin Sekolah tidak ditemukan');
        }
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // 1. Ambil List Sekolah (Untuk Dropdown di Modal)
        $sekolahs = Sekolah::where('pondok_id', $pondokId)->orderBy('nama_sekolah')->get();

        // 2. Query Users (Admin Sekolah)
        $query = User::role('admin-sekolah') 
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
            ->with('sekolahs');

        // 3. Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
            
        $users = $query->latest()->paginate(10)->withQueryString();
            
        return view('sekolah.superadmin.admin-sekolah.index', compact('users', 'sekolahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'telepon' => 'required|numeric|min:10',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'sekolah_id' => 'required|exists:sekolahs,id',
        ]);

        $pondokId = $this->getPondokId();

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole('admin-sekolah');

            PondokStaff::create([
                'user_id' => $user->id,
                'pondok_id' => $pondokId,
            ]);

            $user->sekolahs()->attach($validated['sekolah_id']);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mendaftarkan admin: ' . $e->getMessage());
        }
        
        return redirect()->route('sekolah.superadmin.admin-sekolah.index')
                         ->with('success', 'Akun Admin Sekolah berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        // Note: Route model binding $user disini akan error jika nama parameternya beda di route resource.
        // Pastikan route resource menggunakan {admin_sekolah} atau sesuaikan nama variabel.
        // Asumsi standar resource: update(Request $request, $id) lalu find manual lebih aman jika ragu.
        // Tapi jika Anda yakin, biarkan User $user. 
        
        // KOREKSI KEAMANAN:
        // Pastikan user yang diupdate adalah admin sekolah milik pondok ini
        $this->checkOwnership($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telepon' => 'required|numeric|min:10',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'sekolah_id' => 'required|exists:sekolahs,id',
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

            $user->sekolahs()->sync($validated['sekolah_id']);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }

        return redirect()->route('sekolah.superadmin.admin-sekolah.index')
                         ->with('success', 'Data Admin Sekolah berhasil diperbarui.');
    }

    public function destroy(User $user) // Sesuaikan nama parameter dengan route:list jika perlu
    {
        $this->checkOwnership($user);

        try {
            $user->delete();
            return redirect()->route('sekolah.superadmin.admin-sekolah.index')
                             ->with('success', 'Akun Admin Sekolah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('sekolah.superadmin.admin-sekolah.index')
                             ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}