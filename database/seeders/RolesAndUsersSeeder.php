<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if missing
        $roles = ['manager', 'waiter', 'kitchen', 'bar'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        // Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            ['name' => 'Manager', 'password' => Hash::make('password123')]
        );
        $manager->syncRoles(['manager']);

        // Waiter
        $waiter = User::firstOrCreate(
            ['email' => 'waiter@example.com'],
            ['name' => 'Waiter', 'password' => Hash::make('password123')]
        );
        $waiter->syncRoles(['waiter']);

        // Kitchen
        $kitchen = User::firstOrCreate(
            ['email' => 'kitchen@example.com'],
            ['name' => 'Kitchen', 'password' => Hash::make('password123')]
        );
        $kitchen->syncRoles(['kitchen']);

        // Bar
        $bar = User::firstOrCreate(
            ['email' => 'bar@example.com'],
            ['name' => 'Bar', 'password' => Hash::make('password123')]
        );
        $bar->syncRoles(['bar']);
    }
}
