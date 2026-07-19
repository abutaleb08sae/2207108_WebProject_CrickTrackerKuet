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
| Strict Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['custom.auth', 'admin.strict'])->group(function () {
    
    // Admin Landing Portal Metrics Aggregator
    Route::get('/admin', function () {
        $teamsCount = Team::count();
        $playersCount = Player::count();
        $liveMatchesCount = Fixture::where('status', 'LIVE')->count();

        return view('admin.index', compact('teamsCount', 'playersCount', 'liveMatchesCount'));
    })->name('admin.index');

    // Standard Core Resource Controllers
    Route::resource('admin/teams', TeamController::class);
    Route::resource('admin/players', PlayerController::class);
    Route::resource('admin/fixtures', FixtureController::class);
    Route::resource('admin/games', GameController::class);
    Route::resource('admin/news', AdminNewsController::class, ['as' => 'admin']);

    // Live Match Scoring Engine Routes
    Route::get('admin/scoring', [ScoringController::class, 'index'])->name('scoring.index');
    Route::get('admin/scoring/{fixture}', [ScoringController::class, 'showDashboard'])->name('scoring.dashboard');
    Route::post('admin/scoring/{fixture}/toss', [ScoringController::class, 'saveToss'])->name('scoring.toss');
    Route::post('admin/scoring/{fixture}/update', [ScoringController::class, 'updateScore'])->name('scoring.update');
    
    // Dynamic Application Updates: Management for Active On-Field Batsmen and Bowlers
    Route::post('admin/scoring/{fixture}/active-players', [ScoringController::class, 'updateActivePlayers'])->name('scoring.active_players');
});