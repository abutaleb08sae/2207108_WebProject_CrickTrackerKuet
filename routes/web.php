<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Public\CricketNewsController; // Global News API Controller
use App\Models\Team;
use App\Models\Player;
use App\Models\Fixture;

/*
|--------------------------------------------------------------------------
| Public & Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['custom.auth'])->group(function () {
    // Public Main Portals
    Route::get('/', [PublicHomeController::class, 'index'])->name('public.home');
    
    // Core Route (Fixes the public navbar layout file crash)
    Route::get('/international-matches', [PublicHomeController::class, 'internationalMatches'])->name('public.international');
    
    // Alias Route (Fixes the international.blade.php sub-nav button crash)
    Route::get('/international-matches/live', [PublicHomeController::class, 'internationalMatches'])->name('public.matches.international');
    
    // Asynchronous JavaScript AJAX Endpoint for real-time tracking
    Route::get('/api/international-matches-data', [PublicHomeController::class, 'getInternationalMatchesData'])->name('public.international.data');
    
    // Dedicated International News Skeleton View Route
    Route::get('/international/news', [CricketNewsController::class, 'index'])->name('public.cricket.news');

    // Asynchronous JavaScript AJAX Endpoint for Cricket News API Engine
    Route::get('/api/international-news-data', [CricketNewsController::class, 'getNewsData'])->name('public.cricket.news.data');
    
    // Core Local Match Route - Dynamic Scoreboard view
    Route::get('/matches/{id}', [PublicHomeController::class, 'matchDetails'])->name('public.matches.show');
    
    // Dynamic Player Profile and Statistics Tracking Route
    Route::get('/players/{id}', [PublicHomeController::class, 'playerProfile'])->name('public.players.show');
    
    // Dynamic Team Profile, Squad Roster, and History Tracking Route
    Route::get('/teams/{id}', [PublicHomeController::class, 'teamProfile'])->name('public.teams.show');
    
    // Public League Tracking Tables & Media Archives
    Route::get('/standings', [PublicHomeController::class, 'standings'])->name('public.standings');
    Route::get('/fixtures', [PublicHomeController::class, 'fixtures'])->name('public.fixtures');
    Route::get('/results', [PublicHomeController::class, 'results'])->name('public.results');
    Route::get('/news', [PublicHomeController::class, 'newsArchive'])->name('public.news.index');
    
    // External API Match Route Tracker
    Route::get('/external-match/{id}', [PublicHomeController::class, 'showMatchDetails'])->name('public.match.details');

    // Auth Handlers
    Route::get('/signin', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/signin', [AuthController::class, 'login']);
    Route::get('/signup', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/signout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Strict Admin Dashboard Routes (Standard Namespacing)
|--------------------------------------------------------------------------
*/
Route::middleware(['custom.auth', 'admin.strict'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Landing Portal Metrics Aggregator
    Route::get('/', function () {
        $teamsCount = Team::count();
        $playersCount = Player::count();
        $liveMatchesCount = Fixture::where('status', 'LIVE')->count();

        return view('admin.index', compact('teamsCount', 'playersCount', 'liveMatchesCount'));
    })->name('index');

    // Standard Core Resource Controllers (Automatically named admin.players.*, admin.teams.*, etc.)
    Route::resource('teams', TeamController::class);
    Route::resource('players', PlayerController::class); 
    Route::resource('fixtures', FixtureController::class);
    Route::resource('games', GameController::class);
    Route::resource('news', AdminNewsController::class);

    // Live Match Scoring Engine Routes
    Route::get('scoring', [ScoringController::class, 'index'])->name('scoring.index');
    Route::get('scoring/{fixture}', [ScoringController::class, 'showDashboard'])->name('scoring.dashboard');
    Route::post('scoring/{fixture}/toss', [ScoringController::class, 'saveToss'])->name('scoring.toss');
    Route::post('scoring/{fixture}/update', [ScoringController::class, 'updateScore'])->name('scoring.update');
    
    // Dynamic Application Updates: Management for Active On-Field Batsmen and Bowlers
    Route::post('scoring/{fixture}/active-players', [ScoringController::class, 'updateActivePlayers'])->name('scoring.active_players');
});

/*
|--------------------------------------------------------------------------
| Legacy & Unprefixed View Blade Compatibility Layer (Aliases)
|--------------------------------------------------------------------------
| These routes provide fallback alias names to prevent 500 crashes in older
| blade views that request names without the 'admin.' prefix wrapper.
*/
Route::middleware(['custom.auth', 'admin.strict'])->prefix('admin')->group(function () {
    // Players Aliases
    Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
    Route::get('/players/create', [PlayerController::class, 'create'])->name('players.create');
    Route::post('/players', [PlayerController::class, 'store'])->name('players.store');
    Route::get('/players/{player}', [PlayerController::class, 'show'])->name('players.show');
    Route::get('/players/{player}/edit', [PlayerController::class, 'edit'])->name('players.edit');
    Route::put('/players/{player}', [PlayerController::class, 'update'])->name('players.update');
    Route::delete('/players/{player}', [PlayerController::class, 'destroy'])->name('players.destroy');

    // Teams Aliases
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
    Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');

    // Fixtures Aliases
    Route::get('/fixtures', [FixtureController::class, 'index'])->name('fixtures.index');
    Route::get('/fixtures/create', [FixtureController::class, 'create'])->name('fixtures.create');
    Route::post('/fixtures', [FixtureController::class, 'store'])->name('fixtures.store');
    Route::get('/fixtures/{fixture}', [FixtureController::class, 'show'])->name('fixtures.show');
    Route::get('/fixtures/{fixture}/edit', [FixtureController::class, 'edit'])->name('fixtures.edit');
    Route::put('/fixtures/{fixture}', [FixtureController::class, 'update'])->name('fixtures.update');
    Route::delete('/fixtures/{fixture}', [FixtureController::class, 'destroy'])->name('fixtures.destroy');
    
    // Scoring Engine Aliases (UPDATED: Added missing active-players, toss, and score updates)
    Route::get('/scoring', [ScoringController::class, 'index'])->name('scoring.index');
    Route::get('/scoring/{fixture}', [ScoringController::class, 'showDashboard'])->name('scoring.dashboard');
    Route::post('/scoring/{fixture}/toss', [ScoringController::class, 'saveToss'])->name('scoring.toss');
    Route::post('/scoring/{fixture}/update', [ScoringController::class, 'updateScore'])->name('scoring.update');
    Route::post('/scoring/{fixture}/active-players', [ScoringController::class, 'updateActivePlayers'])->name('scoring.active_players');
    
    // News Aliases 
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news-fallback-explicit', [AdminNewsController::class, 'index'])->name('admin.news.index');
});