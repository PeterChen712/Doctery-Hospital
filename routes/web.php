<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Doctor\MedicalRecordController as DoctorMedicalRecordController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\ScheduleController as DoctorScheduleController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\MedicalRecordController as PatientMedicalRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

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
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::resource('users', UserController::class);
    Route::resource('medicines', MedicineController::class);
    Route::get('/reports/users', [UserController::class, 'reports'])->name('admin.reports.users');
    Route::get('/reports/medicines', [MedicineController::class, 'reports'])->name('admin.reports.medicines');
});

// Doctor Routes
Route::middleware(['auth'])->prefix('doctor')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'doctor'])->name('doctor.dashboard');
    
    // Appointments
    Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('doctor.appointments.index');
    Route::get('/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('doctor.appointments.show');
    Route::put('/appointments/{appointment}/confirm', [DoctorAppointmentController::class, 'confirm'])->name('doctor.appointments.confirm');
    Route::put('/appointments/{appointment}/complete', [DoctorAppointmentController::class, 'complete'])->name('doctor.appointments.complete');
    
    // Schedules
    // Route::resource('schedules', ScheduleController::class);
    
    // Medical Records
    Route::get('/patients', [DoctorMedicalRecordController::class, 'index'])->name('doctor.patients.index');
    Route::get('/patients/{patient}', [DoctorMedicalRecordController::class, 'show'])->name('doctor.patients.show');
    Route::post('/patients/{patient}/records', [DoctorMedicalRecordController::class, 'store'])->name('doctor.patients.records.store');
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
});

require __DIR__.'/auth.php';