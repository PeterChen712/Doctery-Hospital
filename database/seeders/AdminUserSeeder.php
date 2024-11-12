<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'username' => 'Admin',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'),
            'phone_number' => '123456789',
            'address' => 'Hospital Address',
            'is_active' => true,
        ]);

        $admin->assignRole('admin');
    }
}