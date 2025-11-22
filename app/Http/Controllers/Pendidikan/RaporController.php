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
    /**
     * Helper untuk mendapatkan Pondok ID dengan aman
     * Mengecek apakah user adalah staff atau admin utama
     */
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

        // Ambil data kelas aktif
        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->get();

        // Ambil template rapor aktif
        $templates = RaporTemplate::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->latest()
            ->get();

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
        
        // Ambil Santri di kelas ini dengan relasi Orang Tua dan Mustawa
        $santris = Santri::where('mustawa_id', $request->mustawa_id)
                         ->where('status', 'active')
                         ->with(['orangTua', 'mustawa']) 
                         ->orderBy('full_name')
                         ->get();

        if ($santris->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada santri aktif di kelas ini.');
        }

        $raporSiapCetak = [];
        Carbon::setLocale('id'); // Format tanggal bahasa Indonesia

        foreach ($santris as $santri) {
            $konten = $template->konten_html;

            // --- 1. LOGO PONDOK (Image Handling) ---
            // Mengubah gambar menjadi Base64 agar muncul di PDF DomPDF
            $logoHtml = '';
            $logoPath = public_path('storage/' . $pondok->logo); 
            
            // Cek path alternatif jika upload manual
            if (!file_exists($logoPath) && !empty($pondok->logo)) {
                 $logoPath = public_path($pondok->logo);
            }

            if (file_exists($logoPath) && !empty($pondok->logo)) {
                try {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    $logoHtml = '<img src="' . $base64 . '" style="max-height: 100px; width: auto;">';
                } catch (\Exception $e) {
                    $logoHtml = ''; // Fallback jika gagal load gambar
                }
            }
            $konten = str_replace('{{logo_pondok}}', $logoHtml, $konten);


            // --- 2. DATA PRIBADI SANTRI ---
            $konten = str_replace('{{nama_santri}}', strtoupper($santri->full_name), $konten);
            $konten = str_replace('{{nis}}', $santri->nis ?? '-', $konten);
            $konten = str_replace('{{nisn}}', $santri->nisn ?? '-', $konten);
            $konten = str_replace('{{nik}}', $santri->nik ?? '-', $konten);
            
            // Format Tanggal Lahir: "Tasikmalaya, 17 Agustus 2010"
            $tglLahir = $santri->tanggal_lahir ? Carbon::parse($santri->tanggal_lahir)->translatedFormat('d F Y') : '-';
            $ttl = ($santri->tempat_lahir ?? '-') . ', ' . $tglLahir;
            $konten = str_replace('{{ttl}}', $ttl, $konten);
            
            $jk = $santri->jenis_kelamin == 'L' ? 'Laki-laki' : ($santri->jenis_kelamin == 'P' ? 'Perempuan' : '-');
            $konten = str_replace('{{jenis_kelamin}}', $jk, $konten);
            
            $konten = str_replace('{{alamat}}', $santri->alamat ?? '-', $konten);
            $konten = str_replace('{{kota_asal}}', $santri->kota ?? '-', $konten);


            // --- 3. DATA ORANG TUA / WALI ---
            // Menggunakan relasi 'orangTua' dari Model Santri
            $wali = $santri->orangTua;
            $konten = str_replace('{{nama_ayah}}', $wali->nama_ayah ?? '-', $konten);
            $konten = str_replace('{{nama_ibu}}', $wali->nama_ibu ?? '-', $konten);
            // Nama wali default ke Ayah, jika tidak ada ke Ibu
            $namaWali = $wali ? ($wali->nama_ayah ?? $wali->nama_ibu ?? '-') : '-';
            $konten = str_replace('{{nama_wali}}', $namaWali, $konten);
            $konten = str_replace('{{pekerjaan_ayah}}', $wali->pekerjaan_ayah ?? '-', $konten);
            $konten = str_replace('{{no_hp_wali}}', $wali->no_hp ?? '-', $konten);


            // --- 4. DATA AKADEMIK PONDOK ---
            $namaKelas = $santri->mustawa ? $santri->mustawa->nama : '-';
            $tahunAjaran = $santri->mustawa ? $santri->mustawa->tahun_ajaran : date('Y') . '/' . (date('Y')+1);
            
            $konten = str_replace('{{kelas}}', $namaKelas, $konten);
            $konten = str_replace('{{tahun_ajaran}}', $tahunAjaran, $konten);
            $konten = str_replace('{{semester}}', 'Ganjil/Genap', $konten); // Bisa dikembangkan jadi inputan
            $konten = str_replace('{{nama_pondok}}', strtoupper($pondok->nama_pondok), $konten);
            $konten = str_replace('{{alamat_pondok}}', $pondok->alamat, $konten);
            $konten = str_replace('{{kota_pondok}}', $pondok->kota ?? 'Tasikmalaya', $konten);


            // --- 5. LOGIKA KEPUTUSAN (NAIK KELAS / LULUS) ---
            // Menghitung rata-rata nilai akhir dari seluruh mapel di kelas ini
            $nilaiRataRata = NilaiPesantren::where('santri_id', $santri->id)
                ->where('mustawa_id', $request->mustawa_id)
                ->avg('nilai_akhir');
            
            // Logika sederhana: KKM rata-rata 60
            if ($nilaiRataRata >= 60) {
                $keputusan = "NAIK KE TINGKAT BERIKUTNYA";
            } else {
                $keputusan = "TINGGAL DI KELAS YANG SAMA";
            }
            $konten = str_replace('{{keputusan}}', $keputusan, $konten);


            // --- 6. GENERATE TABEL NILAI (Dinamis) ---
            
            // Tabel Ujian Tulis (Tahriri)
            if (str_contains($konten, '{{tabel_nilai_tulis}}')) {
                $tabel = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'tulis');
                $konten = str_replace('{{tabel_nilai_tulis}}', $tabel, $konten);
            }

            // Tabel Ujian Lisan (Syafahi)
            if (str_contains($konten, '{{tabel_nilai_lisan}}')) {
                $tabel = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'lisan');
                $konten = str_replace('{{tabel_nilai_lisan}}', $tabel, $konten);
            }

            // Tabel Ujian Praktek (Amaliyah)
            if (str_contains($konten, '{{tabel_nilai_praktek}}')) {
                $tabel = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'praktek');
                $konten = str_replace('{{tabel_nilai_praktek}}', $tabel, $konten);
            }

            // Tabel Kehadiran Mapel (Absensi)
            if (str_contains($konten, '{{tabel_nilai_absensi}}')) {
                $tabel = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'absensi');
                $konten = str_replace('{{tabel_nilai_absensi}}', $tabel, $konten);
            }

            // Tabel Gabungan (Fallback jika pakai variabel lama)
            if (str_contains($konten, '{{tabel_nilai}}')) {
                $tabel = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'lengkap');
                $konten = str_replace('{{tabel_nilai}}', $tabel, $konten);
            }


            // --- 7. TANDA TANGAN & TANGGAL ---
            $tanggalCetak = Carbon::now()->translatedFormat('d F Y');
            // Ambil kota pondok jika ada, default Tasikmalaya
            $kota = $pondok->kota ?? 'Tasikmalaya'; 
            $titimangsa = $kota . ', ' . $tanggalCetak;
            
            $konten = str_replace('{{titimangsa}}', $titimangsa, $konten);
            
            $namaWaliKelas = ($santri->mustawa && $santri->mustawa->waliUstadz) 
                        ? $santri->mustawa->waliUstadz->nama_lengkap 
                        : '.........................';
            $konten = str_replace('{{wali_kelas}}', $namaWaliKelas, $konten);
            $konten = str_replace('{{kepala_pondok}}', 'K.H. Pimpinan Pondok', $konten); // Bisa diganti variabel global

            // Masukkan ke array siap cetak
            $raporSiapCetak[] = $konten;
        }

        $data = [
            'rapors' => $raporSiapCetak,
            'template' => $template,
            'judul' => 'Rapor ' . ($santris->first()->mustawa->nama ?? 'Santri')
        ];

        // Mode Download PDF
        if ($request->has('download')) {
            $pdf = Pdf::loadView('pendidikan.admin.rapor.print', $data);
            // Set ukuran kertas sesuai template
            $pdf->setPaper($template->ukuran_kertas, $template->orientasi);
            return $pdf->stream($data['judul'] . '.pdf');
        }

        // Mode Preview HTML (Untuk Debugging layout)
        return view('pendidikan.admin.rapor.print', $data);
    }

    /**
     * Fungsi untuk membuat tabel nilai HTML secara dinamis
     */
    private function generateTabelKategori($santriId, $mustawaId, $kategori)
    {
        // Query Nilai + Mapel
        $query = NilaiPesantren::where('santri_id', $santriId)
            ->where('mustawa_id', $mustawaId)
            ->with('mapel');

        // Filter Mapel berdasarkan jenis ujiannya
        if ($kategori == 'tulis') {
            $query->whereHas('mapel', function($q) { $q->where('uji_tulis', true); });
        } elseif ($kategori == 'lisan') {
            $query->whereHas('mapel', function($q) { $q->where('uji_lisan', true); });
        } elseif ($kategori == 'praktek') {
            $query->whereHas('mapel', function($q) { $q->where('uji_praktek', true); });
        }
        // 'absensi' dan 'lengkap' mengambil semua mapel yang ada nilainya

        $nilais = $query->get();

        // Jika tidak ada data nilai di kategori ini
        if ($nilais->isEmpty()) {
            return '<p style="text-align:center; font-style:italic; font-size:10pt; color:#666;">- Tidak ada mata pelajaran untuk kategori ini -</p>';
        }

        // Header Tabel
        $html = '<table style="width:100%; border-collapse: collapse; border: 1px solid black; font-size: 11pt;">';
        $html .= '<thead>
                    <tr style="background-color: #e0e0e0;">
                        <th style="border: 1px solid black; padding: 6px; width: 5%; text-align: center;">No</th>
                        <th style="border: 1px solid black; padding: 6px; text-align: center;">Mata Pelajaran</th>
                        <th style="border: 1px solid black; padding: 6px; width: 10%; text-align: center;">KKM</th>';
        
        // Judul Kolom Nilai Dinamis
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
            // Ambil nilai sesuai kategori kolom database
            $angka = 0;
            if ($kategori == 'tulis') $angka = $nilai->nilai_tulis;
            elseif ($kategori == 'lisan') $angka = $nilai->nilai_lisan;
            elseif ($kategori == 'praktek') $angka = $nilai->nilai_praktek;
            elseif ($kategori == 'absensi') $angka = $nilai->nilai_kehadiran ?? 100; // Fallback 100 jika null
            else $angka = $nilai->nilai_akhir;

            // Tentukan Predikat
            $predikat = $this->getPredikat($angka);
            
            // Nama Mapel Saja (Tanpa Kitab agar ringkas)
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
        
        // Baris Rata-rata (Footer Tabel)
        $rataRata = $countMapel > 0 ? round($totalNilai / $countMapel, 2) : 0;
        $html .= '<tr style="background-color: #f9f9f9;">
                    <td colspan="3" style="border: 1px solid black; padding: 6px; text-align: right; font-weight: bold;">Rata-Rata Nilai</td>
                    <td colspan="2" style="border: 1px solid black; padding: 6px; text-align: center; font-weight: bold;">' . $rataRata . '</td>
                  </tr>';

        $html .= '</tbody></table>';

        return $html;
    }

    /**
     * Menentukan predikat berdasarkan skor
     */
    private function getPredikat($nilai)
    {
        if ($nilai >= 90) return 'Mumtaz (Istimewa)';
        if ($nilai >= 80) return 'Jayyid Jiddan (Sangat Baik)';
        if ($nilai >= 70) return 'Jayyid (Baik)';
        if ($nilai >= 60) return 'Maqbul (Cukup)';
        return 'Rasib (Kurang)';
    }
}