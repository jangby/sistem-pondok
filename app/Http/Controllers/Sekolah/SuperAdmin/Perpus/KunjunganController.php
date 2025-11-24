<?php

namespace App\Http\Controllers\Sekolah\SuperAdmin\Perpus;

use App\Http\Controllers\Controller;
use App\Models\Perpus\Kunjungan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    // Halaman History Kunjungan (Admin View)
    public function index()
    {
        $kunjungans = Kunjungan::where('pondok_id', $this->getPondokId())
            ->with(['santri', 'santri.kelas'])
            ->latest()
            ->paginate(20);

        return view('sekolah.superadmin.perpus.kunjungan.index', compact('kunjungans'));
    }

    // Halaman Kiosk (Layar Scan)
    public function kiosk()
    {
        $todayVisits = Kunjungan::where('pondok_id', $this->getPondokId())
            ->whereDate('waktu_berkunjung', Carbon::today())
            ->with('santri.kelas')
            ->latest()
            ->take(5)
            ->get();

        return view('sekolah.superadmin.perpus.kunjungan.kiosk', compact('todayVisits'));
    }

    // Proses Scan
    public function store(Request $request)
    {
        $request->validate([
            'kode_santri' => 'required|string',
        ]);

        $pondokId = $this->getPondokId();
        $kode = $request->kode_santri;

        // --- PERBAIKAN DI SINI ---
        // Menggunakan nama kolom yang benar: qrcode_token dan rfid_uid
        $santri = Santri::where('pondok_id', $pondokId)
            ->where(function($q) use ($kode) {
                $q->where('nis', $kode)
                  ->orWhere('qrcode_token', $kode)  // SEBELUMNYA: qrcode
                  ->orWhere('rfid_uid', $kode);     // SEBELUMNYA: rfid
            })->first();

        if (!$santri) {
            return back()->with('error', 'Data Santri tidak ditemukan! (Kode: '.$kode.')');
        }

        // Cek Spam (Mencegah scan berkali-kali dalam 1 menit)
        $lastVisit = Kunjungan::where('santri_id', $santri->id)
            ->where('waktu_berkunjung', '>', Carbon::now()->subMinute())
            ->first();

        if ($lastVisit) {
            return back()->with('warning', 'Halo ' . $santri->full_name . ', kehadiranmu sudah tercatat barusan.');
        }

        // Simpan Kunjungan
        Kunjungan::create([
            'pondok_id' => $pondokId,
            'santri_id' => $santri->id,
            'user_id' => Auth::id(),
            'waktu_berkunjung' => Carbon::now(),
            'keperluan' => $request->input('keperluan', 'Membaca'),
        ]);

        return back()->with('success', 'Selamat Datang, ' . $santri->full_name . ' (' . ($santri->kelas->nama_kelas ?? '-') . ')');
    }
}