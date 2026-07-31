<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CriteriaController;

// --- PUBLIC ROUTES ---
Route::get('/', [LoginController::class, 'showLogin']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --- AUTH PROTECTED ROUTES ---
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // 1. AREA HCM
    Route::middleware(['role:hcm'])->prefix('hcm')->name('hcm.')->group(function () {

        // Dashboard & Logs
Route::get('/dashboard', [ApplicantController::class, 'dashboard'])->name('dashboard');
Route::get('/logs', [ApplicantController::class, 'showLogs'])->name('logs');

// GUNAKAN SATU BARIS INI SAJA
Route::get('/hcm/logs/detail/{id}', [ApplicantController::class, 'showLogDetail'])->name('hcm.logs.show');

        Route::get('/positions', [ApplicantController::class, 'indexPositions'])->name('positions');
        Route::post('/positions/store', [ApplicantController::class, 'storePosition'])->name('positions.store');
        Route::delete('/positions/{position}', [ApplicantController::class, 'destroyPosition'])->name('positions.destroy');

        // Manajemen Kandidat (URUTAN SANGAT PENTING DISINI)
        Route::get('/candidates', [ApplicantController::class, 'index'])->name('candidates');
        Route::get('/candidates/create', [ApplicantController::class, 'create'])->name('candidates.create'); // Harus di atas {id}
        Route::post('/candidates/store', [ApplicantController::class, 'store'])->name('candidates.store');

        // Penilaian Tahap Awal (Aptitude)
        Route::get('/aptitude', [ApplicantController::class, 'aptitude'])->name('aptitude');
        Route::post('/aptitude/update/{id}', [ApplicantController::class, 'updateAptitude'])->name('aptitude.update');

        // Detail & Hapus Kandidat (Menggunakan Parameter diletakkan paling bawah)
        Route::get('/candidates/{id}', [ApplicantController::class, 'show'])->name('candidates.show');
        Route::delete('/candidates/{id}', [ApplicantController::class, 'destroy'])->name('candidates.destroy');

        // Pengaturan Kriteria (SAW)
        Route::get('/criteria', [CriteriaController::class, 'index'])->name('criteria');
        Route::put('/criteria/{id}', [CriteriaController::class, 'update'])->name('criteria.update');

        // Manajemen User
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 2. AREA ACCOUNT MANAGER (AM)
    Route::middleware(['role:am'])->prefix('am')->name('am.')->group(function () {
        Route::get('/dashboard', [ApplicantController::class, 'dashboard'])->name('dashboard');
        Route::get('/assessment', [ApplicantController::class, 'indexAM'])->name('assessment');
        Route::post('/assessment/{id}', [ApplicantController::class, 'updateTechnicalScore'])->name('assessment.update');
        Route::get('/history', [ApplicantController::class, 'amHistory'])->name('history');
    });

    // 3. AREA DIREKTUR
    Route::middleware(['role:director,direktur'])->prefix('director')->name('director.')->group(function () {
        Route::get('/dashboard', [ApplicantController::class, 'dashboard'])->name('dashboard');
        Route::get('/assessment', [ApplicantController::class, 'assessment'])->name('assessment');
        Route::post('/assessment/update/{id}', [ApplicantController::class, 'updateFinalAssessment'])->name('assessment.update');
        Route::get('/ranking', [ApplicantController::class, 'showRanking'])->name('ranking');
    });
});
