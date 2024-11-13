<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'is_active' => true,
            'role' => 'PATIENT'
        ];
    }

    // Optional: Add a state for patient role
    public function patient()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'patient'
            ];
        });
    }
}