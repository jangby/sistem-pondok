<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PpdbSetting;
use App\Models\CalonSantri;
use App\Models\PpdbDistribusiDana;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PpdbDistribusiController extends Controller
{
    // LEVEL 1: DASHBOARD UTAMA (POHON AKAR)
    public function index(Request $request)
    {
        // Default ambil gelombang aktif, atau yang dipilih user
        $settingId = $request->get('gelombang_id');
        $activeSetting = $settingId 
            ? PpdbSetting::find($settingId) 
            : PpdbSetting::where('is_active', true)->first();

        if (!$activeSetting) {
            return back()->with('error', 'Belum ada gelombang PPDB yang aktif.');
        }

        // 1. Hitung Total Tagihan per Kategori (Potensi Uang)
        // Kita ambil semua biaya di gelombang ini
        $biayas = $activeSetting->biayas;
        
        // Kita butuh jumlah santri per jenjang untuk mengalikan biayanya
        $countSantri = CalonSantri::where('ppdb_setting_id', $activeSetting->id)
            ->select('jenjang', DB::raw('count(*) as total'))
            ->groupBy('jenjang')
            ->pluck('total', 'jenjang');

        // Struktur Data Awal
        $posData = [
            'yayasan' => ['masuk' => 0, 'keluar' => 0, 'label' => 'Yayasan'],
            'pondok'  => ['masuk' => 0, 'keluar' => 0, 'label' => 'Pondok'],
            'usaha'   => ['masuk' => 0, 'keluar' => 0, 'label' => 'Unit Usaha'],
            'panitia' => ['masuk' => 0, 'keluar' => 0, 'label' => 'Panitia PPDB'],
        ];

        // 2. Hitung Uang Masuk (Real) Proporsional
        // Ambil santri yang sudah bayar (sebagian atau lunas)
        $santris = CalonSantri::with('user')
            ->where('ppdb_setting_id', $activeSetting->id)
            ->where('total_sudah_bayar', '>', 0)
            ->get();

        foreach ($santris as $santri) {
            // Ambil rincian biaya untuk jenjang santri ini
            $biayaSantri = $biayas->where('jenjang', $santri->jenjang);
            $totalTagihanDia = $biayaSantri->sum('nominal');

            if ($totalTagihanDia > 0) {
                // Hitung proporsi pembayaran dia
                // Misal dia bayar 1jt dari tagihan 2jt, berarti rasionya 0.5
                $ratio = $santri->total_sudah_bayar / $totalTagihanDia;

                // Distribusikan ke pos
                foreach ($biayaSantri as $item) {
                    $uangUntukItemIni = $item->nominal * $ratio;
                    if (isset($posData[$item->kategori])) {
                        $posData[$item->kategori]['masuk'] += $uangUntukItemIni;
                    }
                }
            }
        }

        // 3. Hitung Pengeluaran (Setoran/Belanja) dari Database
        $distribusi = PpdbDistribusiDana::where('ppdb_setting_id', $activeSetting->id)
            ->select('kategori', DB::raw('sum(nominal) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        foreach ($distribusi as $kat => $total) {
            if (isset($posData[$kat])) {
                $posData[$kat]['keluar'] = $total;
            }
        }

        $allSettings = PpdbSetting::orderBy('tahun_ajaran', 'desc')->get();

        return view('adminpondok.ppdb.distribusi.index', compact('activeSetting', 'posData', 'allSettings'));
    }

    // LEVEL 2: RINCIAN PER POS (DAHAN)
    public function show($kategori, Request $request)
    {
        // 1. Setup & Validasi
        $settingId = $request->get('gelombang_id');
        $setting = $settingId ? PpdbSetting::find($settingId) : PpdbSetting::where('is_active', true)->first();

        if (!in_array($kategori, ['yayasan', 'pondok', 'usaha', 'panitia'])) {
            abort(404);
        }

        $titles = [
            'yayasan' => 'Keuangan Yayasan',
            'pondok'  => 'Operasional Pondok',
            'usaha'   => 'Unit Usaha',
            'panitia' => 'Kas Panitia PPDB',
        ];

        // 2. HITUNG PEMASUKAN (Proporsional per Item Biaya)
        // Kita hitung: dari total uang masuk, berapa porsi untuk "Gedung", "Seragam", dll.
        $breakdownMasuk = [];
        $totalMasuk = 0;

        // Ambil semua santri yang sudah bayar di gelombang ini
        $santris = CalonSantri::where('ppdb_setting_id', $setting->id)
            ->where('total_sudah_bayar', '>', 0)
            ->get();

        // Ambil daftar biaya yang termasuk kategori ini
        $itemsKategoriIni = $setting->biayas->where('kategori', $kategori);

        // Inisialisasi array
        foreach($itemsKategoriIni as $item) {
            $breakdownMasuk[$item->nama_biaya] = 0;
        }

        foreach ($santris as $santri) {
            // Ambil struktur biaya untuk jenjang santri ini
            $biayaSantri = $setting->biayas->where('jenjang', $santri->jenjang);
            $totalTagihanDia = $biayaSantri->sum('nominal');

            if ($totalTagihanDia > 0) {
                // Rasio Pembayaran (Misal: baru bayar 50%)
                $ratio = $santri->total_sudah_bayar / $totalTagihanDia;
                
                // Hitung porsi untuk item-item di kategori ini saja
                foreach ($itemsKategoriIni as $item) {
                    // Cek apakah item ini berlaku untuk jenjang santri ini
                    if ($item->jenjang == $santri->jenjang) {
                        $porsiUang = $item->nominal * $ratio;
                        
                        if (isset($breakdownMasuk[$item->nama_biaya])) {
                            $breakdownMasuk[$item->nama_biaya] += $porsiUang;
                        } else {
                            $breakdownMasuk[$item->nama_biaya] = $porsiUang;
                        }
                        $totalMasuk += $porsiUang;
                    }
                }
            }
        }

        // 3. HITUNG PENGELUARAN (Riwayat Transaksi)
        $riwayatKeluar = PpdbDistribusiDana::with('user')
            ->where('ppdb_setting_id', $setting->id)
            ->where('kategori', $kategori)
            ->latest()
            ->get();
        
        $totalKeluar = $riwayatKeluar->sum('nominal');
        $saldo = $totalMasuk - $totalKeluar;

        // Kita hanya ambil santri yang sudah bayar > 0 di gelombang ini
        $listSantri = CalonSantri::where('ppdb_setting_id', $setting->id)
            ->where('total_sudah_bayar', '>', 0)
            ->orderBy('full_name', 'asc')
            ->get();

        return view('adminpondok.ppdb.distribusi.show', [
            'kategori'       => $kategori,
            'title'          => $titles[$kategori],
            'setting'        => $setting,
            'riwayat'        => $riwayatKeluar,
            'totalMasuk'     => $totalMasuk,
            'totalKeluar'    => $totalKeluar,
            'saldo'          => $saldo,
            'breakdownMasuk' => $breakdownMasuk,
            'listSantri'     => $listSantri // <--- Kirim variabel ini ke View
        ]);
    }

    // FUNGSI SIMPAN SETORAN / BELANJA
    public function store(Request $request)
    {
        $request->validate([
            'ppdb_setting_id' => 'required',
            'kategori' => 'required',
            'jenis' => 'required|in:setoran,belanja',
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'bukti_foto' => 'nullable|image|max:2048',
            'list_santri_id' => 'nullable|array' // Validasi Array
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        // Upload Foto
        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('uploads/bukti-distribusi', 'public');
            $data['bukti_foto'] = $path;
        }

        PpdbDistribusiDana::create($data);

        return back()->with('success', 'Data transaksi berhasil disimpan.');
    }

    // FUNGSI CETAK BUKTI (PDF)
    public function printBukti($id)
    {
        $transaksi = PpdbDistribusiDana::with(['user', 'ppdbSetting'])->findOrFail($id);
        
        // Ambil data santri jika ada lampiran
        $lampiranSantri = [];
        if (!empty($transaksi->list_santri_id)) {
            $lampiranSantri = CalonSantri::whereIn('id', $transaksi->list_santri_id)
                                ->orderBy('full_name')
                                ->get();
        }
        
        $pdf = Pdf::loadView('adminpondok.ppdb.distribusi.pdf_bukti', compact('transaksi', 'lampiranSantri'));
        return $pdf->stream('Bukti-Transaksi-'.$id.'.pdf');
    }

    // LEVEL 3: DETAIL ITEM PER SANTRI (DAUN)
    public function detailItem($kategori, Request $request)
    {
        $namaBiaya = $request->query('nama_biaya');
        $settingId = $request->get('gelombang_id');
        $setting = $settingId ? PpdbSetting::find($settingId) : PpdbSetting::where('is_active', true)->first();

        if (!$namaBiaya || !$setting) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // 1. Ambil Santri yang sudah bayar
        $santris = CalonSantri::where('ppdb_setting_id', $setting->id)
            ->where('total_sudah_bayar', '>', 0)
            ->orderBy('full_name', 'asc')
            ->get();

        // 2. Filter & Hitung Kontribusi per Santri untuk Item ini
        $dataSantri = [];
        $totalTerkumpul = 0;

        foreach ($santris as $santri) {
            // Ambil biaya item ini untuk jenjang si santri (misal: Uang Gedung SMA)
            $biayaItem = $setting->biayas
                ->where('nama_biaya', $namaBiaya)
                ->where('jenjang', $santri->jenjang)
                ->first();

            // Jika santri ini punya tagihan untuk item ini
            if ($biayaItem) {
                // Hitung total seluruh tagihan santri ini
                $totalTagihanDia = $setting->biayas
                    ->where('jenjang', $santri->jenjang)
                    ->sum('nominal');

                if ($totalTagihanDia > 0) {
                    // Rasio: Sudah Bayar / Total Tagihan
                    $ratio = $santri->total_sudah_bayar / $totalTagihanDia;
                    
                    // Uang santri yang dialokasikan untuk item ini
                    $kontribusi = $biayaItem->nominal * $ratio;

                    if ($kontribusi > 0) {
                        $dataSantri[] = [
                            'nama' => $santri->full_name,
                            'jenjang' => $santri->jenjang,
                            'status_bayar' => $santri->status_pembayaran, // lunas/belum
                            'nilai_item_penuh' => $biayaItem->nominal, // Harga asli item
                            'kontribusi_masuk' => $kontribusi // Yang sudah masuk
                        ];
                        $totalTerkumpul += $kontribusi;
                    }
                }
            }
        }

        return view('adminpondok.ppdb.distribusi.item_detail', compact(
            'setting', 'kategori', 'namaBiaya', 'dataSantri', 'totalTerkumpul'
        ));
    }
}