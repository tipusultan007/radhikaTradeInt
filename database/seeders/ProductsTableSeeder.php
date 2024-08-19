<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['name' => 'Product A', 'initial_stock_kg' => 100.00],
            ['name' => 'Product B', 'initial_stock_kg' => 150.50],
            ['name' => 'Product C', 'initial_stock_kg' => 200.75],
        ]);
    }
}
