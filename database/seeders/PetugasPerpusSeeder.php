<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PetugasPerpusSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan firstOrCreate agar aman dijalankan berkali-kali
        // Role ini akan dibuat jika belum ada, dan dibiarkan jika sudah ada
        Role::firstOrCreate(['name' => 'petugas_perpus', 'guard_name' => 'web']);

        $this->command->info('Role petugas_perpus berhasil ditambahkan!');
    }
}