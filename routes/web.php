<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;

Route::get('/', function () {
    return view('public.home');
});

Route::get('/admin', function () {
    return view('admin.index');
});

Route::resource('admin/teams', TeamController::class);
Route::resource('admin/players', PlayerController::class);