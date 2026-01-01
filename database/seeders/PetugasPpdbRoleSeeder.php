<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PetugasPpdbRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Role Baru
        // Kita gunakan guard_name 'web' sesuai default Laravel
        $role = Role::firstOrCreate(['name' => 'petugas_ppdb', 'guard_name' => 'web']);

        // 2. (Opsional) Buat 1 Akun Dummy untuk Percobaan
        $user = User::firstOrCreate(
            ['email' => 'petugas@pondok.com'],
            [
                'name' => 'Petugas PPDB 1',
                'password' => bcrypt('password'), // password default
                'telepon' => '081234567890',
            ]
        );

        $user->assignRole($role);
    }
}