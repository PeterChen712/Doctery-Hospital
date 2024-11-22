<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'doctor_id' => 1,
                'schedule_date' => Carbon::now()->addDays(1),
                'start_time' => '09:00',
                'end_time' => '17:00',
                'max_patients' => 8,
                'day_of_week' => Carbon::now()->addDays(1)->dayOfWeek,
                'is_available' => true,
            ],
            [
                'doctor_id' => 1,
                'schedule_date' => Carbon::now()->addDays(2),
                'start_time' => '09:00',
                'end_time' => '17:00',
                'max_patients' => 8,
                'day_of_week' => Carbon::now()->addDays(2)->dayOfWeek,
                'is_available' => true,
            ],
            [
                'doctor_id' => 1,
                'schedule_date' => Carbon::now()->addDays(3),
                'start_time' => '09:00',
                'end_time' => '17:00',
                'max_patients' => 8,
                'day_of_week' => Carbon::now()->addDays(3)->dayOfWeek,
                'is_available' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}