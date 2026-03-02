<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
         User::firstOrCreate(
        ['email' => 'admin@admin.com'], // clé unique
        [
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]
    );
    }
}
