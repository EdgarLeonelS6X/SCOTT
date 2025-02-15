<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ReportController;
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

Route::resource('/reports', ReportController::class)->middleware('auth');