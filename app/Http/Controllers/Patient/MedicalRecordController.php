<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{

    public function myRecords()
    {
        $records = Auth::user()->patient->medicalRecords()
            ->with(['doctor.user', 'prescriptions'])
            ->latest('treatment_date')
            ->paginate(10);

        return view('patient.medical-records.index', compact('records'));
    }

    public function myPrescriptions()
    {
        $records = Auth::user()->patient->medicalRecords()
            ->with(['doctor.user', 'prescriptions'])
            ->whereHas('prescriptions')
            ->latest('treatment_date')
            ->paginate(10);

        return view('patient.medical-records.prescriptions', compact('records'));
    }
}