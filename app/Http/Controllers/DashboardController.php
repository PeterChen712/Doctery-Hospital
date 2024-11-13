<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $activeDoctors = Doctor::where('is_available', true)
            ->whereHas('schedules', function ($query) {
                $query->whereDate('schedule_date', today());
            })
            ->with('user')
            ->get();

        $userStats = [
            'total_doctors' => User::role('doctor')->count(),
            'total_patients' => User::role('patient')->count(),
            'total_admins' => User::role('admin')->count(),
        ];

        return view('admin.dashboard', compact('activeDoctors', 'userStats'));
    }

    public function doctor()
    {
        $recentPatients = MedicalRecord::with('patient')
            ->where('doctor_id', Auth::user()->doctor->doctor_id)
            ->latest()
            ->take(5)
            ->get();

        $todayAppointments = Auth::user()->doctor
            ->appointments()
            ->whereDate('appointment_date', today())
            ->with('patient')
            ->get();

        return view('doctor.dashboard', compact('recentPatients', 'todayAppointments'));
    }

    public function patient()
    {
        $treatments = MedicalRecord::with(['doctor', 'prescriptions.medicine'])
            ->where('patient_id', Auth::user()->patient->patient_id)
            ->latest()
            ->get();

        $upcomingAppointments = Auth::user()->patient
            ->appointments()
            ->where('appointment_date', '>', now())
            ->with('doctor')
            ->get();

        return view('patient.dashboard', compact('treatments', 'upcomingAppointments'));
    }
}
