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
        
        // Ambil Santri
        $santris = Santri::where('mustawa_id', $request->mustawa_id)
                         ->where('status', 'active')
                         ->with('orangTua')
                         ->orderBy('full_name')
                         ->get();

        if ($santris->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada santri aktif di kelas ini.');
        }

        $raporSiapCetak = [];
        Carbon::setLocale('id');

        foreach ($santris as $santri) {
            $konten = $template->konten_html;

            // --- 1. DATA PRIBADI & UMUM ---
            $konten = str_replace('{{nama_santri}}', $santri->full_name, $konten);
            $konten = str_replace('{{nis}}', $santri->nis ?? '-', $konten);
            $konten = str_replace('{{nisn}}', $santri->nisn ?? '-', $konten);
            $konten = str_replace('{{nik}}', $santri->nik ?? '-', $konten);
            
            $tglLahir = $santri->tanggal_lahir ? Carbon::parse($santri->tanggal_lahir)->translatedFormat('d F Y') : '-';
            $ttl = ($santri->tempat_lahir ?? '-') . ', ' . $tglLahir;
            $konten = str_replace('{{ttl}}', $ttl, $konten);
            $konten = str_replace('{{jenis_kelamin}}', $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan', $konten);
            $konten = str_replace('{{alamat}}', $santri->alamat ?? '-', $konten);
            $konten = str_replace('{{kelas}}', $santri->mustawa->nama ?? '-', $konten);
            $konten = str_replace('{{tahun_ajaran}}', $santri->mustawa->tahun_ajaran ?? date('Y/Y+1'), $konten);
            $konten = str_replace('{{semester}}', 'Ganjil/Genap', $konten);
            $konten = str_replace('{{nama_pondok}}', $pondok->nama_pondok, $konten);
            $konten = str_replace('{{alamat_pondok}}', $pondok->alamat, $konten);

            // Data Wali
            $namaAyah = $santri->orangTua->nama_ayah ?? '-';
            $konten = str_replace('{{nama_ayah}}', $namaAyah, $konten);
            $konten = str_replace('{{nama_ibu}}', $santri->orangTua->nama_ibu ?? '-', $konten);
            $konten = str_replace('{{nama_wali}}', $namaAyah, $konten);
            $konten = str_replace('{{pekerjaan_ayah}}', $santri->orangTua->pekerjaan_ayah ?? '-', $konten);
            $konten = str_replace('{{no_hp_wali}}', $santri->orangTua->no_hp ?? '-', $konten);

            // Logo
            $logoPath = public_path('storage/' . $pondok->logo); 
            if (file_exists($logoPath) && !empty($pondok->logo)) {
                $logoBase64 = base64_encode(file_get_contents($logoPath));
                $imgTag = '<img src="data:image/png;base64,' . $logoBase64 . '" style="max-height: 80px;">';
                $konten = str_replace('{{logo_pondok}}', $imgTag, $konten);
            } else {
                $konten = str_replace('{{logo_pondok}}', '', $konten);
            }

            // --- 2. GENERATE TABEL NILAI PER KATEGORI ---
            if (str_contains($konten, '{{tabel_nilai_tulis}}')) {
                $tabelTulis = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'tulis');
                $konten = str_replace('{{tabel_nilai_tulis}}', $tabelTulis, $konten);
            }

            if (str_contains($konten, '{{tabel_nilai_lisan}}')) {
                $tabelLisan = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'lisan');
                $konten = str_replace('{{tabel_nilai_lisan}}', $tabelLisan, $konten);
            }

            if (str_contains($konten, '{{tabel_nilai_praktek}}')) {
                $tabelPraktek = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'praktek');
                $konten = str_replace('{{tabel_nilai_praktek}}', $tabelPraktek, $konten);
            }

            if (str_contains($konten, '{{tabel_nilai_absensi}}')) {
                $tabelAbsen = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'absensi');
                $konten = str_replace('{{tabel_nilai_absensi}}', $tabelAbsen, $konten);
            }

            if (str_contains($konten, '{{tabel_nilai}}')) {
                $tabelLengkap = $this->generateTabelKategori($santri->id, $request->mustawa_id, 'lengkap');
                $konten = str_replace('{{tabel_nilai}}', $tabelLengkap, $konten);
            }

            // Tanda Tangan
            $konten = str_replace('{{titimangsa}}', Carbon::now()->translatedFormat('d F Y'), $konten);
            $namaWaliKelas = ($santri->mustawa && $santri->mustawa->waliUstadz) ? $santri->mustawa->waliUstadz->nama_lengkap : '................';
            $konten = str_replace('{{wali_kelas}}', $namaWaliKelas, $konten);
            $konten = str_replace('{{kepala_pondok}}', 'Kyai Pengasuh', $konten);

            $raporSiapCetak[] = $konten;
        }

        $data = [
            'rapors' => $raporSiapCetak,
            'template' => $template,
            'judul' => $template->nama_template . ' - ' . ($santris->first()->mustawa->nama ?? 'Kelas')
        ];

        if ($request->has('download')) {
            $pdf = Pdf::loadView('pendidikan.admin.rapor.print', $data);
            $pdf->setPaper($template->ukuran_kertas, $template->orientasi);
            return $pdf->stream($data['judul'] . '.pdf');
        }

        return view('pendidikan.admin.rapor.print', $data);
    }

    /**
     * Fungsi dinamis membuat tabel berdasarkan kategori
     */
    private function generateTabelKategori($santriId, $mustawaId, $kategori)
    {
        // Query Dasar
        $query = NilaiPesantren::where('santri_id', $santriId)
            ->where('mustawa_id', $mustawaId)
            ->with('mapel');

        if ($kategori == 'tulis') {
            $query->whereHas('mapel', function($q) { $q->where('uji_tulis', true); });
        } elseif ($kategori == 'lisan') {
            $query->whereHas('mapel', function($q) { $q->where('uji_lisan', true); });
        } elseif ($kategori == 'praktek') {
            $query->whereHas('mapel', function($q) { $q->where('uji_praktek', true); });
        }

        $nilais = $query->get();

        if ($nilais->isEmpty()) {
            return '<p style="text-align:center; font-style:italic; font-size:10pt;">- Tidak ada nilai -</p>';
        }

        // Header Tabel
        $html = '<table style="width:100%; border-collapse: collapse; border: 1px solid black; font-size: 10pt;">';
        $html .= '<thead>
                    <tr style="background-color: #f0f0f0;">
                        <th style="border: 1px solid black; padding: 5px; width: 5%; text-align: center;">No</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: left;">Mata Pelajaran</th>
                        <th style="border: 1px solid black; padding: 5px; width: 10%; text-align: center;">KKM</th>';
        
        if ($kategori == 'tulis') $html .= '<th style="border: 1px solid black; padding: 5px; width: 15%; text-align: center;">Nilai Tulis</th>';
        elseif ($kategori == 'lisan') $html .= '<th style="border: 1px solid black; padding: 5px; width: 15%; text-align: center;">Nilai Lisan</th>';
        elseif ($kategori == 'praktek') $html .= '<th style="border: 1px solid black; padding: 5px; width: 15%; text-align: center;">Nilai Praktek</th>';
        elseif ($kategori == 'absensi') $html .= '<th style="border: 1px solid black; padding: 5px; width: 15%; text-align: center;">Kehadiran (%)</th>';
        else $html .= '<th style="border: 1px solid black; padding: 5px; width: 15%; text-align: center;">Nilai Akhir</th>';

        $html .= '<th style="border: 1px solid black; padding: 5px; width: 20%; text-align: center;">Predikat</th>
                    </tr>
                  </thead><tbody>';

        $no = 1;
        $total = 0;
        $count = 0;

        foreach ($nilais as $nilai) {
            $nilaiAngka = 0;
            if ($kategori == 'tulis') $nilaiAngka = $nilai->nilai_tulis;
            elseif ($kategori == 'lisan') $nilaiAngka = $nilai->nilai_lisan;
            elseif ($kategori == 'praktek') $nilaiAngka = $nilai->nilai_praktek;
            elseif ($kategori == 'absensi') $nilaiAngka = $nilai->nilai_kehadiran;
            else $nilaiAngka = $nilai->nilai_akhir;

            $predikat = $this->getPredikat($nilaiAngka);

            // --- PERBAIKAN DI SINI: Hapus nama kitab dari tampilan ---
            $html .= '<tr>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">' . $no++ . '</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: left;">' . ($nilai->mapel->nama_mapel ?? '-') . '</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">' . ($nilai->mapel->kkm ?? 60) . '</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center; font-weight: bold;">' . $nilaiAngka . '</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">' . $predikat . '</td>
                      </tr>';
            
            $total += $nilaiAngka;
            $count++;
        }
        
        $rataRata = $count > 0 ? round($total / $count, 1) : 0;
        $html .= '<tr>
                    <td colspan="3" style="border: 1px solid black; padding: 5px; text-align: right; font-weight: bold;">Rata-rata</td>
                    <td colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; font-weight: bold;">' . $rataRata . '</td>
                  </tr>';

        $html .= '</tbody></table>';

        return $html;
    }

    private function getPredikat($nilai)
    {
        if ($nilai >= 90) return 'Mumtaz';
        if ($nilai >= 80) return 'Jayyid Jiddan';
        if ($nilai >= 70) return 'Jayyid';
        if ($nilai >= 60) return 'Maqbul';
        return 'Rasib';
    }
}