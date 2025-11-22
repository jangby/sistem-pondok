<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Pendidikan\KartuUjianTemplate;
use App\Models\Santri;
use App\Models\Pondok;
use App\Models\JadwalUjianDiniyah; // Pastikan Anda punya model ini
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class KartuUjianController extends Controller
{
    private function getPondokId()
    {
        $user = auth()->user();
        return $user->pondokStaff ? $user->pondokStaff->pondok_id : $user->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();
        $mustawas = Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get();
        $templates = KartuUjianTemplate::where('pondok_id', $pondokId)->where('is_active', true)->latest()->get();

        return view('pendidikan.admin.kartu.index', compact('mustawas', 'templates'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'mustawa_id' => 'required',
            'template_id' => 'required',
        ]);

        $pondokId = $this->getPondokId();
        $pondok = Pondok::find($pondokId);
        $template = KartuUjianTemplate::findOrFail($request->template_id);
        
        $santris = Santri::where('mustawa_id', $request->mustawa_id)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->get();

        if ($santris->isEmpty()) return back()->with('error', 'Tidak ada santri.');

        // Ambil Jadwal Ujian untuk kelas ini (Jika ada tabelnya)
        // Asumsi: Tabel 'jadwal_ujian_diniyahs' punya kolom 'mustawa_id'
        $jadwals = JadwalUjianDiniyah::where('mustawa_id', $request->mustawa_id)
                    ->with('mapel')
                    ->orderBy('tanggal')
                    ->orderBy('jam_mulai')
                    ->get();

        $cards = [];
        Carbon::setLocale('id');

        foreach ($santris as $santri) {
            $konten = $template->konten_html;

            // REPLACE IDENTITAS (Sama seperti Rapor)
            $konten = str_replace('{{nama_santri}}', strtoupper($santri->full_name), $konten);
            $konten = str_replace('{{nis}}', $santri->nis, $konten);
            $konten = str_replace('{{kelas}}', $santri->mustawa->nama ?? '-', $konten);
            $konten = str_replace('{{nama_pondok}}', $pondok->nama_pondok, $konten);
            
            // LOGO
            $logoHtml = '';
            $logoPath = public_path('storage/' . $pondok->logo); 
            if (!file_exists($logoPath) && !empty($pondok->logo)) $logoPath = public_path($pondok->logo);
            if (file_exists($logoPath) && !empty($pondok->logo)) {
                $b64 = base64_encode(file_get_contents($logoPath));
                $logoHtml = '<img src="data:image/png;base64,'.$b64.'" style="height: 60px;">';
            }
            $konten = str_replace('{{logo_pondok}}', $logoHtml, $konten);

            // TABEL JADWAL OTOMATIS
            if (str_contains($konten, '{{tabel_jadwal_ujian}}')) {
                $tabel = $this->generateTabelJadwal($jadwals);
                $konten = str_replace('{{tabel_jadwal_ujian}}', $tabel, $konten);
            }

            $cards[] = $konten;
        }

        $data = [
            'cards' => $cards,
            'template' => $template,
            'judul' => 'Kartu Ujian - ' . ($santris->first()->mustawa->nama ?? '')
        ];

        if ($request->has('download')) {
            $pdf = Pdf::loadView('pendidikan.admin.kartu.print', $data);
            $pdf->setPaper($template->ukuran_kertas, $template->orientasi);
            return $pdf->stream($data['judul'] . '.pdf');
        }

        return view('pendidikan.admin.kartu.print', $data);
    }

    private function generateTabelJadwal($jadwals)
    {
        if ($jadwals->isEmpty()) return '<p style="text-align:center; font-style:italic;">- Jadwal Belum Tersedia -</p>';

        $html = '<table style="width:100%; border-collapse: collapse; border: 1px solid black; font-size: 9pt;">
                    <tr style="background: #eee;">
                        <th style="border: 1px solid black; padding: 3px;">Hari/Tanggal</th>
                        <th style="border: 1px solid black; padding: 3px;">Jam</th>
                        <th style="border: 1px solid black; padding: 3px;">Mapel</th>
                        <th style="border: 1px solid black; padding: 3px;">Paraf</th>
                    </tr>';
        
        foreach ($jadwals as $jadwal) {
            $hari = Carbon::parse($jadwal->tanggal)->translatedFormat('l, d M Y');
            $jam = Carbon::parse($jadwal->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jadwal->jam_selesai)->format('H:i');
            
            $html .= '<tr>
                        <td style="border: 1px solid black; padding: 3px;">'.$hari.'</td>
                        <td style="border: 1px solid black; padding: 3px; text-align:center;">'.$jam.'</td>
                        <td style="border: 1px solid black; padding: 3px;">'.($jadwal->mapel->nama_mapel ?? '-').'</td>
                        <td style="border: 1px solid black; padding: 3px;"></td>
                      </tr>';
        }
        $html .= '</table>';
        return $html;
    }
}