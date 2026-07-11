<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\PublicHomeController;


Route::get('/', [PublicHomeController::class, 'index'])->name('public.home');

Route::get('/standings', [PublicHomeController::class, 'standings'])->name('public.standings');


Route::get('/fixtures', [PublicHomeController::class, 'fixtures'])->name('public.fixtures');

Route::get('/results', [PublicHomeController::class, 'results'])->name('public.results');


Route::get('/news', [PublicHomeController::class, 'newsArchive'])->name('public.news.index');




Route::get('/admin', function () {
    return view('admin.index');
})->name('admin.index');


Route::resource('admin/teams', TeamController::class);
Route::resource('admin/players', PlayerController::class);
Route::resource('admin/fixtures', FixtureController::class);


Route::get('admin/scoring', [ScoringController::class, 'index'])->name('scoring.index');
Route::get('admin/scoring/{fixture}', [ScoringController::class, 'showDashboard'])->name('scoring.dashboard');
Route::post('admin/scoring/{fixture}/update', [ScoringController::class, 'updateScore'])->name('scoring.update');