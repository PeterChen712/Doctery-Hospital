<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Notification;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        switch ($user->role) {
            case 'admin':
                return $this->admin();
            case 'doctor':
                return $this->doctor();
            case 'patient':
                return $this->patient();
            default:
                return redirect('/');
        }
    }

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
        $user = Auth::user();
        $patient = $user->patient;

        $appointments = $patient->appointments()
            ->with('doctor.user')
            ->where('status', '!=', 'CANCELLED')
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        $medicalRecords = $patient->medicalRecords()
            ->with(['doctor.user', 'prescriptions'])
            ->latest('treatment_date')
            ->get();

        $prescriptions = $patient->medicalRecords()
            ->with(['doctor.user', 'prescriptions'])
            ->whereHas('prescriptions')
            ->latest('treatment_date')
            ->get();

        // Fix: Use user_notifications table
        $notifications = $user->customNotifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('patient.dashboard', compact(
            'appointments',
            'medicalRecords',
            'prescriptions',
            'notifications'
        ));
    }
}
