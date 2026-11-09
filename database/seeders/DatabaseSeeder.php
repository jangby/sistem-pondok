<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        //RoleAndAdminSeeder::class, // <-- TAMBAHKAN BARIS INI
        PetugasLabSeeder::class, // <-- TAMBAHKAN BARIS INI
        PetugasPerpusSeeder::class, // <-- TAMBAHKAN BARIS INI
        PetugasPpdbSeeder::class, // <-- TAMBAHKAN BARIS INI
        ]);
    }
}
