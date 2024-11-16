<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory;

class PatientSeeder extends Seeder
{
    public function run()
    {
        // Create fixed patient
        $patient = User::create([
            'username' => 'mangalik',
            'email' => 'm@gmail.com',
            'password' => Hash::make('password'),
            'phone_number' => '123123123',
            'address' => 'Patient Address',
            'is_active' => true,
            'role' => 'patient',
        ]);

        $patient->assignRole('patient');

        Patient::create([
            'user_id' => $patient->user_id,
            'date_of_birth' => '1990-01-01',
            'blood_type' => 'O+',
            'allergies' => 'None',
            'medical_history' => 'No major illnesses',
            'emergency_contact' => '112233445'
        ]);

        // Create faker patients
        User::factory(10)
            ->patient() // optional if role is already set in definition
            ->create()
            ->each(function ($user) {
                $user->assignRole('patient');

                Patient::create([
                    'user_id' => $user->user_id,
                    'date_of_birth' => fake()->date(),
                    'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                    'allergies' => fake()->optional()->text(),
                    'medical_history' => fake()->optional()->text(),
                    'emergency_contact' => fake()->phoneNumber()
                ]);
            });
    }
    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
}
