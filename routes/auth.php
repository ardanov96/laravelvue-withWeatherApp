<?php

use App\Actions\Auth\ConfirmUserPassword;
use App\Actions\Auth\LoginUser;
use App\Actions\Auth\LogoutUser;
use App\Actions\Auth\RegisterNewUser;
use App\Actions\Auth\SendEmailVerificationNotification;
use App\Actions\Auth\SendPasswordResetLink;
use App\Actions\Auth\SetNewPassword;
use App\Actions\Auth\ShowEmailVerificationPrompt;
use App\Actions\Auth\UpdateUserPassword;
use App\Actions\Auth\VerifyUserEmail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia; // Pastikan ini ada

Route::middleware('guest')->group(function () {
    // Register
    Route::get('register', function () {
        return Inertia::render('Auth/Register'); 
    })->name('register');
    Route::post('register', RegisterNewUser::class);

    // Login
    Route::get('login', function () {
        return Inertia::render('Auth/Login'); 
    })->name('login');
    Route::post('login', LoginUser::class);

    // Forgot Password
    Route::get('forgot-password', function () {
        return Inertia::render('Auth/ForgotPassword'); 
    })->name('password.request');
    Route::post('forgot-password', SendPasswordResetLink::class)->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', function (string $token) {
        return Inertia::render('Auth/ResetPassword', ['token' => $token]);
    })->name('password.reset');
    Route::post('reset-password', SetNewPassword::class)->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', ShowEmailVerificationPrompt::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyUserEmail::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', SendEmailVerificationNotification::class)
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Confirm Password
    Route::get('confirm-password', function () {
        return Inertia::render('Auth/ConfirmPassword'); 
    })->name('password.confirm');
    Route::post('confirm-password', ConfirmUserPassword::class);

    // Update Password (from profile)
    Route::put('password', UpdateUserPassword::class)->name('password.update');

    // Logout
    Route::post('logout', LogoutUser::class)->name('logout');
});