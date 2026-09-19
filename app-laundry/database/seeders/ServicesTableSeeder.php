<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('services')->updateOrInsert(
            ['name' => 'Regular Wash'],
            ['pricing_type' => 'per_kg', 'price' => 5000, 'estimated_days' => 1]
        );

        DB::table('services')->updateOrInsert(
            ['name' => 'Express Wash'],
            ['pricing_type' => 'per_kg', 'price' => 8000, 'estimated_days' => 0]
        );

        DB::table('services')->updateOrInsert(
            ['name' => 'Ironing'],
            ['pricing_type' => 'per_item', 'price' => 2000, 'estimated_days' => 1]
        );
    }
}
