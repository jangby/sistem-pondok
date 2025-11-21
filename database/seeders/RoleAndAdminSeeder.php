<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan 'firstOrCreate' agar aman jika dijalankan ulang
        $role_superadmin = Role::firstOrCreate(['name' => 'super-admin']);
        $role_adminpondok = Role::firstOrCreate(['name' => 'admin-pondok']);
        $role_bendahara = Role::firstOrCreate(['name' => 'bendahara']);
        $role_orangtua = Role::firstOrCreate(['name' => 'orang-tua']);

        // --- INI ROLE BARU UNTUK PAKET PREMIUM ---
        Role::firstOrCreate(['name' => 'admin_uang_jajan']);
        Role::firstOrCreate(['name' => 'pos_warung']);
        Role::firstOrCreate(['name' => 'pengurus_pondok']);
        Role::firstOrCreate(['name' => 'admin-pendidikan']); // Kepala Madin
        Role::firstOrCreate(['name' => 'ustadz']);
        // -----------------------------------------

        Role::firstOrCreate(['name' => 'super-admin-sekolah']);
        Role::firstOrCreate(['name' => 'admin-sekolah']);
        Role::firstOrCreate(['name' => 'guru']);

        // Buat Akun Super Admin (gunakan firstOrCreate agar aman)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@sistem.com'], 
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password') // Ganti password ini
            ]
        );

        // Berikan role
        $superAdmin->assignRole($role_superadmin);
    }
}