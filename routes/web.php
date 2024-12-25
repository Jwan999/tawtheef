<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\FormControlController;
use App\Models\FormControl;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



// Public routes
Route::post('/signup', [AuthController::class, 'signup'])
    ->middleware('recaptcha');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout']);

// Password Reset Routes
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', function() {
    return view('master');
})->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/test-mail', function() {
    Mail::raw('Test email content', function($message) {
        $message->to('jwanaalfatla1999@gmail.com')
            ->subject('Test Email');
    });

    return 'Test email sent!';
});

Route::get('/api/statistics', [ApplicantController::class, 'getStatistics']);


// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/api/applicants', [ApplicantController::class, 'index']);
    Route::get('/dashboard', function () {
        return view('master');
    });
});

// Auth required routes
Route::middleware(['auth'])->group(function () {
    Route::get('/api/profile', [ApplicantController::class, 'showPersonalProfile']);
    Route::post('/api/applicant', [ApplicantController::class, 'store']);
});

// Public API routes
Route::get('/applicants/{id}/view-profile', [ApplicantController::class, 'viewApplicantProfile'])->name('applicant.view_profile');
Route::get('/applicants/{id}/generate-profile', [ApplicantController::class, 'generateApplicantProfile'])->name('applicant.generate_profile');
Route::get('/api/applicants/filter', [ApplicantController::class, 'getFilteredApplicants']);
Route::get('/api/applicants/search', [ApplicantController::class, 'searchApplicants']);
Route::get('/api/selectables/{key}', [ApplicantController::class, 'getSelectables']);
Route::get('/api/applicants/{id}', [ApplicantController::class, 'showResume']);
Route::get('/api/auth', [ApplicantController::class, 'getAuthUser']);


// Catch-all route
Route::get('/{any}', function () {
    return view('master');
})->where('any', '^(?!dashboard$).*');
