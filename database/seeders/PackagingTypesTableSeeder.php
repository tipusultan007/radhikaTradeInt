<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagingTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('packaging_types')->insert([
            ['type' => '1kg', 'weight_kg' => 1.00],
            ['type' => '2kg', 'weight_kg' => 2.00],
            ['type' => '3kg', 'weight_kg' => 3.00],
            ['type' => '5kg', 'weight_kg' => 5.00],
        ]);
    }
}
