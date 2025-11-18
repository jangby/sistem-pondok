<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri; // Trait 'BelongsToPondok' akan otomatis memfilter
use Illuminate\Support\Facades\Auth;

class SelectSearchController extends Controller
{
    /**
     * Cari data santri untuk Tom-Select autocomplete.
     */
    public function searchSantri(Request $request)
{
    $search = $request->q;

    // 1. Ambil pondok_id dari user yang sedang login (bisa Admin Pondok atau Admin UJ)
    $pondokId = Auth::user()->pondokStaff->pondok_id;

    if (empty($search) || !$pondokId) {
        return response()->json([]);
    }

    // 2. Hapus Global Scope "BelongsToPondok" agar tidak bentrok
    //    Kita akan terapkan filter manual yang lebih aman
    $query = Santri::withoutGlobalScopes(); 

    // 3. Terapkan filter manual
    $query->where('pondok_id', $pondokId) // <-- Filter Wajib
          ->where(function ($q) use ($search) { // Grup untuk OR
              $q->where('full_name', 'like', '%' . $search . '%')
                ->orWhere('nis', 'like', '%' . $search . '%');
          });

    $santris = $query->limit(10)->get(['id', 'full_name', 'nis']);

    // 4. Format data
    $formattedSantris = $santris->map(function($santri) {
        return [
            'id' => $santri->id,
            'text' => $santri->full_name . ' (NIS: ' . $santri->nis . ')'
        ];
    });

    return response()->json($formattedSantris);
}
}