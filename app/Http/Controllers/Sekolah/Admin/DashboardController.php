<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\MataPelajaran;
use App\Models\Sekolah\TahunAjaran;

class DashboardController extends Controller
{
    /**
     * Helper untuk mengambil data sekolah yang dikelola admin ini
     */
    private function getSekolah()
    {
        // Ambil user admin yang sedang login
        $adminUser = Auth::user();
        
        // Ambil sekolah PERTAMA yang ditugaskan padanya
        // Kita asumsikan 1 Admin Sekolah = 1 Unit Sekolah
        $sekolah = $adminUser->sekolahs()->first(); //
        
        if (!$sekolah) {
            // Jika admin ini belum ditugaskan, lempar error
            abort(403, 'Akun Anda tidak ditugaskan ke unit sekolah manapun.');
        }
        return $sekolah;
    }

    /**
     * Helper untuk mengambil ID pondok
     */
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id; //
    }

    public function index()
    {
        $sekolah = $this->getSekolah();
        $pondokId = $this->getPondokId();

        // 1. Hitung jumlah Mata Pelajaran di sekolah ini
        $jumlahMapel = MataPelajaran::where('sekolah_id', $sekolah->id)->count(); //

        // 2. Hitung jumlah Guru yang ditugaskan di sekolah ini
        // Kita hitung dari tabel pivot 'sekolah_user'
        $jumlahGuru = $sekolah->users()->whereHas('roles', fn($q) => $q->where('name', 'guru'))->count(); //

        // 3. Ambil Tahun Ajaran yang sedang aktif di pondok ini
        $tahunAjaranAktif = TahunAjaran::where('pondok_id', $pondokId)
                                    ->where('is_active', true)
                                    ->first(); //
        
        return view('sekolah.admin.dashboard', compact(
            'sekolah',
            'jumlahMapel',
            'jumlahGuru',
            'tahunAjaranAktif'
        ));
    }
}