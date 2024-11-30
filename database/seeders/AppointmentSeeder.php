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
        
        // Get both past and future schedules
        $schedules = Schedule::where('schedule_date', '>=', now()->subDays(30))
            ->where('schedule_date', '<=', now()->addDays(30))
            ->get();

        if (!$patient || $doctors->isEmpty() || $schedules->isEmpty()) {
            throw new \Exception('Required data not found. Please check patient ID 1, doctors and schedules exist');
        }

        // Create past confirmed appointments
        for ($i = 0; $i < 3; $i++) {
            $pastSchedule = $schedules->where('schedule_date', '<', now())->random();
            $doctor = $doctors->random();

            Appointment::create([
                'patient_id' => $patient->patient_id,
                'doctor_id' => $doctor->doctor_id,
                'schedule_id' => $pastSchedule->schedule_id,
                'appointment_date' => $pastSchedule->schedule_date,
                'appointment_time' => $pastSchedule->start_time,
                'status' => 'CONFIRMED',
                'patient_confirmed' => true,
                'reason' => 'Regular checkup ' . ($i + 1),
                'symptoms' => $this->getRandomSymptoms(),
                'notes' => 'Past appointment',
                'is_rescheduled' => false
            ]);
        }

        // Create future pending appointments
        for ($i = 0; $i < 2; $i++) {
            $futureSchedule = $schedules->where('schedule_date', '>', now())->random();
            $doctor = $doctors->random();

            Appointment::create([
                'patient_id' => $patient->patient_id,
                'doctor_id' => $doctor->doctor_id,
                'schedule_id' => $futureSchedule->schedule_id,
                'appointment_date' => $futureSchedule->schedule_date,
                'appointment_time' => $futureSchedule->start_time,
                'status' => 'PENDING_CONFIRMATION',
                'patient_confirmed' => null,
                'reason' => 'Follow-up appointment ' . ($i + 1),
                'symptoms' => $this->getRandomSymptoms(),
                'notes' => 'Pending future appointment',
                'is_rescheduled' => false
            ]);
        }

        // Create one cancelled appointment
        $schedule = $schedules->where('schedule_date', '>', now())->random();
        $doctor = $doctors->random();

        Appointment::create([
            'patient_id' => $patient->patient_id,
            'doctor_id' => $doctor->doctor_id,
            'schedule_id' => $schedule->schedule_id,
            'appointment_date' => $schedule->schedule_date,
            'appointment_time' => $schedule->start_time,
            'status' => 'CANCELLED',
            'patient_confirmed' => false,
            'reason' => 'Emergency consultation',
            'symptoms' => $this->getRandomSymptoms(),
            'notes' => 'Cancelled by patient',
            'is_rescheduled' => false
        ]);
    }

    private function getRandomSymptoms()
    {
        $symptoms = [
            'Mild fever and headache',
            'Persistent cough and fatigue',
            'Muscle pain and weakness',
            'Sore throat and runny nose',
            'Stomach pain and nausea',
            'Regular health checkup',
            'Follow-up consultation'
        ];

        return $symptoms[array_rand($symptoms)];
    }
}
// Note: schedule_appointments entries will be created 
// when patients confirm their appointments through the confirmAppointment method