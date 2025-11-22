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
        return $user->pondokStaff ? $user->pondokStaff->pondok_id : $user->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();
        if (!$pondokId) return redirect()->back()->with('error', 'Akun tidak valid.');

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
        
        // Ambil Santri di kelas ini
        // PERBAIKAN: orderBy('nama_lengkap') diubah menjadi orderBy('full_name')
        $santris = Santri::where('mustawa_id', $request->mustawa_id)
                         ->where('status', 'active') 
                         ->orderBy('full_name') // <--- INI YANG DIPERBAIKI
                         ->get();

        if ($santris->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada santri aktif di kelas ini.');
        }

        $raporSiapCetak = [];

        foreach ($santris as $santri) {
            $konten = $template->konten_html;

            // PERBAIKAN: Menggunakan $santri->full_name
            $konten = str_replace('{{nama_santri}}', $santri->full_name, $konten); // <--- INI JUGA DIPERBAIKI
            $konten = str_replace('{{nis}}', $santri->nis ?? '-', $konten);
            
            // Perbaikan Null Coalescing untuk relasi (jika data mustawa tidak lengkap)
            $namaKelas = $santri->mustawa ? $santri->mustawa->nama : '-';
            $tahunAjaran = $santri->mustawa ? $santri->mustawa->tahun_ajaran : date('Y/Y+1');
            
            $konten = str_replace('{{kelas}}', $namaKelas, $konten);
            $konten = str_replace('{{tahun_ajaran}}', $tahunAjaran, $konten);
            $konten = str_replace('{{semester}}', 'Ganjil/Genap', $konten);
            
            $konten = str_replace('{{nama_pondok}}', $pondok->nama_pondok, $konten);
            $konten = str_replace('{{alamat_pondok}}', $pondok->alamat, $konten);
            
            // Logo Pondok
            $logoPath = public_path('storage/' . $pondok->logo); 
            if (file_exists($logoPath) && !empty($pondok->logo)) {
                $logoBase64 = base64_encode(file_get_contents($logoPath));
                $imgTag = '<img src="data:image/png;base64,' . $logoBase64 . '" style="max-height: 80px;">';
                $konten = str_replace('{{logo_pondok}}', $imgTag, $konten);
            } else {
                // Jika logo tidak ada, hapus variabelnya
                $konten = str_replace('{{logo_pondok}}', '', $konten);
            }

            // Tabel Nilai
            if (str_contains($konten, '{{tabel_nilai}}')) {
                $tabelNilai = $this->generateTabelNilai($santri->id, $request->mustawa_id);
                $konten = str_replace('{{tabel_nilai}}', $tabelNilai, $konten);
            }

            // Tanda Tangan
            Carbon::setLocale('id');
            $konten = str_replace('{{titimangsa}}', Carbon::now()->translatedFormat('d F Y'), $konten);
            
            $namaWali = ($santri->mustawa && $santri->mustawa->waliUstadz) 
                        ? $santri->mustawa->waliUstadz->nama_lengkap 
                        : '................';
                        
            $konten = str_replace('{{wali_kelas}}', $namaWali, $konten);
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

    private function generateTabelNilai($santriId, $mustawaId)
    {
        // Pastikan model NilaiPesantren juga menggunakan nama kolom yang benar di database Anda
        $nilais = NilaiPesantren::where('santri_id', $santriId)
            ->where('mustawa_id', $mustawaId)
            ->with('mapel')
            ->get();

        if ($nilais->isEmpty()) {
            return '<p style="text-align:center; font-style:italic; color:red; border:1px dashed #ccc; padding:10px;">Belum ada data nilai yang diinput untuk kelas ini.</p>';
        }

        $html = '<table style="width:100%; border-collapse: collapse; border: 1px solid black; font-size: 10pt;">';
        $html .= '<thead>
                    <tr style="background-color: #f0f0f0;">
                        <th style="border: 1px solid black; padding: 5px; width: 5%;">No</th>
                        <th style="border: 1px solid black; padding: 5px;">Mata Pelajaran / Kitab</th>
                        <th style="border: 1px solid black; padding: 5px; width: 10%;">KKM</th>
                        <th style="border: 1px solid black; padding: 5px; width: 10%;">Nilai</th>
                        <th style="border: 1px solid black; padding: 5px; width: 15%;">Predikat</th>
                    </tr>
                  </thead><tbody>';

        $no = 1;
        $total = 0;
        foreach ($nilais as $nilai) {
            $predikat = $this->getPredikat($nilai->nilai_akhir);
            $html .= '<tr>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">' . $no++ . '</td>
                        <td style="border: 1px solid black; padding: 5px;">' . ($nilai->mapel->nama_mapel ?? '-') . ' <br><small><i>' . ($nilai->mapel->nama_kitab ?? '') . '</i></small></td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">' . ($nilai->mapel->kkm ?? 60) . '</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center; font-weight: bold;">' . $nilai->nilai_akhir . '</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">' . $predikat . '</td>
                      </tr>';
            $total += $nilai->nilai_akhir;
        }
        
        $rataRata = $nilais->count() > 0 ? round($total / $nilais->count(), 1) : 0;
        $html .= '<tr>
                    <td colspan="3" style="border: 1px solid black; padding: 5px; text-align: right; font-weight: bold;">Rata-rata</td>
                    <td colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; font-weight: bold;">' . $rataRata . '</td>
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