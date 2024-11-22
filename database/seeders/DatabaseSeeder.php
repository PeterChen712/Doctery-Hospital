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
            RoleAndPermissionSeeder::class,
            AdminUserSeeder::class,
            DoctorSeeder::class,
            PatientSeeder::class,
            ScheduleSeeder::class,
        ]);
    }
}
