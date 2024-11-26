<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{


    public function index()
    {
        $appointments = Auth::user()->patient->appointments()
            ->with('doctor.user', 'schedule')
            ->latest()
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = Doctor::with(['user', 'schedules' => function ($query) {
            $query->where('schedule_date', '>', now())
                ->where('is_available', true);
        }])->get();

        return view('patient.appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'appointment_date' => 'required|date|after:today',
                'reason' => 'required|string|max:500',
                'symptoms' => 'nullable|string|max:500',
            ]);
    
            // Get first available doctor
            $doctor = Doctor::first();
            if (!$doctor) {
                throw new \Exception('No doctors available in the system.');
            }
    
            // Create appointment
            $appointment = Appointment::create([
                'patient_id' => Auth::user()->patient->patient_id,
                'doctor_id' => null, // Auto-assign doctor
                'appointment_date' => $validated['appointment_date'],
                'reason' => $validated['reason'],
                'symptoms' => $validated['symptoms'],
                'status' => 'PENDING',
            ]);
    
            return redirect()->route('patient.appointments.show', $appointment)
                ->with('success', 'Appointment scheduled successfully');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create appointment. ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Appointment $appointment)
    {
        if (Auth::user()->patient->patient_id !== $appointment->patient_id) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->load('doctor.user', 'schedule');
        return view('patient.appointments.show', compact('appointment'));
    }

    public function cancel(Appointment $appointment)
    {
        if (Auth::user()->patient->patient_id !== $appointment->patient_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($appointment->status !== 'PENDING') {
            return back()->withErrors(['error' => 'Only pending appointments can be cancelled']);
        }

        $appointment->update([
            'status' => 'CANCELLED'
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment cancelled successfully');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        if (Auth::user()->patient->patient_id !== $appointment->patient_id) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($appointment->status, ['PENDING', 'CONFIRMED'])) {
            return back()->withErrors(['error' => 'This appointment cannot be rescheduled']);
        }

        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,schedule_id',
            'appointment_date' => 'required|date|after:today',
        ]);

        $schedule = Schedule::find($validated['schedule_id']);
        if (!$schedule || !$schedule->is_available) {
            return back()->withErrors(['schedule_id' => 'Selected schedule is not available']);
        }

        $appointment->update([
            'schedule_id' => $validated['schedule_id'],
            'appointment_date' => $validated['appointment_date'],
            'status' => 'PENDING',
            'is_rescheduled' => true
        ]);

        return redirect()->route('patient.appointments.show', $appointment)
            ->with('success', 'Appointment rescheduled successfully');
    }

    public function getDoctorSchedules(Doctor $doctor)
    {
        return response()->json([
            'schedules' => $doctor->schedules()
                ->where('schedule_date', '>', now())
                ->where('is_available', true)
                ->get()
                ->map(function ($schedule) {
                    return [
                        'schedule_id' => $schedule->schedule_id,
                        'day' => $schedule->day_name,
                        'start_time' => $schedule->start_time->format('H:i'),
                        'end_time' => $schedule->end_time->format('H:i'),
                        'available_slots' => $schedule->available_slots
                    ];
                })
        ]);
    }
}
