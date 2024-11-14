<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\UserSetupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    //Youtube
    // Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    // Route::post('/forgot-password', [ResetPasswordController::class, 'passwordEmail']);
    // Route::get('/reset-password/{token}', [ResetPasswordController::class, 'passwordReset'])->name('password.reset');
    // Route::post('/reset-password', [ResetPasswordController::class, 'passwordUpdate'])->name('password.update');


    //reset password
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // google and rechatpcha
    // Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    // Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

    // Route::get('/user-setup', [UserSetupController::class, 'show'])
    //     ->name('user.setup')
    //     ->withoutMiddleware(['verified']);
    // Route::post('/user-setup', [UserSetupController::class, 'store'])
    //     ->name('user.setup.store')
    //     ->withoutMiddleware(['verified']);

    Route::controller(SocialiteController::class)->group(function() {
        Route::get('auth/redirection/{provider}', 'authProviderRedirect')->name('auth.redirection');
    
        Route::get('auth/{provider}/callback', 'socialAuthentication')->name('auth.callback');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'notifications'])->name('notifications');
    Route::patch('/notifications/{notification}', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read');

    // Download Medical Records (with proper authorization)
    Route::get('/medical-records/{record}/download', [MedicalRecordController::class, 'download'])
        ->middleware('can:view,record')
        ->name('medical-records.download');
});
