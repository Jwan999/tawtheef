<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\FormControlController;
use App\Models\FormControl;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/applicants/{id}/view-profile', [ApplicantController::class, 'viewApplicantProfile'])->name('applicant.view_profile');
Route::get('/applicants/{id}/generate-profile', [ApplicantController::class, 'generateApplicantProfile'])->name('applicant.generate_profile');

Route::get('/api/applicants/filter', [ApplicantController::class, 'getFilteredApplicants']);
Route::get('/api/applicants/search', [ApplicantController::class, 'searchApplicants']);

//Route::get('/api/search-applicants', [ApplicantController::class, 'searchApplicants']);
//Route::get('/api/applicants/search', [ApplicantController::class, 'getFilteredApplicants']);

Route::post('/api/applicant', [ApplicantController::class, 'store']);
Route::get('/api/applicants', [ApplicantController::class, 'index']);
Route::get('/api/selectables/{key}', [ApplicantController::class, 'getSelectables']);

Route::get('/api/profile', [ApplicantController::class, 'showPersonalProfile']);
Route::get('/api/applicants/{id}', [ApplicantController::class, 'showResume']);

Route::get('/api/auth', [ApplicantController::class, 'getAuthUser']);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/{any}', function () {
    return view('master');
})->where('any', '.*');


