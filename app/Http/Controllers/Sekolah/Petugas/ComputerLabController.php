<?php

namespace App\Http\Controllers\Sekolah\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComputerLog; 
use Carbon\Carbon;

class ComputerLabController extends Controller
{
    /**
     * Dashboard Utama (Mobile Friendly UI)
     */
    public function dashboard()
    {
        // Statistik
        $totalKomputer = ComputerLog::count();
        
        // Dianggap online jika last_seen < 2 menit yang lalu
        $komputerOnline = ComputerLog::where('last_seen', '>=', now()->subMinutes(2))->count();
        $komputerOffline = $totalKomputer - $komputerOnline;

        // Ambil 5 aktivitas terakhir (Komputer yang baru saja connect/update status)
        $recentActivities = ComputerLog::whereNotNull('last_seen')
                            ->orderBy('last_seen', 'desc')
                            ->take(10) // Ambil 10 biar listnya agak panjang di HP
                            ->get();

        return view('sekolah.petugas.lab-komputer.dashboard', compact(
            'totalKomputer', 
            'komputerOnline', 
            'komputerOffline', 
            'recentActivities'
        ));
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
        $affected = ComputerLog::where('last_seen', '>=', now()->subMinutes(2))->update([
            'pending_command' => 'shutdown'
        ]);

        if($affected == 0) {
            return back()->with('error', "Tidak ada komputer online yang bisa dimatikan.");
        }

        return back()->with('success', "Perintah SHUTDOWN berhasil dikirim ke $affected komputer yang aktif.");
    }

    /**
     * Menu 3: Refresh / Ping Status
     */
    public function refreshStatus()
    {
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
        ], [
            'password.min' => 'Password minimal 6 karakter'
        ]);

        ComputerLog::query()->update(['password' => $request->password]);

        return redirect()->route('petugas-lab.dashboard')->with('success', 'Password SEMUA komputer berhasil diubah.');
    }

    /**
     * Mengirim perintah (Shutdown/Logout) untuk SATU komputer spesifik
     */
    public function sendCommand(Request $request, $id)
    {
        $computer = ComputerLog::findOrFail($id);

        $request->validate([
            'command' => 'required|in:shutdown,logout,restart',
        ]);

        // Simpan perintah ke database
        $computer->update([
            'pending_command' => $request->command
        ]);

        $aksi = $request->command == 'shutdown' ? 'dimatikan' : ($request->command == 'restart' ? 'direstart' : 'dilogout');
        
        return back()->with('success', "Perintah terkirim! PC {$computer->pc_name} akan segera $aksi.");
    }

    // ... method jadwal dan laporan biarkan seperti sebelumnya atau kembangkan nanti
    public function jadwal() { return view('sekolah.petugas.lab-komputer.jadwal'); }
    public function laporan() { return view('sekolah.petugas.lab-komputer.laporan'); }
}