<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DoctorPatientController extends Controller
{
    use AuthorizesRequests;
    
public function index(Request $request)
{
    $doctor = Auth::user()->doctor;

    // Get patients who have confirmed appointments with this doctor
    $query = User::role('patient')
        ->whereHas('patient.appointments', function ($query) use ($doctor) {
            $query->where('doctor_id', $doctor->doctor_id)
                ->where('status', 'CONFIRMED')
                ->where(function ($q) {
                    $q->whereDate('appointment_date', '<=', now());
                });
        });

    // Search functionality
    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('username', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%");
        });
    }

    $patients = $query->paginate(10);

    return view('doctor.patients.index', compact('patients'));
}

    public function show(User $patient)
    {
        // Verify doctor has access to this patient
        $this->authorize('view', $patient);

        $medicalRecords = $patient->medicalRecords()
            ->where('doctor_id', Auth::id())
            ->latest()
            ->get();

        return view('doctor.patients.show', compact('patient', 'medicalRecords'));
    }
}