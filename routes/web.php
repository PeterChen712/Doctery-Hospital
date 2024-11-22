<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Doctor\MedicalRecordController as DoctorMedicalRecordController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\ScheduleController as DoctorScheduleController;
use App\Http\Controllers\Doctor\ProfileController as DoctorProfileController;
use App\Http\Controllers\Doctor\DoctorPatientController;
use App\Http\Controllers\Doctor\PrescriptionController as DoctorPrescriptionController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\MedicalRecordController as PatientMedicalRecordController;
use App\Http\Controllers\Patient\CompleteProfileController;
use App\Http\Controllers\Patient\ProfileController as SetupProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('user.profile.edit'); // Changed name
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('user.profile.update'); // Changed name
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('user.profile.destroy'); // Changed name
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

    // Profile Management for Admin
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

   
});


// // Doctor Routes
// Route::middleware(['auth', 'doctor'])->prefix('doctor')->group(function () {
//     // Dashboard
//     Route::get('/dashboard', [DashboardController::class, 'doctor'])->name('doctor.dashboard');

//     // Patient Management
//     Route::get('patients', [DoctorPatientController::class, 'index'])->name('patients.index');
//     Route::get('patients/{patient}', [DoctorPatientController::class, 'show'])->name('patients.show');
//     Route::get('patients/{patient}/history', [DoctorPatientController::class, 'history'])->name('patients.history');

//     // Medical Records
//     Route::resource('medical-records', DoctorMedicalRecordController::class);
//     Route::post('medical-records/{record}/prescribe', [DoctorMedicalRecordController::class, 'prescribe'])->name('medical-records.prescribe');

//     // Appointment Management
//     Route::resource('appointments', DoctorAppointmentController::class);
//     Route::post('appointments/{appointment}/confirm', [DoctorAppointmentController::class, 'confirm'])->name('appointments.confirm');
//     Route::post('appointments/{appointment}/cancel', [DoctorAppointmentController::class, 'cancel'])->name('appointments.cancel');
//     Route::post('appointments/{appointment}/complete', [DoctorAppointmentController::class, 'complete'])->name('appointments.complete');

//     // Schedules
//     Route::resource('schedules', DoctorScheduleController::class, ['as' => 'doctor']);
//     Route::post('schedules/{schedule}/toggle', [DoctorScheduleController::class, 'toggleStatus'])->name('schedules.toggle');
//     Route::get('schedules/available/{doctor}', [DoctorScheduleController::class, 'getAvailableSlots'])->name('schedules.available');

//     // Prescriptions
//     Route::resource('prescriptions', DoctorPrescriptionController::class, ['as' => 'doctor']);

//     // Profile Management - Updated name
//     Route::get('profile', [DoctorProfileController::class, 'edit'])
//         ->name('doctor.profile.edit'); // Changed name
//     Route::put('profile', [DoctorProfileController::class, 'update'])
//         ->name('doctor.profile.update'); // Changed name
// });



Route::middleware(['auth', 'doctor'])->prefix('doctor')->as('doctor.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'doctor'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [DoctorProfileController::class, 'update'])->name('profile.update');

    // Doctor resources
    Route::resource('appointments', DoctorAppointmentController::class);
    Route::resource('records', DoctorMedicalRecordController::class);
    Route::resource('schedules', DoctorScheduleController::class);
    Route::resource('patients', DoctorPatientController::class);
    Route::resource('prescriptions', DoctorPrescriptionController::class); // Add this line
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
    
    Route::get('doctor-schedules/{doctor}', [DoctorScheduleController::class, 'getAvailableSchedules'])
        ->name('doctor.schedules.available');

    // Medical Records
    Route::get('/medical-records', [PatientMedicalRecordController::class, 'myRecords'])->name('patient.medical-records');
    Route::get('/prescriptions', [PatientMedicalRecordController::class, 'myPrescriptions'])->name('patient.prescriptions');

    // Feedback
    Route::resource('feedback', FeedbackController::class)->only(['store', 'update']);

    // UserNotification
    Route::get('/notifications', [NotificationController::class, 'index'])->name('patient.notifications');
    Route::patch('/notifications/{notification}', [NotificationController::class, 'markAsRead'])->name('patient.notifications.markAsRead');

    // Profile routes
    Route::get('/profile/create', [CompleteProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [CompleteProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile', [SetupProfileController::class, 'show'])->name('patient.profile.show');
    Route::get('/profile/edit', [SetupProfileController::class, 'edit'])->name('patient.profile.edit'); // Changed name
    Route::put('/profile', [SetupProfileController::class, 'update'])->name('patient.profile.update'); // Changed name
});

require __DIR__ . '/auth.php';
