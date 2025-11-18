<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pondok;
use Illuminate\Http\Request;

class PondokController extends Controller
{
    public function index()
    {
        // Ambil pondok, sekaligus 'eager load' relasi staff dan user-nya
        $pondoks = Pondok::with('staff.user')->latest()->get();
        return view('superadmin.pondoks.index', compact('pondoks'));
    }

    public function create()
    {
        return view('superadmin.pondoks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:pondoks',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,trial,inactive',
        ]);

        Pondok::create($validated);

        return redirect()->route('superadmin.pondoks.index')
                         ->with('success', 'Pondok baru berhasil ditambahkan.');
    }

    // Biarkan show, edit, update kosong dulu
    public function show(Pondok $pondok) {}
    public function edit(Pondok $pondok)
    {
        // Muat relasi langganan & semua paket
        $pondok->load('subscription.plan');
        $plans = \App\Models\Plan::all(); 
        
        return view('superadmin.pondoks.edit', compact('pondok', 'plans'));
    }
    public function update(Request $request, Pondok $pondok) {}

    public function destroy(Pondok $pondok)
    {
        try {
            $pondokName = $pondok->name;
            $pondok->delete(); // Ini akan otomatis menghapus admin, santri, dll (jika cascadeOnDelete)
            
            return redirect()->route('superadmin.pondoks.index')
                             ->with('success', "Pondok '$pondokName' dan semua datanya berhasil dihapus.");
        
        } catch (\Exception $e) {
            return redirect()->route('superadmin.pondoks.index')
                             ->with('error', 'Gagal menghapus pondok: ' . $e->getMessage());
        }
    }
}