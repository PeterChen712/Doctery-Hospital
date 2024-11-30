<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Doctor;
use Carbon\Carbon;

class DoctorScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $doctor = Doctor::first();
        
        // Create schedules for the past week and next week
        $dates = [];
        for ($i = -7; $i <= 7; $i++) {
            $dates[] = Carbon::now()->addDays($i);
        }

        foreach ($dates as $date) {
            Schedule::create([
                'doctor_id' => $doctor->doctor_id,
                'schedule_date' => $date->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'max_patients' => 8,
                'day_of_week' => $date->dayOfWeek,
                'is_available' => true
            ]);
        }
    }
}