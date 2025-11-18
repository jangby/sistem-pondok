<?php

namespace App\Http\Controllers\Pengurus\Inventaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventaris\Audit;
use App\Models\Inventaris\Barang;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();

        // 1. Data Pending (Butuh Tindakan)
        $pendings = Audit::where('pondok_id', $pondokId)
            ->where('status', 'pending')
            ->with('barang')
            ->latest()
            ->get();

        // 2. Data Riwayat (Sudah Direkonsiliasi)
        $queryHistory = Audit::where('pondok_id', $pondokId)
            ->where('status', 'reconciled')
            ->with('barang')
            ->latest('updated_at');

        // Filter Tanggal untuk History
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        if ($request->has('filter')) {
             $queryHistory->whereBetween('audit_date', [$startDate, $endDate]);
        }

        $history = $queryHistory->paginate(15)->withQueryString();

        return view('pengurus.inventaris.audit.index', compact('pendings', 'history', 'startDate', 'endDate'));
    }

    public function scan()
    {
        return view('pengurus.inventaris.audit.scan');
    }

    // Proses Input Hasil Hitung Fisik
    public function process(Request $request)
    {
        $pondokId = $this->getPondokId();
        $code = $request->code; 
        $fisik = $request->actual_qty;

        $barang = Barang::where('pondok_id', $pondokId)->where('code', $code)->first();
        if (!$barang) return response()->json(['status' => 'error', 'message' => 'Barang tidak ditemukan']);

        // Hitung Selisih
        $selisih = $fisik - $barang->qty_good; 

        Audit::create([
            'pondok_id' => $pondokId,
            'item_id' => $barang->id,
            'expected_qty' => $barang->qty_good,
            'actual_qty' => $fisik,
            'difference' => $selisih,
            'status' => 'pending', 
            'audit_date' => now()
        ]);

        return response()->json([
            'status' => 'success', 
            'barang' => $barang->name,
            'selisih' => $selisih
        ]);
    }

    // Eksekusi Penyesuaian Stok
    public function reconcile($id)
    {
        DB::transaction(function () use ($id) {
            $audit = Audit::findOrFail($id);
            if ($audit->status == 'reconciled') return;

            $barang = $audit->barang;
            $diff = $audit->difference; 

            if ($diff < 0) {
                // Barang Hilang
                $hilang = abs($diff);
                $barang->decrement('qty_good', $hilang);
                $barang->increment('qty_lost', $hilang);
            } elseif ($diff > 0) {
                // Barang Lebih (Ketemu)
                $barang->increment('qty_good', $diff);
            }

            $audit->update(['status' => 'reconciled']);
        });

        return back()->with('success', 'Stok berhasil disesuaikan & disimpan ke riwayat.');
    }

    // --- CETAK PDF ---
    // --- CETAK PDF (UPDATE) ---
    public function downloadPDF(Request $request)
    {
        $pondokId = $this->getPondokId();
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $audits = Audit::where('pondok_id', $pondokId)
            ->where('status', 'reconciled')
            ->whereBetween('audit_date', [$startDate, $endDate])
            // UPDATE: Tambahkan .pic agar data penanggung jawab terbawa
            ->with(['barang.pic']) 
            ->orderBy('audit_date')
            ->get();

        $pdf = Pdf::loadView('pengurus.inventaris.audit.pdf', compact('audits', 'startDate', 'endDate'));
        // Gunakan Landscape agar muat kolom tambahan
        $pdf->setPaper('a4', 'landscape'); 
        
        return $pdf->stream('Laporan-Rekonsiliasi.pdf');
    }
}