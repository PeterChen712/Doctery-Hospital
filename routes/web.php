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
    // Route::resource('schedules', DoctorScheduleController::class);
    Route::resource('patients', DoctorPatientController::class);
    Route::resource('prescriptions', DoctorPrescriptionController::class); // Add this line

    Route::resource('schedules', DoctorScheduleController::class);
    Route::get('/schedules/{id}/edit-data', [DoctorScheduleController::class, 'getEditData'])
        ->name('schedules.edit-data');
    // Route::resource('schedules', DoctorScheduleController::class, ['as' => 'doctor']);
    // Route::get('/doctor/schedules', [DoctorScheduleController::class, 'index'])->name('doctor.schedules.index');
    // Route::post('/doctor/schedules', [DoctorScheduleController::class, 'store'])->name('doctor.schedules.store');
    // Route::get('/doctor/schedules/{id}/edit', [DoctorScheduleController::class, 'edit'])->name('doctor.schedules.edit');
    // Route::put('/doctor/schedules/{id}', [DoctorScheduleController::class, 'update'])->name('doctor.schedules.update');
});

// Patient
// Route::middleware(['auth', 'patient'])->prefix('patient')->as('patient.')->group(function () {


Route::middleware('auth')->prefix('patient')->as('patient.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'patient'])->name('dashboard');

    // Profile Management
    Route::controller(SetupProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');
        Route::get('/profile/create', 'create')->name('profile.create');
        Route::post('/profile', 'store')->name('profile.store');
    });

    // Appointments Management - Using Resource Controller
    Route::resource('appointments', PatientAppointmentController::class);
    Route::controller(PatientAppointmentController::class)->group(function () {
        Route::put('appointments/{appointment}/cancel', 'cancel')->name('appointments.cancel');
        Route::put('appointments/{appointment}/reschedule', 'reschedule')->name('appointments.reschedule');
        Route::get('doctors/{doctor}/schedules', 'getDoctorSchedules')->name('doctors.schedules');
    });

    // Medical Records
    Route::controller(PatientMedicalRecordController::class)->group(function () {
        Route::get('medical-records', 'myRecords')->name('medical-records');
        Route::get('prescriptions', 'myPrescriptions')->name('prescriptions');
    });

    // Feedback 
    Route::resource('feedback', FeedbackController::class)->only(['store', 'update']);

    // Notifications
    Route::controller(NotificationController::class)->group(function () {
        Route::get('notifications', 'index')->name('notifications');
        Route::patch('notifications/{notification}', 'markAsRead')->name('notifications.markAsRead');
    });
});

require __DIR__ . '/auth.php';
