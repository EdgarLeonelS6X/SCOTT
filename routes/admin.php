<?php

use App\Http\Controllers\Admin\ChannelController;
use App\Http\Controllers\Admin\GrafanaController;
use App\Http\Controllers\Admin\RadioController;
use App\Http\Controllers\Admin\StageController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('/users', UserController::class)->middleware(['auth', 'verified']);

Route::put('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions');

Route::resource('/channels', ChannelController::class)
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Channel']);

Route::resource('/stages', StageController::class)
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Stage']);

Route::resource('/radios', RadioController::class)
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Radio']);

Route::resource('/grafana', GrafanaController::class)
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\GrafanaPanel']);

Route::match(['get', 'post'], '/user/switch-area/{area}', [UserController::class, 'switchArea'])->name('user.switch-area')->middleware(['auth', 'verified']);
