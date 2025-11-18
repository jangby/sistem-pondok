<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// Import library Image
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; 

class PayoutController extends Controller
{
    /**
     * Tampilkan daftar permintaan penarikan.
     */
    public function index()
    {
        $payouts = Payout::with('pondok', 'adminRequest') // Ambil relasi
                    ->latest('requested_at')
                    ->paginate(15);

        return view('superadmin.payouts.index', compact('payouts'));
    }

    /**
     * Tampilkan halaman detail/konfirmasi.
     */
    public function show(Payout $payout)
    {
        // Muat relasi untuk ditampilkan
        $payout->load('pondok', 'adminRequest');
        
        return view('superadmin.payouts.show', compact('payout'));
    }

    /**
     * Konfirmasi pembayaran (transfer) dari Super Admin.
     */
    public function confirm(Request $request, Payout $payout)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'catatan_superadmin' => 'required|string|max:255',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pathForDatabase = null; // Variabel untuk menyimpan path ke DB

        // 2. Proses Upload & Kompres Gambar (Cara Baru)
        if ($request->hasFile('bukti_pembayaran')) {
            try {
                $file = $request->file('bukti_pembayaran');
                
                // Buat nama file unik
                $filename = $payout->id . '-' . time() . '.jpg'; // Kita paksa .jpg
                
                // Tentukan folder tujuan (di DALAM folder 'public')
                $publicPath = 'uploads/bukti-payout/';
                $destinationPath = public_path($publicPath); // C:\laragon\www\proyek\public\uploads\bukti-payout

                // Buat folder jika belum ada
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Load gambar, kompres, dan simpan
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $image = $manager->read($file);
                $image->resize(width: 800);
                
                // Simpan langsung ke folder public
                $image->toJpg(75)->save($destinationPath . $filename);

                // Path untuk database adalah path relatif di dalam 'public'
                $pathForDatabase = $publicPath . $filename; 

            } catch (\Exception $e) {
                \Log::error('Gagal upload gambar: ' . $e->getMessage()); // Tambah log
                return redirect()->back()->with('error', 'Gagal mengupload gambar: ' . $e->getMessage());
            }
        }

        // 3. Update status Payout
        $payout->status = 'completed';
        $payout->superadmin_id_approve = Auth::id();
        $payout->completed_at = now();
        $payout->catatan_superadmin = $validated['catatan_superadmin'];
        $payout->bukti_transfer_url = $pathForDatabase; // <-- Gunakan path baru
        $payout->save();

        return redirect()->route('superadmin.payouts.index')
                         ->with('success', 'Pembayaran Payout telah berhasil dikonfirmasi.');
    }
}