<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        $doctor = User::create([
            'username' => 'Dr. Rudy Peter',
            'email' => 'rudypetere@gmail.com',
            'password' => Hash::make('password'),
            'phone_number' => '1234567890',
            'address' => 'Jl Tamalanrea No 123',
            'is_active' => true,
            'role' => 'DOCTOR',
        ]);
        
        $doctor->assignRole('doctor');

        Doctor::create([
            'user_id' => $doctor->user_id,
            'specialization' => 'Obat Penyakit Dalam',
            'license_number' => 'DOC123456',
            'education' => 'Universitas Hasanuddin',
            'experience' => '10 Tahun',
            'consultation_fee' => 100000,
            'is_available' => true
        ]);
    }
}