<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserNotification;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $records = MedicalRecord::with(['patient.user', 'medicines'])
            ->where('doctor_id', Auth::user()->doctor->doctor_id)
            ->when($request->patient, function ($query, $patient) {
                return $query->whereHas('patient.user', function ($q) use ($patient) {
                    $q->where('username', 'like', "%{$patient}%");
                });
            })
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('treatment_date', $date);
            })
            ->latest()
            ->paginate(10);

        return view('doctor.medical-records.index', compact('records'));
    }


    public function create()
    {
        $doctor = Auth::user()->doctor;

        // Get patients who have confirmed appointments with this doctor
        // that are either today or in the past
        $patients = Patient::whereHas('appointments', function ($query) use ($doctor) {
            $query->where('doctor_id', $doctor->doctor_id)
                ->where('status', Appointment::STATUS_CONFIRMED)
                ->where(function ($q) {
                    $q->whereDate('appointment_date', '<=', now());
                });
        })->with('user')->get();

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
            'medicines' => 'required|array|min:1',
            'medicines.*' => 'exists:medicines,medicine_id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'dosages' => 'required|array|min:1',
            'dosages.*' => 'required|string',
            'instructions' => 'required|array|min:1',
            'instructions.*' => 'required|string'
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

        // Attach medicines with quantities, dosages and instructions
        $medicines = collect($validated['medicines'])->mapWithKeys(function ($id, $key) use ($validated) {
            return [$id => [
                'quantity' => $validated['quantities'][$key],
                'dosage' => $validated['dosages'][$key],
                'instructions' => $validated['instructions'][$key]
            ]];
        });

        $record->medicines()->attach($medicines);

        // Create notification for patient
        UserNotification::create([
            'user_id' => $record->patient->user->user_id,
            'title' => 'New Medical Record Created',
            'data' => [
                'message' => Auth::user()->username . ' has created a new medical record.',
                'record_id' => $record->record_id,
                'doctor_name' => Auth::user()->username,
                'treatment_date' => $record->treatment_date->format('Y-m-d H:i:s')
            ],
            'type' => 'MEDICAL_RECORD',
            'notifiable_type' => MedicalRecord::class,
            'notifiable_id' => $record->record_id
        ]);

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

        return view(
            'doctor.medical-records.edit',
            compact('medicalRecord', 'patients', 'medicines')
        );
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
