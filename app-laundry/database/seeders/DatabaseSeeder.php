<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\CustomersTableSeeder;
use Database\Seeders\ServicesTableSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed core data
        $this->call([
            UsersTableSeeder::class,
            CustomersTableSeeder::class,
            ServicesTableSeeder::class,
        ]);
    }
}
