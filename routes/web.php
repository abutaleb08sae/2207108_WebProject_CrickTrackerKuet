<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\AuthController;
use App\Models\Team;
use App\Models\Player;
use App\Models\Fixture;

/*

| Public & Authentication Routes

*/
Route::middleware(['custom.auth'])->group(function () {
    Route::get('/', [PublicHomeController::class, 'index'])->name('public.home');
    Route::get('/international-matches', [PublicHomeController::class, 'internationalMatches'])->name('public.international');
    Route::get('/matches/{id}', [PublicHomeController::class, 'matchDetails'])->name('public.matches.show');
    Route::get('/standings', [PublicHomeController::class, 'standings'])->name('public.standings');
    Route::get('/fixtures', [PublicHomeController::class, 'fixtures'])->name('public.fixtures');
    Route::get('/results', [PublicHomeController::class, 'results'])->name('public.results');
    Route::get('/news', [PublicHomeController::class, 'newsArchive'])->name('public.news.index');
    Route::get('/external-match/{id}', [PublicHomeController::class, 'showMatchDetails'])->name('public.match.details');

    // Auth Handlers
    Route::get('/signin', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/signin', [AuthController::class, 'login']);
    Route::get('/signup', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/signout', [AuthController::class, 'logout'])->name('logout');
});

/*
| Strict Admin Dashboard Routes
*/
Route::middleware(['custom.auth', 'admin.strict'])->group(function () {
    
    // Admin Landing Portal
    Route::get('/admin', function () {
        $teamsCount = Team::count();
        $playersCount = Player::count();
        $liveMatchesCount = Fixture::where('status', 'LIVE')->count();

        return view('admin.index', compact('teamsCount', 'playersCount', 'liveMatchesCount'));
    })->name('admin.index');

    // Standard Resource Controllers
    Route::resource('admin/teams', TeamController::class);
    Route::resource('admin/players', PlayerController::class);
    Route::resource('admin/fixtures', FixtureController::class);
    Route::resource('admin/news', AdminNewsController::class, ['as' => 'admin']);

    // Live Match Scoring Engine Routes
    Route::get('admin/scoring', [ScoringController::class, 'index'])->name('scoring.index');
    Route::get('admin/scoring/{fixture}', [ScoringController::class, 'showDashboard'])->name('scoring.dashboard');
    Route::post('admin/scoring/{fixture}/update', [ScoringController::class, 'updateScore'])->name('scoring.update');
    Route::post('/admin/scoring/{id}/update', [ScoringController::class, 'updateScore'])->name('scoring.update2');
    Route::post('/admin/scoring/{id}/toss', [ScoringController::class, 'saveToss'])->name('scoring.toss');
});