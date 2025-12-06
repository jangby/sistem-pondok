<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PetugasLabSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah role sudah ada, jika belum buat baru
        if (!Role::where('name', 'petugas_lab')->exists()) {
            Role::create(['name' => 'petugas_lab', 'guard_name' => 'web']);
        }
    }
}