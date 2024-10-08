<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounts')->insert([
            ['parent_id' => null, 'name' => 'Cash', 'type' => 'asset', 'code' => '1001', 'opening_balance' => 0],
            ['parent_id' => null, 'name' => 'Bank', 'type' => 'asset', 'code' => '1002', 'opening_balance' => 0.00],
            ['parent_id' => null, 'name' => 'Accounts Receivable', 'type' => 'asset', 'code' => '1003', 'opening_balance' => 0.00],
            ['parent_id' => null, 'name' => 'Asset', 'type' => 'asset', 'code' => '1004', 'opening_balance' => 0.00],
            ['parent_id' => null, 'name' => 'Accounts Payable', 'type' => 'liability', 'code' => '2001', 'opening_balance' => 0.00],
            ['parent_id' => null, 'name' => 'Revenue', 'type' => 'revenue', 'code' => '3001', 'opening_balance' => 0.00],
            ['parent_id' => null, 'name' => 'Expenses', 'type' => 'expense', 'code' => '4001', 'opening_balance' => 0.00],
            ['parent_id' => 7, 'name' => 'Salary', 'type' => 'expense', 'code' => '4002', 'opening_balance' => 0.00],
            ['parent_id' => 7, 'name' => 'Commission', 'type' => 'expense', 'code' => '4003', 'opening_balance' => 0.00],
        ]);
    }
}
