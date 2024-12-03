<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    use AuthorizesRequests;



    public function index(Request $request)
    {
        // Get doctor_id first
        $doctorId = DB::table('doctors')
            ->where('user_id', Auth::id())
            ->value('doctor_id');
    
        // Debug doctor info
        Log::info('Doctor Info:', [
            'user_id' => Auth::id(),
            'doctor_id' => $doctorId
        ]);
    
        // Build query with correct joins
        $query = Appointment::with(['patient.user'])
            ->where('appointments.doctor_id', $doctorId);
    
        // Add filters
        if ($request->date) {
            $query->whereDate('appointments.appointment_date', $request->date);
        }
    
        if ($request->status) {
            $query->where('appointments.status', $request->status);
        }
    
        // Join schedule_appointments
        $query->join('schedule_appointments', 'appointments.appointment_id', '=', 'schedule_appointments.appointment_id');
    
        // Select appointments columns to avoid ambiguity
        $query->select('appointments.*');
    
        $appointments = $query->paginate(10);
    
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
