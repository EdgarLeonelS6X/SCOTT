<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/google-auth/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('redirectToGoogle');

Route::get('/google-auth/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('handleGoogleCallback');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/report-history', [HistoryController::class, 'index'])->name('report.history');