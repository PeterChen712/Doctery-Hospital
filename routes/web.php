<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Doctor\MedicalRecordController as DoctorMedicalRecordController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\ScheduleController as DoctorScheduleController;
use App\Http\Controllers\Doctor\DoctorPatientController;
use App\Http\Controllers\Doctor\PrescriptionController as DoctorPrescriptionController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\MedicalRecordController as PatientMedicalRecordController;
use App\Http\Controllers\Patient\PatientProfileController as CompletePatientProfile;
use App\Http\Controllers\Patient\ProfileController as SetupProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReportController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/avatar/{id}', [ProfileController::class, 'showAvatar'])->name('avatar.show');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    // Users Management
    Route::resource('users', UserController::class, ['as' => 'admin']);

    // Medicines Management
    Route::resource('medicines', MedicineController::class, ['as' => 'admin']);

    // Reports Routes
    Route::get('/reports/users', [ReportController::class, 'users'])->name('admin.reports.users');
    Route::get('/reports/medicines', [ReportController::class, 'medicines'])->name('admin.reports.medicines');
    Route::get('/reports/appointments', [ReportController::class, 'appointments'])->name('admin.reports.appointments');
    Route::get('/reports/prescriptions', [ReportController::class, 'prescriptions'])->name('admin.reports.prescriptions');

    // Report Exports
    Route::get('/reports/users/export', [ReportController::class, 'exportUsers'])->name('admin.reports.users.export');
    Route::get('/reports/medicines/export', [ReportController::class, 'exportMedicines'])->name('admin.reports.medicines.export');
});

// Doctor Routes
Route::middleware(['auth', 'doctor'])->prefix('doctor')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'doctor'])->name('doctor.dashboard');

    // Patient Management
    Route::get('/patients', [DoctorPatientController::class, 'index'])->name('doctor.patients.index');
    Route::get('/patients/{patient}', [DoctorPatientController::class, 'show'])->name('doctor.patients.show');

    // Medical Records Management
    Route::resource('records', DoctorMedicalRecordController::class, ['as' => 'doctor']);

    // Appointments
    Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('doctor.appointments.index');
    Route::get('/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('doctor.appointments.show');
    Route::put('/appointments/{appointment}', [DoctorAppointmentController::class, 'update'])->name('doctor.appointments.update');

    // Schedules
    Route::resource('schedules', DoctorScheduleController::class, ['as' => 'doctor']);

    // Prescriptions
    Route::resource('prescriptions', DoctorPrescriptionController::class, ['as' => 'doctor']);
});

// Patient Routes
Route::middleware(['auth'])->prefix('patient')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'patient'])->name('patient.dashboard');

    // Appointments
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('patient.appointments.index');
    Route::get('/appointments/create', [PatientAppointmentController::class, 'create'])->name('patient.appointments.create');
    Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('patient.appointments.store');
    Route::get('/appointments/{appointment}', [PatientAppointmentController::class, 'show'])->name('patient.appointments.show');
    Route::put('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('patient.appointments.cancel');
    Route::put('/appointments/{appointment}/reschedule', [PatientAppointmentController::class, 'reschedule'])->name('patient.appointments.reschedule');
    Route::get('/doctors/{doctor}/schedules', [PatientAppointmentController::class, 'getDoctorSchedules']);

    // Medical Records
    Route::get('/medical-records', [PatientMedicalRecordController::class, 'myRecords'])->name('patient.medical-records');
    Route::get('/prescriptions', [PatientMedicalRecordController::class, 'myPrescriptions'])->name('patient.prescriptions');

    // Feedback
    Route::resource('feedback', FeedbackController::class)->only(['store', 'update']);

    // UserNotification
    Route::get('/notifications', [NotificationController::class, 'index'])->name('patient.notifications');
    Route::patch('/notifications/{notification}', [NotificationController::class, 'markAsRead'])->name('patient.notifications.markAsRead');

    // Profile routes

    Route::get('/profile/create', [CompletePatientProfile::class, 'create'])->name('patient.profile.create');
    Route::post('/profile', [CompletePatientProfile::class, 'store'])->name('patient.profile.store');
    Route::get('/profile', [SetupProfileController::class, 'show'])->name('patient.profile.show');
    Route::get('/profile/modify', [SetupProfileController::class, 'edit'])->name('patient.profile.edit');
    Route::put('/profile/modify', [SetupProfileController::class, 'update'])->name('patient.profile.update'); // Changed name
});

require __DIR__ . '/auth.php';
