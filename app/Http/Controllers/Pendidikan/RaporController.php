<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Pendidikan\RaporTemplate;
use App\Models\Santri;
use App\Models\NilaiPesantren;
use App\Models\MapelDiniyah; 
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

            // --- 1. PREPARE DATA IDENTITAS ---
            
            // Data Santri
            $namaSantri = $santri->full_name;
            $nis        = $santri->nis ?? '-';
            $nisn       = $santri->nisn ?? '-';
            $nik        = $santri->nik ?? '-';
            
            // Alamat
            $alamat     = $santri->alamat ?? '-';
            $kota       = $santri->kabupaten ?? $santri->kota ?? $santri->desa ?? '-';
            
            // TTL
            $tmpLahir   = $santri->tempat_lahir ?? '-';
            $tglLahirRaw= $santri->tanggal_lahir;
            $tglLahir   = $tglLahirRaw ? Carbon::parse($tglLahirRaw)->translatedFormat('d F Y') : '-';
            $ttl        = $tmpLahir . ', ' . $tglLahir;

            // Gender
            $jkCode     = $santri->jenis_kelamin;
            $jk         = ($jkCode == 'L' || $jkCode == 'Laki-laki') ? 'Laki-laki' : 'Perempuan';

            // Data Orang Tua
            $namaAyah   = $santri->nama_ayah ?? '-';
            $namaIbu    = $santri->nama_ibu ?? '-';
            
            if (!empty($santri->nama_ayah)) {
                $namaWali = $santri->nama_ayah;
            } elseif (!empty($santri->nama_ibu)) {
                $namaWali = $santri->nama_ibu;
            } else {
                $namaWali = $santri->orangTua->name ?? '-';
            }

            $jobAyah    = $santri->pekerjaan_ayah ?? '-';
            $hpWali     = $santri->orangTua->phone ?? '-';

            // --- 2. LOGIKA NILAI BARU (Sikap, Keterampilan, Kehadiran) ---

            // A. Ambil Rata-rata Akademik (Nilai Akhir)
            $avgAkademik = NilaiPesantren::where('santri_id', $santri->id)
                ->where('mustawa_id', $request->mustawa_id)
                ->avg('nilai_akhir') ?? 0;

            $konten = $this->replaceVar($konten, 'ipk', round($avgAkademik, 2));

            // B. Nilai Kehadiran (Kedisiplinan 0-100)
            // Mengambil rata-rata input nilai kehadiran dari mapel yang diinput
            $nilaiKehadiranRaw = NilaiPesantren::where('santri_id', $santri->id)
                ->where('mustawa_id', $request->mustawa_id)
                ->whereNotNull('nilai_kehadiran')
                ->avg('nilai_kehadiran');
            
            $nilaiKehadiranFinal = $nilaiKehadiranRaw ? round($nilaiKehadiranRaw) : 0;
            
            // C. Nilai Sikap (Attitude)
            // Rumus: (60% Nilai Kehadiran) + (40% Rata-rata Akademik)
            $nilaiSikapScore = ($nilaiKehadiranFinal * 0.6) + ($avgAkademik * 0.4);
            $nilaiSikapFinal = round($nilaiSikapScore);
            $predikatSikap   = $this->getPredikat($nilaiSikapFinal);

            // D. Nilai Keterampilan (Skill)
            // Prioritas: Rata-rata Nilai Praktek -> Jika 0, ambil Rata-rata Nilai Lisan (Baca Kitab)
            $avgPraktek = NilaiPesantren::where('santri_id', $santri->id)
                ->where('mustawa_id', $request->mustawa_id)
                ->avg('nilai_praktek');
            
            if ($avgPraktek > 0) {
                $nilaiKeterampilanFinal = round($avgPraktek);
            } else {
                $avgLisan = NilaiPesantren::where('santri_id', $santri->id)
                    ->where('mustawa_id', $request->mustawa_id)
                    ->avg('nilai_lisan');
                $nilaiKeterampilanFinal = $avgLisan ? round($avgLisan) : 0;
            }
            $predikatKeterampilan = $this->getPredikat($nilaiKeterampilanFinal);


            // --- 3. REPLACE VARIABLES ---
            
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

            // Data Pondok & Kelas
            $namaKelas   = $santri->mustawa ? $santri->mustawa->nama : '-';
            $tahunAjaran = $santri->mustawa ? $santri->mustawa->tahun_ajaran : date('Y') . '/' . (date('Y')+1);
            
            $konten = $this->replaceVar($konten, 'kelas', $namaKelas);
            $konten = $this->replaceVar($konten, 'tahun_ajaran', $tahunAjaran);
            $konten = $this->replaceVar($konten, 'semester', 'Ganjil/Genap'); 
            $konten = $this->replaceVar($konten, 'nama_pondok', strtoupper($pondok->nama_pondok));
            $konten = $this->replaceVar($konten, 'alamat_pondok', $pondok->alamat);
            $konten = $this->replaceVar($konten, 'kota_pondok', $pondok->kota ?? 'Tasikmalaya');

            // Keputusan Naik Kelas
            $keputusanText = ($avgAkademik >= 60 && $nilaiKehadiranFinal >= 50) 
                             ? "NAIK KE TINGKAT BERIKUTNYA" 
                             : "TINGGAL DI KELAS YANG SAMA";
            $konten = $this->replaceVar($konten, 'keputusan', $keputusanText);

            // Replace Nilai Tambahan
            $konten = $this->replaceVar($konten, 'nilai_kehadiran_total', $nilaiKehadiranFinal);
            $konten = $this->replaceVar($konten, 'predikat_kehadiran_total', $this->getPredikat($nilaiKehadiranFinal));
            
            $konten = $this->replaceVar($konten, 'nilai_sikap', $nilaiSikapFinal);
            $konten = $this->replaceVar($konten, 'predikat_sikap', $predikatSikap);
            
            $konten = $this->replaceVar($konten, 'nilai_keterampilan', $nilaiKeterampilanFinal);
            $konten = $this->replaceVar($konten, 'predikat_keterampilan', $predikatKeterampilan);

            // Logo Pondok
            $logoHtml = '';
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
                        break; 
                    } catch (\Exception $e) {}
                }
            }
            $konten = $this->replaceVar($konten, 'logo_pondok', $logoHtml);


            // --- 4. GENERATE TABEL NILAI ---
            
            if (str_contains($konten, 'tabel_nilai_tulis')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_tulis', $this->generateTabelMapel($santri->id, $request->mustawa_id, 'tulis'));
            }
            if (str_contains($konten, 'tabel_nilai_lisan')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_lisan', $this->generateTabelMapel($santri->id, $request->mustawa_id, 'lisan'));
            }
            if (str_contains($konten, 'tabel_nilai_praktek')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_praktek', $this->generateTabelMapel($santri->id, $request->mustawa_id, 'praktek'));
            }
            if (str_contains($konten, 'tabel_nilai_hafalan')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_hafalan', $this->generateTabelMapel($santri->id, $request->mustawa_id, 'hafalan'));
            }
            if (str_contains($konten, 'tabel_nilai_absensi')) {
                $konten = $this->replaceVar($konten, 'tabel_nilai_absensi', $this->generateTabelMapel($santri->id, $request->mustawa_id, 'absensi'));
            }
            if (str_contains($konten, 'tabel_nilai')) { 
                 $konten = str_replace('{{tabel_nilai}}', $this->generateTabelMapel($santri->id, $request->mustawa_id, 'lengkap'), $konten);
            }

            // Data Rekap Absensi
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
    
    private function replaceVar($content, $varName, $value)
    {
        $content = str_replace('{{'.$varName.'}}', $value, $content);
        return $content;
    }

    /**
     * GENERATE TABEL (LOGIKA FIX: JADWAL UJIAN + NILAI)
     * Mengambil mapel dari JADWAL UJIAN (sesuai request) atau dari NILAI yang sudah ada.
     */
    private function generateTabelMapel($santriId, $mustawaId, $kategori)
    {
        // 1. Ambil ID Mapel dari JADWAL UJIAN (Perbaikan Disini)
        // Kita cek tabel jadwal_ujian_diniyahs, bukan jadwal_diniyahs
        $mapelIdsFromJadwal = \App\Models\JadwalUjianDiniyah::where('mustawa_id', $mustawaId)
            ->pluck('mapel_diniyah_id')
            ->toArray();

        // 2. Ambil ID Mapel dari NILAI SANTRI (Backup)
        // Tetap kita pertahankan agar jika ada mapel yg lupa dijadwalkan ujian tapi sudah dinilai, tetap muncul
        $mapelIdsFromNilai = NilaiPesantren::where('santri_id', $santriId)
            ->where('mustawa_id', $mustawaId)
            ->pluck('mapel_diniyah_id')
            ->toArray();

        // Gabungkan kedua sumber ID (Hapus duplikat)
        $allMapelIds = array_unique(array_merge($mapelIdsFromJadwal, $mapelIdsFromNilai));

        if (empty($allMapelIds)) {
            return '<div style="text-align:center; font-style:italic; padding:10px; border:1px dashed #999; font-size:10pt;">- Tidak ada mata pelajaran kategori ini -</div>';
        }

        // 3. Query Mapel berdasarkan ID gabungan tadi
        $queryMapel = MapelDiniyah::whereIn('id', $allMapelIds);

        // Filter Kategori Ujian
        if ($kategori == 'tulis') {
            $queryMapel->where('uji_tulis', true);
        } elseif ($kategori == 'lisan') {
            $queryMapel->where('uji_lisan', true);
        } elseif ($kategori == 'praktek') {
            $queryMapel->where('uji_praktek', true);
        } elseif ($kategori == 'hafalan') {
            $queryMapel->where('uji_hafalan', true);
        }

        $mapels = $queryMapel->orderBy('nama_mapel')->get();

        if ($mapels->isEmpty()) {
            return '<div style="text-align:center; font-style:italic; padding:10px; border:1px dashed #999; font-size:10pt;">- Tidak ada mata pelajaran kategori ini -</div>';
        }

        // 4. Render Tabel HTML
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
        elseif ($kategori == 'hafalan') $labelNilai = 'Nilai Hafalan';
        elseif ($kategori == 'absensi') $labelNilai = 'Kehadiran';

        $html .= '<th style="border: 1px solid black; padding: 6px; width: 15%; text-align: center;">'.$labelNilai.'</th>';
        $html .= '<th style="border: 1px solid black; padding: 6px; width: 20%; text-align: center;">Predikat</th>
                    </tr>
                  </thead><tbody>';

        $no = 1;
        $totalNilai = 0;
        $countMapel = 0;

        foreach ($mapels as $mapel) {
            // Cari Nilai Santri
            $nilaiData = NilaiPesantren::where('santri_id', $santriId)
                        ->where('mapel_diniyah_id', $mapel->id) 
                        ->first();

            $angka = 0;
            
            // Ambil angka spesifik sesuai kategori
            if ($nilaiData) {
                if ($kategori == 'tulis') $angka = $nilaiData->nilai_tulis;
                elseif ($kategori == 'lisan') $angka = $nilaiData->nilai_lisan;
                elseif ($kategori == 'praktek') $angka = $nilaiData->nilai_praktek;
                elseif ($kategori == 'hafalan') $angka = $nilaiData->nilai_hafalan; 
                elseif ($kategori == 'absensi') $angka = $nilaiData->nilai_kehadiran;
                else $angka = $nilaiData->nilai_akhir;
            }

            // Pastikan null menjadi 0
            $angka = $angka ?? 0;
            
            $predikat = $this->getPredikat($angka);
            $kkm = $mapel->kkm ?? 60;

            $html .= '<tr>
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">' . $no++ . '</td>
                        <td style="border: 1px solid black; padding: 4px 8px;">' . $mapel->nama_mapel . '</td>
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">' . $kkm . '</td>
                        <td style="border: 1px solid black; padding: 4px; text-align: center; font-weight: bold;">' . $angka . '</td>
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">' . $predikat . '</td>
                      </tr>';
            
            // Hitung rata-rata (termasuk nilai 0)
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