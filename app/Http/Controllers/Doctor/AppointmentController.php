<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Appointment::with('patient.user')
            ->where('doctor_id', Auth::id());

        // Filter by date
        if ($request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(10);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        // Verify doctor owns appointment
        $this->authorize('view', $appointment);

        $appointment->load('patient.user', 'medicalRecord');

        return view('doctor.appointments.show', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        // Verify doctor owns appointment
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'status' => 'required|in:CONFIRMED,COMPLETED,CANCELLED',
            'notes' => 'nullable|string|max:1000',
        ]);

        $appointment->update($validated);

        return redirect()
            ->route('doctor.appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully');
    }

    public function todayAppointments()
    {
        $appointments = Appointment::with('patient.user')
            ->where('doctor_id', Auth::id())
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        return response()->json($appointments);
    }

    public function upcomingAppointments()
    {
        $appointments = Appointment::with('patient.user')
            ->where('doctor_id', Auth::id())
            ->where('appointment_date', '>', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        return response()->json($appointments);
    }
}