<?php

namespace App\Http\Controllers\Sekolah\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComputerLog; 
use Carbon\Carbon;

class ComputerLabController extends Controller
{
    /**
     * Dashboard Utama (Launcher)
     */
    public function dashboard()
    {
        $totalKomputer = ComputerLog::count();
        // Dianggap online jika last_seen < 2 menit yang lalu
        $komputerOnline = ComputerLog::where('last_seen', '>=', now()->subMinutes(2))->count();
        $komputerOffline = $totalKomputer - $komputerOnline;

        // Ambil 5 aktivitas terakhir (log sederhana dari update terakhir)
        $recentActivities = ComputerLog::whereNotNull('last_seen')
                            ->orderBy('last_seen', 'desc')
                            ->take(5)
                            ->get();

        return view('sekolah.petugas.lab-komputer.dashboard', compact('totalKomputer', 'komputerOnline', 'komputerOffline', 'recentActivities'));
    }

    /**
     * Menu 1: Daftar Komputer & Monitoring Real-time
     */
    public function listKomputer()
    {
        $computers = ComputerLog::orderBy('pc_name', 'asc')->get();
        return view('sekolah.petugas.lab-komputer.list', compact('computers'));
    }

    /**
     * Menu 2: Matikan Semua Komputer (Shutdown All)
     */
    public function shutdownAll()
    {
        // Hanya kirim perintah ke komputer yang sedang Online
        $onlinePCs = ComputerLog::where('last_seen', '>=', now()->subMinutes(2))->update([
            'pending_command' => 'shutdown'
        ]);

        return back()->with('success', "Perintah SHUTDOWN dikirim ke $onlinePCs komputer yang aktif.");
    }

    /**
     * Menu 3: Refresh / Ping Status
     * (Sebenarnya status update otomatis via Python, tapi ini untuk force check di DB)
     */
    public function refreshStatus()
    {
        // Kita hanya redirect back, karena update status dilakukan oleh script Python di client
        return back()->with('success', 'Data status komputer berhasil diperbarui.');
    }

    /**
     * Menu 4: Ganti Password Massal (Form)
     */
    public function massPasswordForm()
    {
        return view('sekolah.petugas.lab-komputer.password');
    }

    /**
     * Menu 4: Proses Ganti Password Massal
     */
    public function massPasswordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
        ]);

        // Update password di database untuk SEMUA komputer
        // Script Python nanti akan mengambil password ini saat cek server
        ComputerLog::query()->update(['password' => $request->password]);

        return redirect()->route('petugas-lab.dashboard')->with('success', 'Password SEMUA komputer berhasil diubah. Script Client akan segera sinkronisasi.');
    }

    /**
     * Menu 5: Jadwal Lab
     */
    public function jadwal()
    {
        // Di sini Anda bisa menghubungkan dengan Model JadwalPelajaran jika sudah siap.
        // Untuk sekarang kita tampilkan view jadwal statis/dummy yang rapi.
        return view('sekolah.petugas.lab-komputer.jadwal');
    }

    /**
     * Menu 6: Laporan Harian
     */
    public function laporan()
    {
        return view('sekolah.petugas.lab-komputer.laporan');
    }
}