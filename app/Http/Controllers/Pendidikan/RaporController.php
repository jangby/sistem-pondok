<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Pendidikan\RaporTemplate;
use App\Models\Santri;
use App\Models\NilaiPesantren;
use App\Models\Pondok;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RaporController extends Controller
{
    private function getPondokId()
    {
        $user = auth()->user();
        if ($user->pondokStaff) {
            return $user->pondokStaff->pondok_id;
        }
        return $user->pondok_id; 
    }

    public function index()
    {
        $pondokId = $this->getPondokId();
        if (!$pondokId) return redirect()->back()->with('error', 'Akun tidak terhubung dengan data pondok.');

        $mustawas = Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get();
        $templates = RaporTemplate::where('pondok_id', $pondokId)->where('is_active', true)->latest()->get();

        return view('pendidikan.admin.rapor.index', compact('mustawas', 'templates'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'mustawa_id' => 'required',
            'template_id' => 'required',
        ]);

        $pondokId = $this->getPondokId();
        $pondok = Pondok::find($pondokId);
        $template = RaporTemplate::findOrFail($request->template_id);
        
        $santris = Santri::where('mustawa_id', $request->mustawa_id)
                          ->where('status', 'active')
                          ->with(['orangTua', 'mustawa']) 
                          ->orderBy('full_name')
                          ->get();

        if ($santris->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada santri aktif di kelas ini.');
        }

        $raporSiapCetak = [];
        Carbon::setLocale('id'); 

        foreach ($santris as $santri) {
            // Ambil konten HTML template
            $konten = $template->konten_html;

            // --- 1. PREPARE DATA ---
            
            // Data Santri (Prioritas ambil dari tabel santris kolom baru)
            $namaSantri = $santri->full_name;
            $nis        = $santri->nis ?? '-';
            $nisn       = $santri->nisn ?? '-'; // Jika kolom nisn sudah ditambahkan
            $nik        = $santri->nik ?? '-';  // Jika kolom nik sudah ditambahkan
            
            // Alamat & Domisili (Ambil dari tabel Santri update terbaru)
            $alamat     = $santri->alamat ?? '-';
            $kota       = $santri->kabupaten ?? $santri->kota ?? $santri->desa ?? '-'; // Sesuaikan dengan struktur baru
            
            // Tempat Tanggal Lahir
            $tmpLahir   = $santri->tempat_lahir ?? '-';
            $tglLahirRaw= $santri->tanggal_lahir;
            $tglLahir   = $tglLahirRaw ? Carbon::parse($tglLahirRaw)->translatedFormat('d F Y') : '-';
            $ttl        = $tmpLahir . ', ' . $tglLahir;

            // Gender
            $jkCode     = $santri->jenis_kelamin;
            $jk         = ($jkCode == 'L' || $jkCode == 'Laki-laki') ? 'Laki-laki' : 'Perempuan';

            // --- DATA ORANG TUA (PERBAIKAN DISINI) ---
            // Kita ambil langsung dari tabel santris (data EMIS)
            // Jika kosong, baru kita coba ambil dari relasi orangTua (akun login)
            
            $namaAyah   = $santri->nama_ayah ?? '-';
            $namaIbu    = $santri->nama_ibu ?? '-';
            
            // Logika Nama Wali di Rapor:
            // Prioritas: Nama Ayah -> Nama Ibu -> Nama Akun Wali -> '-'
            if (!empty($santri->nama_ayah)) {
                $namaWali = $santri->nama_ayah;
            } elseif (!empty($santri->nama_ibu)) {
                $namaWali = $santri->nama_ibu;
            } else {
                $namaWali = $santri->orangTua->name ?? '-';
            }

            // Pekerjaan (Ambil dari tabel santris)
            $jobAyah    = $santri->pekerjaan_ayah ?? '-';
            
            // No HP (Biasanya masih tersimpan di tabel akun orang_tuas)
            $hpWali     = $santri->orangTua->phone ?? '-';


            // --- 2. REPLACE VARIABLES ---
            
            // Identitas
            $konten = $this->replaceVar($konten, 'nama_santri', strtoupper($namaSantri));
            $konten = $this->replaceVar($konten, 'nis', $nis);
            $konten = $this->replaceVar($konten, 'nisn', $nisn);
            $konten = $this->replaceVar($konten, 'nik', $nik);
            $konten = $this->replaceVar($konten, 'ttl', $ttl);
            $konten = $this->replaceVar($konten, 'tempat_lahir', $tmpLahir);
            $konten = $this->replaceVar($konten, 'tanggal_lahir', $tglLahir);
            $konten = $this->replaceVar($konten, 'jenis_kelamin', $jk);
            $konten = $this->replaceVar($konten, 'alamat', $alamat);
            $konten = $this->replaceVar($konten, 'kota_asal', $kota);

            // Orang Tua
            $konten = $this->replaceVar($konten, 'nama_ayah', $namaAyah);
            $konten = $this->replaceVar($konten, 'nama_ibu', $namaIbu);
            $konten = $this->replaceVar($konten, 'nama_wali', $namaWali);
            $konten = $this->replaceVar($konten, 'pekerjaan_ayah', $jobAyah);
            $konten = $this->replaceVar($konten, 'no_hp_wali', $hpWali);

            // Akademik & Pondok
            $namaKelas   = $santri->mustawa ? $santri->mustawa->nama : '-';
            $tahunAjaran = $santri->mustawa ? $santri->mustawa->tahun_ajaran : date('Y') . '/' . (date('Y')+1);
            
            $konten = $this->replaceVar($konten, 'kelas', $namaKelas);
            $konten = $this->replaceVar($konten, 'tahun_ajaran', $tahunAjaran);
            $konten = $this->replaceVar($konten, 'semester', 'Ganjil/Genap'); 
            $konten = $this->replaceVar($konten, 'nama_pondok', strtoupper($pondok->nama_pondok));
            $konten = $this->replaceVar($konten, 'alamat_pondok', $pondok->alamat);
            $konten = $this->replaceVar($konten, 'kota_pondok', $pondok->kota ?? 'Tasikmalaya');

            // Keputusan Naik Kelas
            $nilaiAkhir = NilaiPesantren::where('santri_id', $santri->id)
                ->where('mustawa_id', $request->mustawa_id)
                ->avg('nilai_akhir');
            
            $keputusanText = ($nilaiAkhir >= 60) ? "NAIK KE TINGKAT BERIKUTNYA" : "TINGGAL DI KELAS YANG SAMA";
            $konten = $this->replaceVar($konten, 'keputusan', $keputusanText);

            // Logo
            $logoHtml = '';
            // Cek path storage (linked) dan public biasa
            $pathsToCheck = [
                public_path('storage/' . $pondok->logo),
                public_path($pondok->logo)
            ];

            foreach ($pathsToCheck as $path) {
                if (!empty($pondok->logo) && file_exists($path)) {
                    try {
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        $logoHtml = '<img src="' . $base64 . '" style="width: 100px; height: auto;">';
                        break; // Berhenti jika ketemu
                    } catch (\Exception $e) { 
                        // silent error
                    }
                }
            }
            $konten = $this->replaceVar($konten, 'logo_pondok', $logoHtml);


            // --- 3. GENERATE TABEL NILAI ---
            if (str_contains($konten, 'tabel_nilai_tulis')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_tulis', $this->generateTabelKategori($santri->id, $request->mustawa_id, 'tulis'));
            }
            if (str_contains($konten, 'tabel_nilai_lisan')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_lisan', $this->generateTabelKategori($santri->id, $request->mustawa_id, 'lisan'));
            }
            if (str_contains($konten, 'tabel_nilai_praktek')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_praktek', $this->generateTabelKategori($santri->id, $request->mustawa_id, 'praktek'));
            }
            if (str_contains($konten, 'tabel_nilai_hafalan')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_hafalan', $this->generateTabelKategori($santri->id, $request->mustawa_id, 'hafalan'));
            }
            if (str_contains($konten, 'nilai_kehadiran_total')) {
                $maxKehadiran = NilaiPesantren::where('santri_id', $santri->id)
                    ->where('mustawa_id', $request->mustawa_id)
                    // Anda bisa tambahkan where('semester', ...) jika variabel semester tersedia di scope ini
                    ->max('nilai_kehadiran');
                
                $nilaiKehadiranFinal = $maxKehadiran ? round($maxKehadiran) : 0;
                $predikatKehadiran = $this->getPredikat($nilaiKehadiranFinal);

                // Jika template rapor meminta angka tunggal (bukan tabel)
                $konten = $this->replaceVar($konten, 'nilai_kehadiran_total', $nilaiKehadiranFinal);
                $konten = $this->replaceVar($konten, 'predikat_kehadiran_total', $predikatKehadiran);
            }
            if (str_contains($konten, 'tabel_nilai_absensi')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_absensi', $this->generateTabelKategori($santri->id, $request->mustawa_id, 'absensi'));
            }
            if (str_contains($konten, 'tabel_nilai')) { 
                 $konten = str_replace('{{tabel_nilai}}', $this->generateTabelKategori($santri->id, $request->mustawa_id, 'lengkap'), $konten);
            }
            

            // Data Rekap Absensi Default
            $konten = $this->replaceVar($konten, 'sakit', '0');
            $konten = $this->replaceVar($konten, 'izin', '0');
            $konten = $this->replaceVar($konten, 'alpha', '0');

            // Tanda Tangan
            $tglCetak = Carbon::now()->translatedFormat('d F Y');
            $konten = $this->replaceVar($konten, 'titimangsa', ($pondok->kota ?? 'Garut') . ', ' . $tglCetak);
            
            $namaWaliKelas = ($santri->mustawa && $santri->mustawa->waliUstadz) 
                            ? $santri->mustawa->waliUstadz->nama_lengkap 
                            : '.........................';
            $konten = $this->replaceVar($konten, 'wali_kelas', $namaWaliKelas);
            $konten = $this->replaceVar($konten, 'kepala_pondok', 'K.H. Pimpinan Pondok');

            $raporSiapCetak[] = $konten;
        }

        $data = [
            'rapors' => $raporSiapCetak,
            'template' => $template,
            'judul' => 'Rapor ' . ($santris->first()->mustawa->nama ?? 'Santri')
        ];

        if ($request->has('download')) {
            $pdf = Pdf::loadView('pendidikan.admin.rapor.print', $data);
            $pdf->setPaper($template->ukuran_kertas, $template->orientasi);
            return $pdf->stream($data['judul'] . '.pdf');
        }

        return view('pendidikan.admin.rapor.print', $data);
    }

    // ... (Fungsi replaceVar, generateTabelKategori, dan getPredikat biarkan sama) ...
    
    private function replaceVar($content, $varName, $value)
    {
        $content = str_replace('{{'.$varName.'}}', $value, $content);
        return $content;
    }

    private function generateTabelKategori($santriId, $mustawaId, $kategori)
    {
        $query = NilaiPesantren::where('santri_id', $santriId)
            ->where('mustawa_id', $mustawaId)
            ->with('mapel');

        if ($kategori == 'tulis') {
            $query->whereHas('mapel', function($q) { $q->where('uji_tulis', true); });
        } elseif ($kategori == 'lisan') {
            $query->whereHas('mapel', function($q) { $q->where('uji_lisan', true); });
        } elseif ($kategori == 'praktek') {
            $query->whereHas('mapel', function($q) { $q->where('uji_praktek', true); });
        } elseif ($kategori == 'hafalan') { // [BARU] Filter Hafalan
            $query->whereHas('mapel', function($q) { $q->where('uji_hafalan', true); });
        }

        $nilais = $query->get();

        if ($nilais->isEmpty()) {
            return '<div style="text-align:center; font-style:italic; padding:10px; border:1px dashed #999; font-size:10pt;">- Tidak ada nilai untuk kategori ini -</div>';
        }

        $html = '<table style="width:100%; border-collapse: collapse; border: 1px solid black; font-size: 11pt;">';
        $html .= '<thead>
                    <tr style="background-color: #e0e0e0;">
                        <th style="border: 1px solid black; padding: 6px; width: 5%; text-align: center;">No</th>
                        <th style="border: 1px solid black; padding: 6px; text-align: center;">Mata Pelajaran</th>
                        <th style="border: 1px solid black; padding: 6px; width: 10%; text-align: center;">KKM</th>';
        
        $labelNilai = 'Nilai Akhir';
        if ($kategori == 'tulis') $labelNilai = 'Nilai Tulis';
        elseif ($kategori == 'lisan') $labelNilai = 'Nilai Lisan';
        elseif ($kategori == 'praktek') $labelNilai = 'Nilai Praktek';
        elseif ($kategori == 'absensi') $labelNilai = 'Kehadiran';

        $html .= '<th style="border: 1px solid black; padding: 6px; width: 15%; text-align: center;">'.$labelNilai.'</th>';
        $html .= '<th style="border: 1px solid black; padding: 6px; width: 20%; text-align: center;">Predikat</th>
                    </tr>
                  </thead><tbody>';

        $no = 1;
        $totalNilai = 0;
        $countMapel = 0;

        foreach ($nilais as $nilai) {
            $angka = 0;
            if ($kategori == 'tulis') $angka = $nilai->nilai_tulis;
            elseif ($kategori == 'lisan') $angka = $nilai->nilai_lisan;
            elseif ($kategori == 'praktek') $angka = $nilai->nilai_praktek;
            elseif ($kategori == 'hafalan') $angka = $nilai->nilai_hafalan; // [BARU]
            elseif ($kategori == 'absensi') {
                 // LOGIKA LAMA (Per Mapel), nanti kita handle "Single Source" di function generate() utama
                 $angka = $nilai->nilai_kehadiran ?? 0;
            }
            else $angka = $nilai->nilai_akhir;

            $predikat = $this->getPredikat($angka);
            $namaMapel = $nilai->mapel->nama_mapel ?? 'Tanpa Nama';

            $html .= '<tr>
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">' . $no++ . '</td>
                        <td style="border: 1px solid black; padding: 4px 8px;">' . $namaMapel . '</td>
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">' . ($nilai->mapel->kkm ?? 60) . '</td>
                        <td style="border: 1px solid black; padding: 4px; text-align: center; font-weight: bold;">' . $angka . '</td>
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">' . $predikat . '</td>
                      </tr>';
            
            $totalNilai += $angka;
            $countMapel++;
        }
        
        $rataRata = $countMapel > 0 ? round($totalNilai / $countMapel, 2) : 0;
        $html .= '<tr style="background-color: #f9f9f9;">
                    <td colspan="3" style="border: 1px solid black; padding: 6px; text-align: right; font-weight: bold;">Rata-Rata Nilai</td>
                    <td colspan="2" style="border: 1px solid black; padding: 6px; text-align: center; font-weight: bold;">' . $rataRata . '</td>
                  </tr>';

        $html .= '</tbody></table>';

        return $html;
    }

    private function getPredikat($nilai)
    {
        if ($nilai >= 90) return 'Mumtaz (Istimewa)';
        if ($nilai >= 80) return 'Jayyid Jiddan (Sangat Baik)';
        if ($nilai >= 70) return 'Jayyid (Baik)';
        if ($nilai >= 60) return 'Maqbul (Cukup)';
        return 'Rasib (Kurang)';
    }
}