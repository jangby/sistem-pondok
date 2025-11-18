<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Halaman Utama (Menu Pilihan Absensi)
     */
    public function index()
    {
        return view('pengurus.absensi.index');
    }

    // --- SUB MENU ---

    public function gerbang()
    {
        // Nanti di sini logika Absensi Gerbang
        return view('pengurus.absensi.gerbang.index');
    }

    public function asrama()
    {
        // Nanti di sini logika Absensi Asrama
        return view('pengurus.absensi.asrama.index');
    }

    public function kegiatan()
    {
        // Nanti di sini logika Absensi Kegiatan
        return view('pengurus.absensi.kegiatan.index');
    }

    public function jamaah()
    {
        // Nanti di sini logika Absensi Berjamaah
        return view('pengurus.absensi.jamaah.index');
    }

    public function kontrol()
    {
        // Nanti di sini logika Kontrol Kehadiran
        return view('pengurus.absensi.kontrol.index');
    }
}