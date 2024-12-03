<?php

use App\Http\Controllers\ProfileController;

//Admin
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

//Doctor
use App\Http\Controllers\Doctor\MedicalRecordController as DoctorMedicalRecordController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\ScheduleController as DoctorScheduleController;
use App\Http\Controllers\Doctor\ProfileController as DoctorProfileController;
use App\Http\Controllers\Doctor\DoctorPatientController;

//Patient
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\MedicalRecordController as PatientMedicalRecordController;
use App\Http\Controllers\Patient\CompleteProfileController;
use App\Http\Controllers\Patient\PatientViewDoctorController;
use App\Http\Controllers\Patient\ProfileController as SetupProfileController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Patient\FeedbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Doctor\FeedbackResponseController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('user.profile.edit'); // Changed name
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('user.profile.update'); // Changed name
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('user.profile.destroy'); // Changed name
    Route::get('/avatar/{id}', [ProfileController::class, 'showAvatar'])->name('avatar.show');

    
});




// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Users Management
    Route::resource('users', UserController::class);

    // Medicines Management
    Route::resource('medicines', MedicineController::class)->parameters([
        'medicines' => 'medicine:medicine_id'
    ]);


    // Medicine Image Route
    Route::get('/medicine/image/{medicine:medicine_id}', [MedicineController::class, 'showImage'])
        ->name('medicines.image');

    // Reports Routes
    Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/medicines', [ReportController::class, 'medicines'])->name('reports.medicines');
    Route::get('/reports/appointments', [ReportController::class, 'appointments'])->name('reports.appointments');
    Route::get('/reports/prescriptions', [ReportController::class, 'prescriptions'])->name('reports.prescriptions');

    // Report Exports
    Route::get('/reports/users/export', [ReportController::class, 'exportUsers'])->name('reports.users.export');
    Route::get('/reports/medicines/export', [ReportController::class, 'exportMedicines'])->name('reports.medicines.export');

    // Admin Profile Management
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Appointments Management
    Route::resource('appointments', AdminAppointmentController::class);
    Route::put('appointments/{appointment}/assign-doctor', [AdminAppointmentController::class, 'assignDoctor'])
        ->name('appointments.assign-doctor');
    Route::put('appointments/{appointment}/update-status', [AdminAppointmentController::class, 'updateStatus'])
        ->name('appointments.update-status');
    Route::get('/doctors/{doctor}/schedules', [AdminAppointmentController::class, 'getDoctorSchedules'])
        ->name('doctors.schedules');
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
    Route::get('/profile/show', [DoctorProfileController::class, 'show'])->name('profile.show');

    // Doctor resources
    Route::resource('appointments', DoctorAppointmentController::class);
    Route::resource('medical-records', DoctorMedicalRecordController::class);
    // Route::resource('records', DoctorMedicalRecordController::class);
    Route::resource('patients', DoctorPatientController::class);


    // Route::resource('schedules', DoctorScheduleController::class);

    // Route::get('schedules/edit-data/{id}', [DoctorScheduleController::class, 'getEditData'])
    //     ->name('schedules.edit-data');
    // Route::get('schedules/by-date/{date}', [DoctorScheduleController::class, 'getSchedulesByDate'])
    //     ->name('schedules.byDate');
    // Route::get('/doctor/schedules/edit-data/{id}', [DoctorScheduleController::class, 'getScheduleData'])
    //     ->name('doctor.schedules.getData');


    Route::resource('schedules', DoctorScheduleController::class)->except(['show']);
    Route::get('schedules/{id}/edit-data', [DoctorScheduleController::class, 'getScheduleEditData'])
        ->name('schedules.edit-data');
    Route::get('schedules/by-date/{date}', [DoctorScheduleController::class, 'getSchedulesByDate'])
        ->name('schedules.by-date');


        Route::post('feedback/{feedback}/response', [FeedbackResponseController::class, 'store'])
        ->name('feedback.response');

    // Route::get('schedules/{id}/edit-data', [DoctorScheduleController::class, 'getScheduleEditData']);
    // Route::put('schedules/{id}', [DoctorScheduleController::class, 'update'])->name('schedules.update');
    // Route::get('schedules/{id}/edit', [DoctorScheduleController::class, 'edit'])->name('schedules.edit');
    // Route::delete('schedules/{id}', [DoctorScheduleController::class, 'destroy']);
    // Route::get('schedules/by-date/{date}', [DoctorScheduleController::class, 'getSchedulesByDate']);




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
    Route::controller(PatientProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');
        Route::get('/profile/create', 'create')->name('profile.create');
        Route::post('/profile', 'store')->name('profile.store');
    });

    // Appointments Management
    Route::resource('appointments', PatientAppointmentController::class);
    Route::controller(PatientAppointmentController::class)->group(function () {
        Route::put('appointments/{appointment}/cancel', 'cancel')->name('appointments.cancel');
        Route::put('appointments/{appointment}/reschedule', 'reschedule')->name('appointments.reschedule');
        Route::post('appointments/{appointment}/confirm', 'confirmAppointment')->name('appointments.confirm');
        Route::get('doctors/{doctor}/schedules', 'getDoctorSchedules')->name('doctors.schedules');
    });

    // Medical Records
    Route::controller(PatientMedicalRecordController::class)->group(function () {
        Route::get('medical-records', 'myRecords')->name('medical-records');
        Route::get('medical-records/{record}', 'show')->name('medical-records.show');
    });

    // Prescriptions
    Route::controller(PatientMedicalRecordController::class)->group(function () {
        Route::get('prescriptions', 'myPrescriptions')->name('prescriptions');
    });

    // View Doctor Profile
    Route::get('view-doctor/{doctor}', [PatientViewDoctorController::class, 'show'])->name('doctors.show');

    // Notifications
    Route::controller(NotificationController::class)->group(function () {
        Route::get('notifications', 'index')->name('notifications');
        Route::patch('notifications/{notification}', 'markAsRead')->name('notifications.markAsRead');
        Route::post('notifications/mark-all-read', 'markAllAsRead')->name('notifications.markAllAsRead');
    });

    // Feedback (optional)
    Route::controller(FeedbackController::class)->group(function () {
        Route::post('medical-records/{medicalRecord}/feedback', 'store')->name('medical-records.feedback.store');
    });
    
});
require __DIR__ . '/auth.php';
