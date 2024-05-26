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

Route::get('/api/selectables/{key}',[FormControlController::class, 'getSelectables']);

Route::post('/api/applicant', [ApplicantController::class, 'store']);
Route::get('/api/applicants', [ApplicantController::class, 'index']);
Route::get('/api/applicants/{id}', [ApplicantController::class, 'show']);
Route::get('/api/auth', [ApplicantController::class, 'getAuthUserId']);
//Route::get('/api/filter/{speciality}', [ApplicantController::class, 'filter']);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/{any}', function () {
    return view('master');
})->where('any', '.*');


