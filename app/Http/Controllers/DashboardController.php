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

        // Recent patients
        $recentPatients = MedicalRecord::with(['patient.user'])
            ->where('doctor_id', $doctor->doctor_id)
            ->orderByDesc('treatment_date')
            ->take(5)
            ->get();

        // Ongoing treatments
        $ongoingTreatments = MedicalRecord::with(['patient.user'])
            ->where('doctor_id', $doctor->doctor_id)
            ->whereIn('status', ['ONGOING', 'IN_PROGRESS'])
            ->orderByDesc('updated_at')
            ->get();

        // Today's patients count
        $todayPatients = Appointment::where('doctor_id', $doctor->doctor_id)
            ->whereDate('appointment_date', today())
            ->where('status', 'CONFIRMED')
            ->count();

        // Pending appointments count
        $pendingAppointments = Appointment::where('doctor_id', $doctor->doctor_id)
            ->where('status', 'PENDING_CONFIRMATION')
            ->count();


        return view('doctor.dashboard', compact(
            'recentPatients',
            'ongoingTreatments',
            'todayPatients',
            'pendingAppointments',
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

            $prescriptions = MedicalRecord::with(['doctor.user', 'medicalRecordMedicines.medicine'])
                ->where('patient_id', $patient->patient_id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
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
