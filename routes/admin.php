<?php

use App\Http\Controllers\Admin\ChannelController;
use App\Http\Controllers\Admin\StageController;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('/channels', ChannelController::class);

Route::resource('/stages', StageController::class);