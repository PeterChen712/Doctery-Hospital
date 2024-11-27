<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Schedule;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run()
    {
        // Get patient with ID 1
        $patient = Patient::find(1);
        $doctors = Doctor::all();
        $schedules = Schedule::where('schedule_date', '>=', now())->get();

        if (!$patient || $doctors->isEmpty() || $schedules->isEmpty()) {
            throw new \Exception('Required data not found. Please check patient ID 1, doctors and schedules exist');
        }

        // Create 5 appointments for patient ID 1
        for ($i = 0; $i < 5; $i++) {
            $schedule = $schedules->random();
            $doctor = $doctors->random();

            Appointment::create([
                'patient_id' => $patient->patient_id,
                'doctor_id' => $doctor->doctor_id,
                'schedule_id' => $schedule->schedule_id,
                'appointment_date' => $schedule->schedule_date,
                'status' => 'PENDING_CONFIRMATION',
                'patient_confirmed' => null,
                'reason' => 'Regular checkup ' . ($i + 1),
                'symptoms' => 'Mild fever, headache',
                'notes' => 'Patient requested evening appointment',
                'is_rescheduled' => false
            ]);
        }
    }
}
// Note: schedule_appointments entries will be created 
// when patients confirm their appointments through the confirmAppointment method