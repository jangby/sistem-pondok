<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pondok;
use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function store(Request $request, Pondok $pondok)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'durasi_bulan' => 'required|integer|min:1|max:24', // Durasi 1-24 bulan
        ]);

        // Ambil langganan yang ada atau buat baru
        $subscription = Subscription::firstOrNew(['pondok_id' => $pondok->id]);

        // Tentukan tanggal mulai
        // Jika langganan masih aktif, perpanjang dari tanggal 'ends_at'
        // Jika tidak, mulai dari hari ini
        $starts_at = now();
        if ($subscription->exists && $subscription->ends_at->isFuture()) {
            $starts_at = $subscription->ends_at;
        }

        // --- INI ADALAH PERBAIKANNYA ---
        // Hitung tanggal berakhir yang baru
        // Kita paksa $validated['durasi_bulan'] menjadi integer (angka)
        $ends_at = $starts_at->copy()->addMonths((int) $validated['durasi_bulan']);
        // ---------------------------------

        // Simpan data
        $subscription->plan_id = $validated['plan_id'];
        $subscription->starts_at = $starts_at; // starts_at juga di-update jika memperpanjang
        $subscription->ends_at = $ends_at;
        $subscription->status = 'active';
        $subscription->save();

        return redirect()->back()->with('success', 'Langganan pondok berhasil diperbarui.');
    }
}