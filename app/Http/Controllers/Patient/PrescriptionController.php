<?php
// app/Http/Controllers/Patient/PrescriptionController.php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = MedicalRecord::with(['medicalRecordMedicines.medicine', 'doctor.user'])
            ->where('patient_id', Auth::user()->patient->patient_id)
            ->orderBy('treatment_date', 'desc')
            ->paginate(10);

        return view('patient.medical-records.prescriptions', compact('prescriptions'));
    }

    public function show(MedicalRecord $record)
    {
        $record->load(['medicalRecordMedicines.medicine', 'doctor.user']);
        return view('patient.prescriptions.show', compact('record'));
    }
}