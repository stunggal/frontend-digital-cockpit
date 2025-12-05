<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HybridAppController;
use App\Http\Controllers\PasienController;
use App\Http\Middleware\AuthenticateApiSession;
use Illuminate\Support\Facades\Route;

// Public auth routes (no session middleware)
// - GET  /login  -> shows the login form
// - POST /login  -> posts credentials to external auth API via AuthController::loginPost
// - GET  /reset  -> shows password reset view
Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('auth.loginPost');
Route::get('/reset', [AuthController::class, 'reset'])->name('auth.passwordUpdate');

// Routes that require a valid API session stored in the user's session.
// The `AuthenticateApiSession` middleware checks for `api_token` in session and
// may redirect to `/login` if missing/invalid. Most controller actions here
// call external APIs using that token and either return views or JSON.
Route::middleware([AuthenticateApiSession::class])->group(function () {
    // Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    // Patient vitals endpoints (AJAX): return JSON to update realtime widgets
    Route::get('/get-heart-rate', [PasienController::class, 'getHeartRate'])->name('pasien.getHeartRate');
    Route::get('/get-blood-pressure', [PasienController::class, 'getBloodPressure'])->name('pasien.getBloodPressure');
    Route::get('/get-spo2', [PasienController::class, 'getSpo2'])->name('pasien.getSpo2');

    // Trigger a consultation flow (calls external webhook via PasienController::consult)
    Route::get('/consult', [PasienController::class, 'consult'])->name('pasien.consult');

    // Dokter listing page
    Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');

    // Pasien listing and detail pages
    Route::get('/pasiens', [PasienController::class, 'index'])->name('pasien.index');
    Route::get('/pasien/{id}', [PasienController::class, 'view'])->name('pasien.view');

    // Misc static/demo pages served from HomeController
    Route::get('/model', [HomeController::class, 'model'])->name('model');
    Route::get('/urat', [HomeController::class, 'urat'])->name('urat');
    Route::get('/aaa', [HomeController::class, 'aaa'])->name('aaa');
    Route::get('/setengah', [HomeController::class, 'setengah'])->name('setengah');
    Route::get('/utuh', [HomeController::class, 'utuh'])->name('utuh');

    // LLM-based food recommendation (expects POST with `description`)
    Route::post('/get-food-recommendation', [PasienController::class, 'getFoodRecommendation'])->name('pasien.getFoodRecommendation');
});
// Logout: clears session token and redirects to login
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/hybrid', [HybridAppController::class, 'index'])->name('hybrid.index');

Route::get('/offline', function () {
    return response()->file(public_path('offline.html'));
})->name('offline');

// Lightweight test route to inspect an environment variable (dev-only)
Route::get('/test-env', [HomeController::class, 'testEnv'])->name('home.testEnv');
