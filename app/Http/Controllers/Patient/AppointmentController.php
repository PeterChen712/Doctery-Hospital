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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{




    public function index(Request $request)
    {

        $query = Appointment::with(['doctor.user', 'schedule']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('date')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->whereDate('schedule_date', $request->date);
            });
        }
    
        $appointments = $query->latest()->paginate(10);
        
        return view('patient.appointments.index', compact('appointments'));

        // $appointments = Appointment::with(['doctor.user', 'schedule'])
        //     ->where('patient_id', Auth::user()->patient->patient_id)
        //     ->latest()
        //     ->paginate(10); // Change get() to paginate()

        // return view('patient.appointments.index', compact('appointments'));
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
                'doctor_id' => 'required|exists:doctors,doctor_id',
                'schedule_id' => 'required|exists:schedules,schedule_id',
                'reason' => 'required|string|max:500',
                'symptoms' => 'nullable|string|max:500',
            ]);

            // Check if schedule is available
            $schedule = Schedule::find($validated['schedule_id']);
            if (!$schedule || !$schedule->is_available) {
                throw new \Exception('Selected schedule is not available.');
            }

            // Check if schedule belongs to selected doctor
            if ($schedule->doctor_id != $validated['doctor_id']) {
                throw new \Exception('Invalid schedule selection.');
            }

            // Create appointment
            $appointment = Appointment::create([
                'patient_id' => Auth::user()->patient->patient_id,
                'doctor_id' => $validated['doctor_id'],
                'schedule_id' => $validated['schedule_id'],
                'appointment_date' => $schedule->schedule_date,
                'appointment_time' => $schedule->start_time,
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
    try {
        // Add logging
        Log::info('Getting schedules for doctor:', ['doctor_id' => $doctor->doctor_id]);

        $tomorrow = now()->addDay()->startOfDay();

        $schedules = Schedule::where('doctor_id', $doctor->doctor_id)
            ->where('schedule_date', '>=', $tomorrow)
            ->where('is_available', true)
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();

        // Log found schedules
        Log::info('Found schedules:', ['count' => $schedules->count()]);

        $formattedSchedules = $schedules->map(function ($schedule) {
            // Get booked appointments count
            $bookedCount = Appointment::where('schedule_id', $schedule->schedule_id)
                ->whereIn('status', ['CONFIRMED', 'PENDING'])
                ->count();

            return [
                'schedule_id' => $schedule->schedule_id,
                'schedule_date' => $schedule->schedule_date instanceof Carbon 
                    ? $schedule->schedule_date->format('Y-m-d')
                    : Carbon::parse($schedule->schedule_date)->format('Y-m-d'),
                'start_time' => $schedule->start_time instanceof Carbon
                    ? $schedule->start_time->format('H:i')
                    : Carbon::parse($schedule->start_time)->format('H:i'),
                'end_time' => $schedule->end_time instanceof Carbon
                    ? $schedule->end_time->format('H:i')
                    : Carbon::parse($schedule->end_time)->format('H:i'),
                'max_patients' => (int) $schedule->max_patients,
                'booked_patients' => (int) $bookedCount,
                'is_available' => (bool) $schedule->is_available,
                'day_of_week' => Carbon::parse($schedule->schedule_date)->dayOfWeek
            ];
        });

        return response()->json([
            'success' => true,
            'schedules' => $formattedSchedules
        ]);

    } catch (\Exception $e) {
        Log::error('Error getting doctor schedules:', [
            'doctor_id' => $doctor->doctor_id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to load doctor schedules',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function confirmAppointment(Request $request, Appointment $appointment)
    {
        // Security check
        if ($appointment->patient_id !== Auth::user()->patient->patient_id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            if ($request->confirm == 1) {
                // Confirm appointment
                $appointment->update([
                    'status' => 'CONFIRMED',
                    'patient_confirmed' => true
                ]);

                // Create schedule_appointments entry
                $appointment->schedules()->attach($appointment->schedule_id);
                $message = 'Appointment confirmed successfully';
            } else {
                // Decline appointment
                $appointment->update([
                    'status' => 'PENDING',
                    'patient_confirmed' => false,
                    'schedule_id' => null // Clear schedule assignment
                ]);
                $message = 'Appointment declined';
            }

            DB::commit();
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process confirmation');
        }
    }
}
