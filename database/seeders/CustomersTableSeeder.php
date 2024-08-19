<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            ['name' => 'John Doe', 'phone' => '1234567890', 'address' => '123 Main St, Anytown, USA', 'type' => 'dealer'],
            ['name' => 'Jane Smith', 'phone' => '0987654321', 'address' => '456 Elm St, Othertown, USA', 'type' => 'retailer'],
            ['name' => 'Bob Johnson', 'phone' => '5551234567', 'address' => '789 Oak St, Sometown, USA', 'type' => 'wholesale'],
            ['name' => 'Alice Davis', 'phone' => '5559876543', 'address' => '321 Pine St, Anytown, USA', 'type' => 'commission_agent'],
        ]);
    }
}
