<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\ScheduleController as DoctorScheduleController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController; 

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // User Management
    Route::resource('users', UserController::class);
    
    // Medicine Management
    Route::resource('medicines', MedicineController::class);
    
    // Reports and Statistics
    Route::get('/reports/users', [UserController::class, 'reports'])->name('admin.reports.users');
    Route::get('/reports/medicines', [MedicineController::class, 'reports'])->name('admin.reports.medicines');
});

// Doctor Routes
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->group(function () {
    // Medical Records
    Route::resource('medical-records', MedicalRecordController::class);
    
    // Doctor Schedules
    Route::resource('schedules', DoctorScheduleController::class);
    
    // Appointments Management
    Route::get('/appointments', [AppointmentController::class, 'doctorAppointments'])->name('doctor.appointments');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('doctor.appointments.status');
    
    // Patient History
    Route::get('/patients/{patient}/history', [MedicalRecordController::class, 'patientHistory'])->name('doctor.patient.history');
});

// Patient Routes
Route::middleware(['auth', 'role:patient'])->prefix('patient')->group(function () {
    // Appointments
    Route::resource('appointments', AppointmentController::class)->except(['edit', 'destroy']);
    
    // Medical History
    Route::get('/medical-records', [MedicalRecordController::class, 'myRecords'])->name('patient.medical-records');
    
    // Prescriptions
    Route::get('/prescriptions', [MedicalRecordController::class, 'myPrescriptions'])->name('patient.prescriptions');
    
    // Feedback
    Route::resource('feedback', FeedbackController::class)->only(['store', 'update']);
});

// Shared Routes
Route::middleware(['auth'])->group(function () {
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'notifications'])->name('notifications');
    Route::patch('/notifications/{notification}', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read');
    
    // Download Medical Records (with proper authorization)
    Route::get('/medical-records/{record}/download', [MedicalRecordController::class, 'download'])
        ->middleware('can:view,record')
        ->name('medical-records.download');
});

require __DIR__.'/auth.php';