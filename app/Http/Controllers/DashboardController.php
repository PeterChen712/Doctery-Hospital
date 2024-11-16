<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Appointment;
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
        $recentPatients = MedicalRecord::with('patient.user')
            ->where('doctor_id', Auth::id())
            ->latest('treatment_date')
            ->take(5)
            ->get();

        $todayAppointments = Appointment::with('patient.user')
            ->where('doctor_id', Auth::id())
            ->whereDate('appointment_date', today())
            ->get();

        $ongoingTreatments = MedicalRecord::with('patient.user')
            ->where('doctor_id', Auth::id())
            ->where('status', 'ONGOING')
            ->latest()
            ->take(5)
            ->get();

        $todayPatients = MedicalRecord::where('doctor_id', Auth::user()->id)
            ->whereDate('treatment_date', today())
            ->count();

        $pendingAppointments = Appointment::where('doctor_id', Auth::id())
            ->where('status', 'PENDING')
            ->count();

        $followUps = MedicalRecord::where('doctor_id', Auth::id())
            ->whereDate('follow_up_date', today())
            ->count();

        return view('doctor.dashboard', compact(
            'recentPatients',
            'todayAppointments',
            'ongoingTreatments',
            'todayPatients',
            'pendingAppointments',
            'followUps'
        ));
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
