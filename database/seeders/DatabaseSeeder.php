<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductsTableSeeder::class,
            AccountsTableSeeder::class,
            ExpenseCategoriesTableSeeder::class,
            PackagingTypesTableSeeder::class,
            CustomersTableSeeder::class,
            UsersTableSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
