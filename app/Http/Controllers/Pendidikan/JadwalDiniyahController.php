<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\JadwalDiniyah;
use App\Models\Mustawa;
use App\Models\MapelDiniyah;
use App\Models\Ustadz;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JadwalDiniyahController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // Filter Hari
        $hari = $request->input('hari');
        $mustawaId = $request->input('mustawa_id');

        $query = JadwalDiniyah::where('pondok_id', $pondokId)
            ->with(['mustawa', 'mapel', 'ustadz']);

        if ($hari) {
            $query->where('hari', $hari);
        }
        
        if ($mustawaId) {
            $query->where('mustawa_id', $mustawaId);
        }

        // Urutkan berdasarkan hari (custom order) dan jam
        // Karena hari disimpan string, kita urutkan manual via collection di view atau query case
        $jadwals = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(15);
        
        // Data untuk filter
        $mustawas = Mustawa::where('pondok_id', $pondokId)->orderBy('tingkat')->get();

        return view('pendidikan.admin.jadwal.index', compact('jadwals', 'mustawas'));
    }

    public function create()
    {
        $pondokId = $this->getPondokId();

        $data = [
            'mustawas' => Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get(),
            'mapels' => MapelDiniyah::where('pondok_id', $pondokId)->orderBy('nama_mapel')->get(),
            'ustadzs' => Ustadz::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('nama_lengkap')->get(),
            'hari_list' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'],
        ];

        return view('pendidikan.admin.jadwal.create', $data);
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $request->validate([
            'mustawa_id' => 'required|exists:mustawas,id',
            'mapel_diniyah_id' => 'required|exists:mapel_diniyahs,id',
            'ustadz_id' => 'required|exists:ustadzs,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Cek Bentrok Jadwal Ustadz
        $bentrok = JadwalDiniyah::where('pondok_id', $pondokId)
            ->where('ustadz_id', $request->ustadz_id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })->exists();

        if ($bentrok) {
            throw ValidationException::withMessages([
                'ustadz_id' => 'Ustadz ini sudah memiliki jadwal mengajar di jam dan hari yang sama.',
            ]);
        }

        JadwalDiniyah::create([
            'pondok_id' => $pondokId,
            'mustawa_id' => $request->mustawa_id,
            'mapel_diniyah_id' => $request->mapel_diniyah_id,
            'ustadz_id' => $request->ustadz_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('pendidikan.admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(JadwalDiniyah $jadwal)
    {
        if ($jadwal->pondok_id != $this->getPondokId()) abort(403);

        $pondokId = $this->getPondokId();
        $data = [
            'jadwal' => $jadwal,
            'mustawas' => Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get(),
            'mapels' => MapelDiniyah::where('pondok_id', $pondokId)->orderBy('nama_mapel')->get(),
            'ustadzs' => Ustadz::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('nama_lengkap')->get(),
            'hari_list' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'],
        ];

        return view('pendidikan.admin.jadwal.edit', $data);
    }

    public function update(Request $request, JadwalDiniyah $jadwal)
    {
        if ($jadwal->pondok_id != $this->getPondokId()) abort(403);

        $request->validate([
            'mustawa_id' => 'required|exists:mustawas,id',
            'mapel_diniyah_id' => 'required|exists:mapel_diniyahs,id',
            'ustadz_id' => 'required|exists:ustadzs,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Cek Bentrok (Kecuali jadwal ini sendiri)
        $bentrok = JadwalDiniyah::where('pondok_id', $this->getPondokId())
            ->where('id', '!=', $jadwal->id)
            ->where('ustadz_id', $request->ustadz_id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })->exists();

        if ($bentrok) {
            throw ValidationException::withMessages([
                'ustadz_id' => 'Jadwal bentrok dengan jam mengajar ustadz lain.',
            ]);
        }

        $jadwal->update($request->all());

        return redirect()->route('pendidikan.admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(JadwalDiniyah $jadwal)
    {
        if ($jadwal->pondok_id != $this->getPondokId()) abort(403);
        
        $jadwal->delete();
        return redirect()->route('pendidikan.admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}