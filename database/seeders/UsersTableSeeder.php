<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone' => '01829321686',
                'basic_salary' => 10000,
                'password' => Hash::make('password123'), // Always hash passwords
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'phone' => '01515285761',
                'basic_salary' => 9500,
                'password' => Hash::make('password123'),
            ],
        ]);
    }
}
