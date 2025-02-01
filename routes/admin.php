<?php

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');