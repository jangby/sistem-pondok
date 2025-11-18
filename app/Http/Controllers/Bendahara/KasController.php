<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KasController extends Controller
{
    private function getQuery()
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;
        return Kas::where('pondok_id', $pondokId);
    }
    
    /**
     * Halaman "Buku Kas" (Laporan)
     */
    public function index(Request $request)
    {
        $query = $this->getQuery()->with('user'); // Ambil pencatat
        
        $tanggalMulai = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', now()->endOfMonth()->format('Y-m-d'));

        // 1. Saldo Awal (Saldo sebelum Tanggal Mulai)
        $pemasukanAwal = $this->getQuery()
            ->where('tipe', 'pemasukan')
            ->whereDate('tanggal_transaksi', '<', $tanggalMulai)
            ->sum('nominal');
        $pengeluaranAwal = $this->getQuery()
            ->where('tipe', 'pengeluaran')
            ->whereDate('tanggal_transaksi', '<', $tanggalMulai)
            ->sum('nominal');
        $saldoAwal = $pemasukanAwal - $pengeluaranAwal;

        // 2. Transaksi (Sesuai rentang tanggal)
        $query->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai]);

        // Filter Tipe
        $query->when($request->filled('tipe'), function($q) use ($request) {
            $q->where('tipe', $request->tipe);
        });

        $transaksis = $query->latest('tanggal_transaksi')->latest('id')->paginate(20)->withQueryString();

        // 3. Ringkasan (HANYA dalam rentang tanggal)
        $totalPemasukan = $transaksis->where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksis->where('tipe', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        return view('bendahara.kas.index', compact(
            'transaksis', 'saldoAwal', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'
        ));
    }

    /**
     * Tampilkan form tambah PENGELUARAN
     */
    public function create()
    {
        return view('bendahara.kas.create');
    }

    /**
     * Simpan PENGELUARAN baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
        ]);

        // 1. Ambil Pondok ID dari user (Bendahara) yang sedang login
        $pondokId = Auth::user()->pondokStaff->pondok_id;

        // 2. Gunakan Kas::create() dan tambahkan pondok_id
        Kas::create([
            'pondok_id' => $pondokId, // <-- INI ADALAH PERBAIKANNYA
            'user_id' => Auth::id(),
            'tipe' => 'pengeluaran',
            'deskripsi' => $validated['deskripsi'],
            'nominal' => $validated['nominal'],
            'tanggal_transaksi' => $validated['tanggal_transaksi'],
        ]);

        return redirect()->route('bendahara.kas.index')
                         ->with('success', 'Catatan pengeluaran berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit PENGELUARAN
     */
    public function edit(Kas $ka) // Nama variabel $kas bentrok, ganti $ka
    {
        $kas = $ka;
        // Keamanan: Cek kepemilikan
        if ($kas->pondok_id != Auth::user()->pondokStaff->pondok_id) {
            abort(404);
        }
        // Hanya boleh edit pengeluaran, pemasukan tidak boleh diedit
        if ($kas->tipe == 'pemasukan') {
            return redirect()->route('bendahara.kas.index')
                             ->with('error', 'Pemasukan otomatis tidak dapat diedit.');
        }

        return view('bendahara.kas.edit', compact('kas'));
    }

    /**
     * Update PENGELUARAN
     */
    public function update(Request $request, Kas $ka)
    {
        $kas = $ka;
        if ($kas->pondok_id != Auth::user()->pondokStaff->pondok_id || $kas->tipe == 'pemasukan') {
            abort(404);
        }
        
        $validated = $request->validate([
            'deskripsi' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
        ]);

        $kas->update($validated);

        return redirect()->route('bendahara.kas.index')
                         ->with('success', 'Catatan pengeluaran berhasil diperbarui.');
    }

    /**
     * Hapus PENGELUARAN
     */
    public function destroy(Kas $ka)
    {
        $kas = $ka;
        if ($kas->pondok_id != Auth::user()->pondokStaff->pondok_id || $kas->tipe == 'pemasukan') {
            abort(404);
        }

        $kas->delete();

        return redirect()->route('bendahara.kas.index')
                         ->with('success', 'Catatan pengeluaran berhasil dihapus.');
    }

    /**
     * Buat dan unduh Laporan Buku Kas (PDF)
     */
    public function downloadPDF(Request $request)
    {
        // 1. Ambil data Saldo Awal (logika SAMA PERSIS dengan index)
        $tanggalMulai = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', now()->endOfMonth()->format('Y-m-d'));

        $pemasukanAwal = $this->getQuery()
            ->where('tipe', 'pemasukan')
            ->whereDate('tanggal_transaksi', '<', $tanggalMulai)
            ->sum('nominal');
        $pengeluaranAwal = $this->getQuery()
            ->where('tipe', 'pengeluaran')
            ->whereDate('tanggal_transaksi', '<', $tanggalMulai)
            ->sum('nominal');
        $saldoAwal = $pemasukanAwal - $pengeluaranAwal;

        // 2. Ambil data Transaksi (logika SAMA, tapi pakai get() bukan paginate())
        $query = $this->getQuery()->with('user');
        $query->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai]);
        $query->when($request->filled('tipe'), function($q) use ($request) {
            $q->where('tipe', $request->tipe);
        });
        
        // PENTING: Gunakan get() untuk PDF, bukan paginate()
        $transaksis = $query->latest('tanggal_transaksi')->latest('id')->get(); 

        // 3. Hitung Ringkasan (logika SAMA)
        $totalPemasukan = $transaksis->where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksis->where('tipe', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        // 4. Siapkan data untuk PDF
        $data = [
            'transaksis' => $transaksis,
            'saldoAwal' => $saldoAwal,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ];

        // 5. Generate PDF
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('bendahara.kas.pdf', $data); // Kita akan buat view 'pdf.blade.php'
        
        $namaFile = "Buku-Kas-{$tanggalMulai}-sd-{$tanggalSelesai}.pdf";
        
        return $pdf->stream($namaFile);
    }
}