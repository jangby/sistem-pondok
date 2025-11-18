<?php

namespace App\Console\Commands;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;

class CreateUserWithRole extends Command
{
    protected $signature = 'user:create {role} {email} {name}'; // Definisi Command
    protected $description = 'Create a new user and assign a role.';

    public function handle()
    {
        $roleName = $this->argument('role');
        $email = $this->argument('email');
        $name = $this->argument('name');
        
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("Role '{$roleName}' does not exist!");
            return Command::FAILURE;
        }

        // Minta password secara terpisah
        $password = $this->secret('Enter password');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole($role);

        $this->info("User '{$name}' created and assigned role '{$roleName}'.");
        return Command::SUCCESS;
    }
}