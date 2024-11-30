<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleAndPermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            RoleAndPermissionSeeder::class,    // 1. Create roles
            AdminUserSeeder::class,            // 2. Create admin
            DoctorSeeder::class,               // 3. Create doctor
            PatientSeeder::class,              // 4. Create patients
            DoctorScheduleSeeder::class,       // 5. Create schedules
            MedicineSeeder::class,             // 6. Create medicines
            AppointmentSeeder::class           // 7. Create appointments
        ]);
    }
}
