<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@berhentimerokok.test'],
            [
                'name' => 'Admin BerhentiMerokok',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
                'is_email_verified' => true,
            ]
        );
    }
}
