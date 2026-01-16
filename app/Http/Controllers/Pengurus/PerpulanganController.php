<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\PerpulanganEvent;
use App\Models\Santri;
use App\Models\Mustawa;
use App\Models\PerpulanganRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Exports\Pengurus\PerpulanganExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PerpulanganController extends Controller
{
    // 1. Menampilkan Daftar Acara Liburan
    public function index()
    {
        // Ambil event terbaru, urutkan dari yang paling baru dibuat
        $events = PerpulanganEvent::latest()->get();
        return view('pengurus.perpulangan.index', compact('events'));
    }

    // 2. Menampilkan Form Tambah Acara
    public function create()
    {
        return view('pengurus.perpulangan.create');
    }

    // 3. Menyimpan Data Acara Baru ke Database
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // Jika acara ini aktif, non-aktifkan acara lain (supaya tidak bentrok)
        if ($request->has('is_active')) {
            PerpulanganEvent::where('is_active', true)->update(['is_active' => false]);
        }

        PerpulanganEvent::create([
            'judul' => $request->judul,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'keterangan' => $request->keterangan,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('pengurus.perpulangan.index')
            ->with('success', 'Acara perpulangan berhasil dibuat.');
    }

    // 4. Menghapus Acara
    public function destroy($id)
    {
        $event = PerpulanganEvent::findOrFail($id);
        $event->delete();
        return redirect()->back()->with('success', 'Acara dihapus.');
    }

    // 5. Halaman Memilih Santri (Filter per Mustawa/Kelas Pesantren)
    public function pilihSantri(Request $request, $id)
    {
        $event = PerpulanganEvent::findOrFail($id);
        
        // Ambil data Mustawa (Kelas Pesantren)
        $mustawas = Mustawa::orderBy('nama')->get(); // Asumsi kolom nama adalah 'name'
        
        $santris = collect();
        if ($request->has('mustawa_id') && $request->mustawa_id != '') {
            $santris = Santri::where('mustawa_id', $request->mustawa_id)
                             ->where('status', 'active')
                             ->orderBy('full_name')
                             ->get();
        }

        return view('pengurus.perpulangan.pilih_santri', compact('event', 'mustawas', 'santris'));
    }

    // 6. Proses Cetak (Tidak banyak berubah, hanya validasi)
    public function cetakKartu(Request $request, $id)
    {
        $event = PerpulanganEvent::findOrFail($id);
        
        $request->validate([
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'exists:santris,id',
        ]);

        $cards = [];

        foreach ($request->santri_ids as $santriId) {
            $record = PerpulanganRecord::firstOrCreate(
                [
                    'perpulangan_event_id' => $event->id,
                    'santri_id' => $santriId
                ],
                [
                    'qr_token' => $event->id . '-' . $santriId . '-' . Str::random(8),
                    'status' => 0
                ]
            );
            $cards[] = $record;
        }

        return view('pengurus.perpulangan.print', compact('event', 'cards'));
    }

    // 7. Tampilkan Halaman Scan
    public function scanIndex()
    {
        return view('pengurus.perpulangan.scan');
    }

    // 8. Proses Logic Scan (AJAX) - VERSI DIPERKETAT
    public function scanProcess(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
            'mode' => 'required|in:keluar,masuk', 
        ]);

        // 1. Cari Data Berdasarkan Token
        // Kita eager load 'event' dan 'santri' biar query efisien
        $record = PerpulanganRecord::with(['event', 'santri.mustawa', 'santri.asrama'])
                    ->where('qr_token', $request->qr_token)
                    ->first();

        if (!$record) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code TIdak Valid / Tidak Dikenali!'
            ], 404);
        }

        $event = $record->event;
        $santri = $record->santri;
        $timestamp = now();
        $todayDate = $timestamp->format('Y-m-d');

        // 2. Validasi Global: Status Event
        if (!$event->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'GAGAL: Event perpulangan ini sudah dinonaktifkan.'
            ], 400);
        }

        // --- LOGIKA MODE KELUAR (PULANG) ---
        if ($request->mode == 'keluar') {
            
            // A. Cek Status Record
            if ($record->status != 0) {
                $msg = $record->status == 1 
                    ? 'Santri ini SUDAH scan keluar sebelumnya.' 
                    : 'Santri ini sudah kembali ke pondok.';
                return response()->json(['status' => 'error', 'message' => $msg], 400);
            }

            // B. VALIDASI TANGGAL (PENTING!)
            // Jika hari ini < tanggal mulai, tolak.
            if ($todayDate < $event->tanggal_mulai->format('Y-m-d')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'BELUM WAKTUNYA! Jadwal pulang mulai: ' . $event->tanggal_mulai->format('d M Y')
                ], 400); // 400 Bad Request akan memicu suara error di frontend
            }

            // C. Update Database
            $record->update([
                'status' => 1, // Sedang Pulang
                'waktu_keluar' => $timestamp,
                'petugas_keluar_id' => auth()->id(), // Catat siapa yang jaga
            ]);

            $pesan = "Hati-hati di jalan!";
        } 
        
        // --- LOGIKA MODE MASUK (KEMBALI) ---
        else {
            
            // A. Cek Status Record
            if ($record->status == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'GAGAL: Santri ini tercatat BELUM PERNAH scan keluar.'
                ], 400);
            }
            if ($record->status == 2) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Santri ini sudah scan masuk sebelumnya.'
                ], 400);
            }

            // B. Cek Keterlambatan
            // Jika sekarang > tanggal akhir/wajib kembali
            $isLate = $timestamp->format('Y-m-d') > $event->tanggal_akhir->format('Y-m-d');

            $record->update([
                'status' => 2, // Sudah Kembali
                'waktu_kembali' => $timestamp,
                'is_late' => $isLate,
                'petugas_masuk_id' => auth()->id(),
            ]);

            $pesan = $isLate ? "Terlambat Kembali!" : "Ahlan wa Sahlan!";
        }

        // Return Data untuk Tampilan Kartu
        return response()->json([
            'status' => 'success',
            'message' => $pesan,
            'data' => [
                'nama' => $santri->full_name,
                'kelas' => $santri->mustawa->nama ?? '-',
                'asrama' => $santri->asrama->nama_asrama ?? '-',
                'waktu' => $timestamp->format('H:i d/m/Y'),
                'is_late' => $record->is_late ?? false
            ]
        ]);
    }

    // 9. Halaman Detail & Monitoring Event (DENGAN FILTER)
    public function show(Request $request, $id)
    {
        $event = PerpulanganEvent::findOrFail($id);

        // 1. Ambil Data Kelas yang ada di event ini (Untuk Dropdown Filter)
        // Kita hanya ambil kelas yang santrinya sudah didaftarkan/dicetak kartunya
        $mustawaIds = PerpulanganRecord::where('perpulangan_event_id', $id)
            ->join('santris', 'perpulangan_records.santri_id', '=', 'santris.id')
            ->select('santris.mustawa_id')
            ->distinct()
            ->pluck('santris.mustawa_id');
            
        $mustawas = Mustawa::whereIn('id', $mustawaIds)->orderBy('nama')->get();

        // 2. Query Utama Record
        $query = PerpulanganRecord::with(['santri.mustawa', 'santri.asrama'])
            ->where('perpulangan_event_id', $id);

        // 3. Logika Filter
        if ($request->has('mustawa_id') && $request->mustawa_id != '') {
            $query->whereHas('santri', function($q) use ($request) {
                $q->where('mustawa_id', $request->mustawa_id);
            });
        }

        $records = $query->get();

        // 4. Grouping Data (Tabulasi)
        $belum_pulang = $records->where('status', 0);
        $sedang_pulang = $records->where('status', 1);
        $sudah_kembali = $records->where('status', 2);
        
        $terlambat = $records->filter(function ($record) use ($event) {
            return $record->is_late || ($record->status == 1 && now()->greaterThan($event->tanggal_akhir));
        });

        return view('pengurus.perpulangan.show', compact(
            'event', 'records', 'belum_pulang', 'sedang_pulang', 'sudah_kembali', 'terlambat', 'mustawas'
        ));
    }

    // 10. Toggle Status Event (On/Off)
    public function toggleStatus($id)
    {
        $event = PerpulanganEvent::findOrFail($id);
        
        // Cek status sekarang
        $newStatus = !$event->is_active;

        // Jika mau diaktifkan, matikan dulu event yang lain
        if ($newStatus) {
            PerpulanganEvent::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $event->update(['is_active' => $newStatus]);

        return redirect()->back()->with('success', 'Status jadwal berhasil diperbarui.');
    }

    public function download(Request $request, $id)
    {
        $event = PerpulanganEvent::findOrFail($id);
        $status = $request->status ?? 'all';
        $format = $request->format ?? 'excel';

        if ($format == 'excel') {
            // Excel akan otomatis membuat 2 sheet (Putra & Putri) dari Class Export baru
            return Excel::download(new PerpulanganExport($id, $status), 'perpulangan-' . $status . '.xlsx');
        } elseif ($format == 'pdf') {
            // Helper query function
            $queryBase = PerpulanganRecord::with(['santri.kelas', 'event'])
                ->where('perpulangan_event_id', $id);

            // Filter Status
            if ($status && $status !== 'all') {
                $statusMap = ['belum_jalan' => 0, 'sedang_pulang' => 1, 'sudah_kembali' => 2];
                if (isset($statusMap[$status])) {
                    $queryBase->where('status', $statusMap[$status]);
                } elseif ($status == 'terlambat') {
                    // Logic terlambat query manual
                    $queryBase->where(function($q) use ($event) {
                        // Kasus 1: Sudah kembali tapi telat
                        $q->whereNotNull('waktu_kembali')
                          ->where('waktu_kembali', '>', $event->tanggal_akhir . ' 23:59:59');
                    })->orWhere(function($q) use ($event) {
                        // Kasus 2: Belum kembali dan hari ini sudah lewat batas
                        $q->whereNull('waktu_kembali')
                          ->where('status', '!=', 2)
                          ->whereRaw('NOW() > ?', [$event->tanggal_akhir . ' 23:59:59']);
                    });
                }
            }

            // Ambil Data Putra (L)
            // Clone query agar tidak merusak query dasar
            $recordsPutra = (clone $queryBase)->whereHas('santri', function($q) {
                $q->where('jenis_kelamin', 'L'); // Sesuaikan 'L' dengan data di DB Anda (L/P atau Laki-laki)
            })->get();

            // Ambil Data Putri (P)
            $recordsPutri = (clone $queryBase)->whereHas('santri', function($q) {
                $q->where('jenis_kelamin', 'P');
            })->get();

            $pdf = Pdf::loadView('pengurus.perpulangan.pdf_export', compact('recordsPutra', 'recordsPutri', 'event', 'status'));
            return $pdf->download('perpulangan-' . $status . '.pdf');
        }

        return redirect()->back()->with('error', 'Format tidak dikenali');
    }
}