<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class PatientViewDoctorController extends Controller
{
    public function show(Doctor $doctor)
    {
        return view('patient.view-doctor.show', compact('doctor'));
    }


    public function getDoctorSchedules(Doctor $doctor)
    {
        $schedules = $doctor->schedules()
            ->where('schedule_date', '>', now())
            ->where('is_available', true)
            ->get()
            ->map(function ($schedule) {
                $bookedPatients = $schedule->appointments()
                    ->whereIn('status', ['CONFIRMED', 'PENDING_CONFIRMATION'])
                    ->count();

                return [
                    'schedule_id' => $schedule->schedule_id,
                    'schedule_date' => $schedule->schedule_date->format('Y-m-d'),
                    'day_of_week' => $schedule->schedule_date->dayOfWeek,
                    'start_time' => $schedule->start_time->format('H:i'),
                    'end_time' => $schedule->end_time->format('H:i'),
                    'max_patients' => $schedule->max_patients,
                    'booked_patients' => $bookedPatients,
                    'is_available' => $schedule->is_available
                ];
            });

        return response()->json([
            'schedules' => $schedules
        ]);
    }
}
