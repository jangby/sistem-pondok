<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;

class TunggakanController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar: Ambil Santri. Trait 'BelongsToPondok' otomatis aktif.
        $query = Santri::query();

        // 1. Tambahkan SUM "Pintar"
        // Kita hitung 'total_tunggakan' untuk setiap santri
        // dengan menjumlahkan 'sisa_tagihan_item' dari relasi 'tagihanDetails'
        // HANYA untuk item yang statusnya 'pending'.
        $query->withSum(
            ['tagihanDetails as total_tunggakan' => fn($q) => $q->where('status_item', 'pending')],
            'sisa_tagihan_item'
        );

        // 2. Terapkan Filter
        // Filter Pencarian (dari Tom-Select)
        $query->when($request->filled('santri_id'), function ($q) use ($request) {
            return $q->where('id', $request->santri_id);
        });

        // Filter Jenis Kelamin
        $query->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
            return $q->where('jenis_kelamin', $request->jenis_kelamin);
        });
        
        // Filter Kelas
        $query->when($request->filled('kelas'), function ($q) use ($request) {
            // Ambil semua kelas unik dulu untuk dropdown filter
            return $q->where('class', $request->kelas);
        });

        // 3. Terapkan Sorting
        $sort = $request->get('sort', 'tunggakan_desc'); // Default: tunggakan terbesar
        if ($sort == 'tunggakan_desc') {
            $query->orderBy('total_tunggakan', 'desc');
        } elseif ($sort == 'tunggakan_asc') {
            $query->orderBy('total_tunggakan', 'asc');
        } elseif ($sort == 'nama_asc') {
            $query->orderBy('full_name', 'asc');
        }

        // 4. Hanya tampilkan santri yang PUNYA tunggakan
        // Jika Anda ingin menampilkan semua santri (termasuk yg 0), hapus baris ini
        $query->having('total_tunggakan', '>', 0);
        
        // 5. Eksekusi Query dengan Paginasi
        $santris = $query->paginate(15)->withQueryString();

        // 6. Ambil data untuk Form Filter
        $selectedSantri = Santri::find($request->santri_id);
        $daftarKelas = Santri::select('kelas_id')->distinct()->whereNotNull('kelas_id')->orderBy('kelas_id')->get();
        
        // 7. Hitung Grand Total (untuk kartu di atas)
        // Kita harus query ulang tanpa paginasi untuk total keseluruhan
        $grandTotalTunggakan = $query->sum('total_tunggakan');
        // Catatan: Ini kurang efisien. Cara lebih baik adalah:
        // $grandTotalTunggakan = DB::table('tagihan_details')
        //     ->join('tagihans', 'tagihan_details.tagihan_id', '=', 'tagihans.id')
        //     ->join('santris', 'tagihans.santri_id', '=', 'santris.id')
        //     ->where('santris.pondok_id', auth()->user()->pondokStaff->pondok_id)
        //     ->where('tagihan_details.status_item', 'pending')
        //     ->sum('tagihan_details.sisa_tagihan_item');
        // Tapi kita pakai query pertama agar filter juga ter-apply ke grand total.
        // Mari kita gunakan query builder murni yang efisien...
        
        $grandTotalQuery = Santri::query();
        $grandTotalQuery->join('tagihans', 'santris.id', '=', 'tagihans.santri_id');
        $grandTotalQuery->join('tagihan_details', 'tagihans.id', '=', 'tagihan_details.tagihan_id');
        $grandTotalQuery->where('santris.pondok_id', auth()->user()->pondokStaff->pondok_id);
        $grandTotalQuery->where('tagihan_details.status_item', 'pending');
        
        // Terapkan filter yang sama ke Grand Total
        $grandTotalQuery->when($request->filled('santri_id'), fn($q) => $q->where('santris.id', $request->santri_id));
        $grandTotalQuery->when($request->filled('jenis_kelamin'), fn($q) => $q->where('santris.jenis_kelamin', $request->jenis_kelamin));
        $grandTotalQuery->when($request->filled('kelas'), fn($q) => $q->where('santris.class', $request->kelas));

        $grandTotalTunggakan = $grandTotalQuery->sum('tagihan_details.sisa_tagihan_item');


        return view('adminpondok.tunggakan.index', compact(
            'santris', 
            'selectedSantri', 
            'daftarKelas',
            'grandTotalTunggakan'
        ));
    }
}