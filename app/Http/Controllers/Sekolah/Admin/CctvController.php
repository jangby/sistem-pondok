<?php

namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CctvController extends Controller
{
    public function index()
    {
        // Data list CCTV (Nantinya bisa diganti dengan query dari database)
        // URL di bawah adalah contoh jika Anda menggunakan Streaming Server (seperti MediaMTX)
        // atau direct IP jika menggunakan IP Public/Lokal langsung.
        $cctvs = [
            [
                'nama' => 'Gerbang Utama',
                'stream_url' => 'https://shinobi2.garut.go.id/98I6Eeck02WmADcx62PRKi5mA56ekC/mp4/garut/simpanglimbangan/s.mp4', 
                'status' => 'Online'
            ],
            [
                'nama' => 'Halaman Sekolah',
                'stream_url' => 'https://shinobi.garutkab.go.id/DiDBfpnNM5aXISsEM5ZD4nrdEC8tav/mp4/garut/suci/s.mp4',
                'status' => 'Online'
            ],
            [
                'nama' => 'Koridor Kelas',
                'stream_url' => 'https://shinobi2.garut.go.id/98I6Eeck02WmADcx62PRKi5mA56ekC/mp4/garut/pasarlewo/s.mp4',
                'status' => 'Online'
            ],
        ];

        return view('sekolah.admin.cctv.index', compact('cctvs'));
    }
}