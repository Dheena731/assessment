<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ResumeController;

/*
|--------------------------------------------------------------------------
| Login Route
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    if (!auth()->check()) {
        return view('auth.login');
    }

    return auth()->user()->role === 'super_admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('resume.upload');
})->name('login');

/*
|--------------------------------------------------------------------------
| Google OAuth Routes
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->name('google.login');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('google.callback');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes - PRODUCTION PORTAL FLOW
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'nocache'])->group(function () {
    // Resume flow (ALWAYS accessible)
    Route::get('/resume-upload', [ResumeController::class, 'index'])->name('resume.upload');
    Route::post('/resume-upload', [ResumeController::class, 'store'])->name('resume.upload.store');
    Route::get('/resume/status/{resume}', [ResumeController::class, 'status'])->name('resume.status');
    
    // Dashboard redirect
    Route::get('/', fn() => redirect('/assessment/start'))->name('dashboard');
    
    // 🎯 ASSESSMENT WITH BACK BUTTON PROTECTION
        Route::get('/assessment/instructions', [AssessmentController::class, 'instructions'])->name('assessment.instructions');
        Route::get('/assessment/start', [AssessmentController::class, 'start'])->name('assessment.start');
        Route::post('/assessment/save', [AssessmentController::class, 'save'])->name('assessment.save');
        Route::get('/assessment/results/{userAssessment}', [AssessmentController::class, 'results'])->name('assessment.results');
        Route::post('/assessment/log-violation', [AssessmentController::class, 'logViolation']);
        Route::get('/assessment/{id}/download-report', [AssessmentController::class, 'downloadReport'])
        ->name('assessment.download-report');
        

});

/*

|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin', 'nocache'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Logout Route
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    return redirect()->route('login')
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT')
        ->header('Clear-Site-Data', 'cache, cookies, storage');
})->name('logout');
