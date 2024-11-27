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
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login');
            }

            if ($user->role !== 'patient') {
                return redirect()->route($user->role . '.dashboard');
            }

            $patient = Patient::where('user_id', $user->user_id)->with('user')->first();
            
            if (!$patient) {
                return redirect()
                    ->route('patient.profile.create')
                    ->with('warning', 'Please complete your profile first');
            }

            $appointments = Appointment::with(['doctor.user', 'schedule'])
                ->where('patient_id', $patient->patient_id)
                ->where('status', '!=', 'CANCELLED')
                ->whereDate('appointment_date', '>=', now())
                ->orderBy('appointment_date')
                ->take(5)
                ->get();

            $medicalRecords = MedicalRecord::with(['doctor.user'])
                ->where('patient_id', $patient->patient_id)
                ->latest('treatment_date')
                ->take(5)
                ->get();

            $prescriptions = MedicalRecord::with(['doctor.user', 'prescriptions'])
                ->where('patient_id', $patient->patient_id)
                ->whereHas('prescriptions')
                ->latest('treatment_date')
                ->take(5)
                ->get();

            $notifications = $user->notifications()
                ->whereNull('read_at')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('patient.dashboard', compact(
                'patient',
                'appointments',
                'medicalRecords',
                'prescriptions',
                'notifications'
            ));

        } catch (\Exception $e) {
            Log::error('Patient dashboard error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Error loading dashboard. Please try again.');
        }
    }
}