<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\ScoringController;

Route::get('/', function () {
    return view('public.home');
});

Route::get('/admin', function () {
    return view('admin.index');
});

Route::resource('admin/teams', TeamController::class);
Route::resource('admin/players', PlayerController::class);
Route::resource('admin/fixtures', FixtureController::class);

Route::get('admin/scoring', [ScoringController::class, 'index'])->name('scoring.index');
Route::get('admin/scoring/{fixture}', [ScoringController::class, 'showDashboard'])->name('scoring.dashboard');
Route::post('admin/scoring/{fixture}/update', [ScoringController::class, 'updateScore'])->name('scoring.update');