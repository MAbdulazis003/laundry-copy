<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'kasir@example.com'],
            ['name' => 'Kasir', 'password' => Hash::make('password'), 'role' => 'kasir']
        );

        User::updateOrCreate(
            ['email' => 'operator@example.com'],
            ['name' => 'Operator', 'password' => Hash::make('password'), 'role' => 'operator']
        );
    }
}
