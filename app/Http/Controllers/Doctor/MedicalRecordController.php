<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $records = MedicalRecord::with(['patient.user', 'medicines'])
            ->where('doctor_id', Auth::user()->doctor->doctor_id)
            ->when($request->patient, function($query, $patient) {
                return $query->whereHas('patient.user', function($q) use ($patient) {
                    $q->where('username', 'like', "%{$patient}%");
                });
            })
            ->when($request->date, function($query, $date) {
                return $query->whereDate('treatment_date', $date);
            })
            ->latest()
            ->paginate(10);

        return view('doctor.medical-records.index', compact('records'));
    }

    public function create()
    {
        $patients = Patient::with('user')->get();
        $medicines = Medicine::where('is_available', true)
            ->where('stock', '>', 0)
            ->get();
        
        return view('doctor.medical-records.create', compact('patients', 'medicines'));
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
            'status' => 'required|in:PENDING,IN_PROGRESS,COMPLETED',
            'follow_up_date' => 'nullable|date|after:treatment_date',
            'medicine_ids' => 'required|array|min:1',
            'medicine_ids.*' => 'exists:medicines,medicine_id'
        ]);

        $record = MedicalRecord::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => Auth::user()->doctor->doctor_id,
            'creator_doctor_id' => Auth::user()->doctor->doctor_id,
            'symptoms' => $validated['symptoms'],
            'diagnosis' => $validated['diagnosis'],
            'medical_action' => $validated['medical_action'],
            'lab_results' => $validated['lab_results'],
            'treatment_date' => $validated['treatment_date'],
            'notes' => $validated['notes'],
            'status' => $validated['status'],
            'follow_up_date' => $validated['follow_up_date']
        ]);

        $record->medicines()->attach($validated['medicine_ids']);

        return redirect()
            ->route('doctor.medical-records.index')
            ->with('success', 'Medical record created successfully');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['patient.user', 'medicines']);
        return view('doctor.medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        if (Auth::user()->doctor->doctor_id !== $medicalRecord->creator_doctor_id) {
            return redirect()
                ->route('doctor.medical-records.index')
                ->with('error', 'Unauthorized. Only the creator can edit this record.');
        }

        $patients = Patient::with('user')->get();
        $medicines = Medicine::where('is_available', true)
            ->where('stock', '>', 0)
            ->get();
            
        return view('doctor.medical-records.edit', 
            compact('medicalRecord', 'patients', 'medicines'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        if (Auth::user()->doctor->doctor_id !== $medicalRecord->creator_doctor_id) {
            return redirect()
                ->route('doctor.medical-records.index')
                ->with('error', 'Unauthorized. Only the creator can update this record.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'medical_action' => 'required|string',
            'lab_results' => 'nullable|string',
            'treatment_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:PENDING,IN_PROGRESS,COMPLETED',
            'follow_up_date' => 'nullable|date|after:treatment_date',
            'medicine_ids' => 'required|array|min:1',
            'medicine_ids.*' => 'exists:medicines,medicine_id'
        ]);

        $medicalRecord->update([
            'patient_id' => $validated['patient_id'],
            'symptoms' => $validated['symptoms'],
            'diagnosis' => $validated['diagnosis'],
            'medical_action' => $validated['medical_action'],
            'lab_results' => $validated['lab_results'],
            'treatment_date' => $validated['treatment_date'],
            'notes' => $validated['notes'],
            'status' => $validated['status'],
            'follow_up_date' => $validated['follow_up_date']
        ]);

        $medicalRecord->medicines()->sync($validated['medicine_ids']);

        return redirect()
            ->route('doctor.medical-records.index')
            ->with('success', 'Medical record updated successfully');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        if (Auth::user()->doctor->doctor_id !== $medicalRecord->creator_doctor_id) {
            return redirect()
                ->route('doctor.medical-records.index')
                ->with('error', 'Unauthorized. Only the creator can delete this record.');
        }

        $medicalRecord->medicines()->detach();
        $medicalRecord->delete();

        return redirect()
            ->route('doctor.medical-records.index')
            ->with('success', 'Medical record deleted successfully');
    }

    public function patientHistory(Patient $patient)
    {
        $records = MedicalRecord::with(['medicines'])
            ->where('patient_id', $patient->patient_id)
            ->where('doctor_id', Auth::user()->doctor->doctor_id)
            ->latest()
            ->get();

        return view('doctor.medical-records.history', compact('patient', 'records'));
    }
}