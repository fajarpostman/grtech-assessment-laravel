<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        User::updateOrCreate(
            ['email' => 'admin@grtech.com'],
            ['name' => 'Administrator', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 'user@grtech.com'],
            ['name' => 'Regular User', 'password' => Hash::make('password')]
        );
    }
}
