<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Notification;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller 
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::user()->role == 'doctor') {
            return redirect()->route('doctor.dashboard');
        }

        if (Auth::user()->role == 'patient') {
            return redirect()->route('patient.dashboard');
        }

        return redirect('/');
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
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return redirect()
                ->route('login')
                ->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        $recentPatients = MedicalRecord::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->latest('treatment_date')
            ->take(5)
            ->get();

        $todayAppointments = Appointment::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->get();

        $ongoingTreatments = MedicalRecord::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'ONGOING')
            ->latest()
            ->take(5)
            ->get();

        $todayPatients = MedicalRecord::where('doctor_id', $doctor->id)
            ->whereDate('treatment_date', today())
            ->count();

        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'PENDING')
            ->count();

        $followUps = MedicalRecord::where('doctor_id', $doctor->id)
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
        
        // First check if user exists and has correct role
        if (!$user) {
            return redirect()->route('login');
        }

        // If user is not a patient, redirect to appropriate dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }
        if ($user->role !== 'patient') {
            return redirect('/')->with('error', 'Invalid user role');
        }

        // Get patient record
        $patient = $user->patient;
        
        // If no patient record exists
        if (!$patient) {
            // Check if user registered themselves or was created by admin
            if ($user->created_by === null) { // Self registered
                return redirect()
                    ->route('patient.profile.create')
                    ->with('error', 'Please complete your patient profile first.');
            } else {
                // Created by admin but patient profile not yet set up
                return redirect('/')
                    ->with('error', 'Your patient profile is not yet set up. Please contact the administrator.');
            }
        }

        try {
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

        } catch (\Exception $e) {
            Log::error('Error in patient dashboard: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'An error occurred while loading your dashboard. Please try again later.');
        }
    }
}