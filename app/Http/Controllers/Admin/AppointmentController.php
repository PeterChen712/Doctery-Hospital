<?php
// app/Http/Controllers/Admin/AppointmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\UserNotification;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->latest()
            ->paginate(10);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $doctors = Doctor::with('user')->get();
        return view('admin.appointments.show', compact('appointment', 'doctors'));
    }


    public function assignDoctor(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'schedule_id' => 'required|exists:schedules,schedule_id'
        ]);

        $appointment->update([
            'doctor_id' => $validated['doctor_id'],
            'schedule_id' => $validated['schedule_id'],
            'status' => 'PENDING_CONFIRMATION'
        ]);

        // Create notification
        UserNotification::create([
            'user_id' => $appointment->patient->user_id,
            'title' => 'Doctor Assigned to Your Appointment',
            'type' => 'APPOINTMENT',
            'data' => json_encode([
                'message' => "Dr. {$appointment->doctor->user->username} has been assigned to your appointment. Please confirm your attendance.",
                'appointment_id' => $appointment->appointment_id
            ]),
            'notifiable_type' => 'App\Models\Appointment',
            'notifiable_id' => $appointment->appointment_id
        ]);

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', 'Doctor assigned and notification sent to patient');
    }


    public function confirmAppointment(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'confirm' => 'required|boolean'
        ]);

        $appointment->update([
            'patient_confirmed' => $validated['confirm'],
            'status' => $validated['confirm'] ? 'CONFIRMED' : 'PENDING'
        ]);

        return back()->with('success', 'Appointment response recorded');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,CANCELLED,COMPLETED'
        ]);

        $appointment->update(['status' => $validated['status']]);

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', 'Status updated successfully');
    }





    public function getDoctorSchedules(Doctor $doctor)
    {
        $now = now(); // Carbon instance of current date/time

        $schedules = $doctor->schedules()
            ->where('is_available', true)
            ->where('schedule_date', '>=', $now->toDateString())
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
                return [
                    'schedule_id' => $schedule->schedule_id,
                    'schedule_date' => $schedule->schedule_date->format('Y-m-d'),
                    'day_of_week' => $schedule->day_of_week,
                    'start_time' => $schedule->start_time->format('H:i'),
                    'end_time' => $schedule->end_time->format('H:i')
                ];
            });

        return response()->json([
            'schedules' => $schedules
        ]);
    }
}
