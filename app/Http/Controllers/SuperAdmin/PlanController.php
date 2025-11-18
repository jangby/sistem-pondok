<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plans',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
        ]);

        Plan::create($validated);
        return redirect()->route('superadmin.plans.index')->with('success', 'Paket berhasil dibuat.');
    }

    public function edit(Plan $plan)
    {
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plans,name,' . $plan->id,
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
        ]);

        $plan->update($validated);
        return redirect()->route('superadmin.plans.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Plan $plan)
    {
        // Nanti tambahkan pengecekan jika paket sedang dipakai
        $plan->delete();
        return redirect()->route('superadmin.plans.index')->with('success', 'Paket berhasil dihapus.');
    }
}