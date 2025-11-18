<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;

class TunggakanController extends Controller
{
    public function index(Request $request)
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;

        // Query Dasar: Ambil santri di pondok ini yang punya tagihan belum lunas
        $query = Santri::where('pondok_id', $pondokId)
            ->whereHas('tagihans', function ($q) {
                $q->whereIn('status', ['pending', 'partial', 'overdue']);
            })
            ->with(['kelas', 'orangTua'])
            // Hitung total tunggakan per santri (menggunakan relasi tagihanDetails agar akurat jika ada cicilan)
            ->withSum(['tagihanDetails as total_tunggakan' => function ($q) {
                $q->whereHas('tagihan', function($sq) {
                    $sq->whereIn('status', ['pending', 'partial', 'overdue']);
                });
            }], 'sisa_tagihan_item');

        // Filter Pencarian
        if ($request->search) {
            $query->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
        }

        // Filter Kelas
        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Eksekusi Query
        // Ambil data, lalu filter yang total_tunggakan > 0, lalu urutkan
        $santris = $query->get()
            ->where('total_tunggakan', '>', 0)
            ->sortByDesc('total_tunggakan'); // Urutkan dari hutang terbesar

        // Hitung Grand Total
        $grandTotal = $santris->sum('total_tunggakan');

        // Pagination Manual (karena kita pakai collection filter)
        $page = $request->get('page', 1);
        $perPage = 20;
        $paginatedSantris = new \Illuminate\Pagination\LengthAwarePaginator(
            $santris->forPage($page, $perPage),
            $santris->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Ambil daftar kelas untuk filter dropdown
        $daftarKelas = \App\Models\Kelas::where('pondok_id', $pondokId)->get();

        return view('bendahara.tunggakan.index', [
            'santris' => $paginatedSantris,
            'grandTotal' => $grandTotal,
            'daftarKelas' => $daftarKelas
        ]);
    }

    /**
     * Menampilkan rincian tunggakan satu santri
     */
    public function show($id)
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;

        // Ambil data santri dan pastikan milik pondok ini
        $santri = Santri::where('id', $id)
            ->where('pondok_id', $pondokId)
            ->with(['kelas', 'orangTua'])
            ->firstOrFail();

        // Ambil tagihan yang BELUM LUNAS saja
        $tagihans = \App\Models\Tagihan::where('santri_id', $santri->id)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->with(['jenisPembayaran', 'tagihanDetails'])
            ->orderBy('due_date', 'asc') // Urutkan dari yang paling lama nunggak
            ->get();

        // Hitung total tunggakan real dari sisa item
        $totalTunggakan = 0;
        foreach ($tagihans as $tagihan) {
            $totalTunggakan += $tagihan->tagihanDetails->sum('sisa_tagihan_item');
        }

        return view('bendahara.tunggakan.show', compact('santri', 'tagihans', 'totalTunggakan'));
    }
}