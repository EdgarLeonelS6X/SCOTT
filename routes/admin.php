<?php

use App\Http\Controllers\Admin\GrafanaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ChannelController;
use App\Http\Controllers\Admin\StageController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('/users', UserController::class)->middleware(['auth', 'verified']);

Route::put('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions');

Route::resource('/channels', ChannelController::class)
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Channel']);

Route::resource('/stages', StageController::class)->middleware(['auth', 'verified', 'can:viewAny,App\Models\Stage']);

Route::resource('/grafana', GrafanaController::class)->middleware(['auth', 'verified']);
