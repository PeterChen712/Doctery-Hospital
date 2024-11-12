<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{

    public function index(Request $request)
    {
        $records = MedicalRecord::with(['patient', 'prescriptions'])
            ->where('doctor_id', Auth::user()->doctor->doctor_id)
            ->when($request->patient, function($query, $patient) {
                return $query->whereHas('patient', function($q) use ($patient) {
                    $q->where('name', 'like', "%{$patient}%");
                });
            })
            ->when($request->date, function($query, $date) {
                return $query->whereDate('treatment_date', $date);
            })
            ->latest()
            ->paginate(10);

        return view('doctor.records.index', compact('records'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'medical_action' => 'required|string',
            'lab_results' => 'nullable|string',
            'treatment_date' => 'required|date',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date|after:treatment_date',
            'medicines' => 'array',
            'medicines.*.medicine_id' => 'required|exists:medicines,medicine_id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.instructions' => 'required|string',
        ]);

        $record = MedicalRecord::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => Auth::user()->doctor->doctor_id,
            'symptoms' => $validated['symptoms'],
            'diagnosis' => $validated['diagnosis'],
            'medical_action' => $validated['medical_action'],
            'lab_results' => $validated['lab_results'],
            'treatment_date' => $validated['treatment_date'],
            'notes' => $validated['notes'],
            'follow_up_date' => $validated['follow_up_date'],
            'status' => 'IN_PROGRESS'
        ]);

        foreach ($validated['medicines'] as $medicine) {
            $record->prescriptions()->create([
                'medicine_id' => $medicine['medicine_id'],
                'quantity' => $medicine['quantity'],
                'dosage' => $medicine['dosage'],
                'instructions' => $medicine['instructions'],
                'status' => 'PENDING',
                'valid_until' => now()->addDays(30),
            ]);
        }

        return redirect()->route('doctor.records.index')
            ->with('success', 'Medical record created successfully');
    }
}