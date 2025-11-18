<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setoran;
use App\Models\AlokasiPembayaran;
use App\Models\PembayaranTransaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Kas;

class SetoranController extends Controller
{
    /**
     * Helper untuk keamanan, memastikan bendahara hanya melihat
     * setoran dari pondok-nya sendiri.
     */
    private function getSetoranQuery()
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;
        return Setoran::where('pondok_id', $pondokId);
    }

    /**
     * Keamanan: Cek kepemilikan setoran
     */
    private function checkOwnership(Setoran $setoran)
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;
        if ($setoran->pondok_id != $pondokId) {
            abort(404, 'Setoran tidak ditemukan');
        }
    }

    /**
     * Tampilkan daftar setoran yang masuk (Menunggu & Terkonfirmasi).
     */
    public function index(Request $request)
    {
        // (Fungsi index Anda sudah benar, tidak perlu diubah)
        $query = $this->getSetoranQuery()->with('admin');

        $query->when($request->filled('status'), function ($q) use ($request) {
            if ($request->status == 'pending') {
                $q->whereNull('dikonfirmasi_pada');
            } elseif ($request->status == 'confirmed') {
                $q->whereNotNull('dikonfirmasi_pada');
            }
        });

        $setorans = $query->latest()->paginate(15)->withQueryString();
        
        return view('bendahara.setoran.index', compact('setorans'));
    }

    // =================================================================
    // INI ADALAH LOGIKA BARU YANG DIPERLUKAN
    // =================================================================
    /**
     * Helper: Ambil data rinci untuk laporan
     */
    private function getLaporanData(Setoran $setoran)
    {
        // 1. Ambil ID transaksi dari setoran ini
        $transaksiIds = $setoran->transaksi()->pluck('id');

        // 2. Query Laporan Per Item
        $summaryPerItem = AlokasiPembayaran::join('tagihan_details as td', 'alokasi_pembayarans.tagihan_detail_id', '=', 'td.id')
                            ->whereIn('pembayaran_transaksi_id', $transaksiIds)
                            ->groupBy('td.nama_item')
                            ->select('td.nama_item', DB::raw('SUM(alokasi_pembayarans.nominal_alokasi) as total_terkumpul'))
                            ->orderBy('total_terkumpul', 'desc')
                            ->get();

        // 3. Query Daftar Transaksi Rinci (untuk PDF)
        // Kita ambil semua relasi yang dibutuhkan
        $daftarTransaksi = PembayaranTransaksi::with('tagihan.santri', 'tagihan.jenisPembayaran', 'orangTua')
                            ->whereIn('pembayaran_transaksis.id', $transaksiIds)
                            ->get();

        // 4. Pisahkan Putra & Putri
        $santriPutra = $daftarTransaksi->where('tagihan.santri.jenis_kelamin', 'Laki-laki')
                        ->groupBy('tagihan.santri.id') // Grup per santri
                        ->map(fn($group) => [ // Buat ringkasan per santri
                            'nama' => $group->first()->tagihan->santri->full_name,
                            'nis' => $group->first()->tagihan->santri->nis,
                            'total' => $group->sum('total_bayar'),
                            'rincian' => $group // <-- INI KUNCI YANG HILANG
                        ])->sortBy('nama');

        $santriPutri = $daftarTransaksi->where('tagihan.santri.jenis_kelamin', 'Perempuan')
                        ->groupBy('tagihan.santri.id')
                        ->map(fn($group) => [
                            'nama' => $group->first()->tagihan->santri->full_name,
                            'nis' => $group->first()->tagihan->santri->nis,
                            'total' => $group->sum('total_bayar'),
                            'rincian' => $group // <-- INI KUNCI YANG HILANG
                        ])->sortBy('nama');

        return compact('summaryPerItem', 'santriPutra', 'santriPutri', 'daftarTransaksi');
    }
    
    /**
     * Tampilkan detail satu setoran (rincian item & transaksi).
     * INI FUNGSI YANG DIPERBARUI
     */
    public function show(Setoran $setoran)
    {
        // 1. Cek Keamanan
        $this->checkOwnership($setoran);
        
        // 2. Panggil helper baru kita untuk mengambil semua data
        $data = $this->getLaporanData($setoran);
        
        // 3. Kirim semua data ke View
        return view('bendahara.setoran.show', [
            'setoran' => $setoran,
            'summaryPerItem' => $data['summaryPerItem'],
            'santriPutra' => $data['santriPutra'],
            'santriPutri' => $data['santriPutri'],
        ]);
    }
    // =================================================================
    // AKHIR DARI BLOK PERBAIKAN
    // =================================================================

    /**
     * Konfirmasi penerimaan setoran.
     */
    public function konfirmasi(Request $request, Setoran $setoran)
    {
        // (Fungsi konfirmasi Anda sudah benar)
        $this->checkOwnership($setoran);

        if (is_null($setoran->dikonfirmasi_pada)) {
            $setoran->dikonfirmasi_pada = now();
            $setoran->bendahara_id_penerima = Auth::id();
            $setoran->save();

            try {
        \App\Models\Kas::create([
            'pondok_id' => $setoran->pondok_id,
            'user_id' => Auth::id(), // Bendahara yg konfirmasi
            'setoran_id' => $setoran->id,
            'tipe' => 'pemasukan',
            'deskripsi' => 'Pemasukan dari Setoran ' 
                           . str_replace('_', ' ', $setoran->kategori_setoran) 
                           . ' (ID: ' . $setoran->id . ')',
            'nominal' => $setoran->total_setoran,
            'tanggal_transaksi' => $setoran->tanggal_setoran, // Ambil tgl setoran
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal mencatat kas otomatis: ' . $e->getMessage());
        // Kirim pesan error, tapi setoran tetap terkonfirmasi
        return redirect()->route('bendahara.setoran.index')
                         ->with('error', 'Setoran terkonfirmasi, TAPI gagal mencatat ke Buku Kas: ' . $e->getMessage());
    }
            
            return redirect()->route('bendahara.setoran.index')
                             ->with('success', 'Setoran telah berhasil dikonfirmasi.');
        }

        return redirect()->route('bendahara.setoran.index')
                         ->with('error', 'Setoran ini sudah dikonfirmasi sebelumnya.');
    }

    /**
     * Cetak PDF Setoran
     */
    public function downloadPDF(\App\Models\Setoran $setoran)
    {
        // Pastikan memuat relasi yang dibutuhkan view PDF
        $setoran->load(['admin', 'bendaharaPenerima']);

        // Data untuk Santri Putra
        $santriPutra = \App\Models\Santri::whereHas('tagihanDetails.tagihan.pembayaranTransaksis', function($q) use ($setoran) {
                $q->where('setoran_id', $setoran->id);
            })
            ->where('jenis_kelamin', 'Laki-laki')
            ->with(['tagihanDetails' => function($q) use ($setoran) {
                $q->whereHas('tagihan.pembayaranTransaksis', fn($sq) => $sq->where('setoran_id', $setoran->id));
            }])
            ->get()
            ->map(function($santri) use ($setoran) {
                // Logika hitung per santri (sama seperti admin pondok)
                $transaksiSantri = \App\Models\PembayaranTransaksi::where('setoran_id', $setoran->id)
                    ->whereHas('tagihan', fn($q) => $q->where('santri_id', $santri->id))
                    ->with('tagihan.jenisPembayaran')
                    ->get();
                
                return [
                    'nama' => $santri->full_name,
                    'nis' => $santri->nis,
                    'total' => $transaksiSantri->sum('total_bayar'),
                    'rincian' => $transaksiSantri
                ];
            });

        // Data untuk Santri Putri
        $santriPutri = \App\Models\Santri::whereHas('tagihanDetails.tagihan.pembayaranTransaksis', function($q) use ($setoran) {
                $q->where('setoran_id', $setoran->id);
            })
            ->where('jenis_kelamin', 'Perempuan')
            ->with(['tagihanDetails' => function($q) use ($setoran) {
                $q->whereHas('tagihan.pembayaranTransaksis', fn($sq) => $sq->where('setoran_id', $setoran->id));
            }])
            ->get()
            ->map(function($santri) use ($setoran) {
                $transaksiSantri = \App\Models\PembayaranTransaksi::where('setoran_id', $setoran->id)
                    ->whereHas('tagihan', fn($q) => $q->where('santri_id', $santri->id))
                    ->with('tagihan.jenisPembayaran')
                    ->get();
                
                return [
                    'nama' => $santri->full_name,
                    'nis' => $santri->nis,
                    'total' => $transaksiSantri->sum('total_bayar'),
                    'rincian' => $transaksiSantri
                ];
            });

        // Ringkasan per Item
        $daftarTransaksi = \App\Models\PembayaranTransaksi::where('setoran_id', $setoran->id)
            ->with('tagihan.jenisPembayaran')
            ->get();

        $summaryPerItem = $daftarTransaksi->groupBy('tagihan.jenisPembayaran.name')
            ->map(function ($row, $key) {
                return (object)[
                    'nama_item' => $key,
                    'total_terkumpul' => $row->sum('total_bayar')
                ];
            });

        $judulLaporan = 'Laporan Setoran #' . $setoran->id;

        // Kita gunakan view PDF yang sama dengan Admin Pondok karena isinya identik
        // Pastikan file 'resources/views/adminpondok/setoran/pdf.blade.php' sudah ada (dari langkah sebelumnya)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('adminpondok.setoran.pdf', compact(
            'setoran', 
            'summaryPerItem', 
            'santriPutra', 
            'santriPutri', 
            'daftarTransaksi',
            'judulLaporan'
        ));

        return $pdf->stream('Setoran-' . $setoran->id . '.pdf');
    }
}