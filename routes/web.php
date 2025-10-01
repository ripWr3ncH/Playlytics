<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Api\LiveScoreController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// League routes
Route::get('/leagues', [LeagueController::class, 'index'])->name('leagues.index');
Route::get('/leagues/{slug}', [LeagueController::class, 'show'])->name('leagues.show');
Route::get('/leagues/{slug}/standings', [LeagueController::class, 'standings'])->name('leagues.standings');

// Match routes
Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
Route::get('/matches/live', [MatchController::class, 'live'])->name('matches.live');
Route::get('/matches/{id}', [MatchController::class, 'show'])->name('matches.show');

// Team routes
Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::get('/teams/{slug}', [TeamController::class, 'show'])->name('teams.show');

// Player routes
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
Route::get('/players/{slug}', [PlayerController::class, 'show'])->name('players.show');

// API routes for live data
Route::prefix('api')->group(function () {
    Route::get('/live-scores', [LiveScoreController::class, 'index'])->name('api.live-scores');
    Route::post('/live-scores/update', [LiveScoreController::class, 'update'])->name('api.live-scores.update');
    Route::get('/match-events/{id}', [MatchController::class, 'events'])->name('api.match-events');
});

// Admin routes
Route::prefix('admin')->group(function () {
    // Auth routes (not protected)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
        
        // League management
        Route::get('/leagues', [AdminController::class, 'leagues'])->name('admin.leagues');
        Route::get('/leagues/create', [AdminController::class, 'createLeague'])->name('admin.leagues.create');
        Route::post('/leagues', [AdminController::class, 'storeLeague'])->name('admin.leagues.store');
        Route::get('/leagues/{league}/edit', [AdminController::class, 'editLeague'])->name('admin.leagues.edit');
        Route::put('/leagues/{league}', [AdminController::class, 'updateLeague'])->name('admin.leagues.update');
        Route::delete('/leagues/{league}', [AdminController::class, 'deleteLeague'])->name('admin.leagues.delete');
        
        // Team management
        Route::get('/teams', [AdminController::class, 'teams'])->name('admin.teams');
        Route::get('/teams/create', [AdminController::class, 'createTeam'])->name('admin.teams.create');
        Route::post('/teams', [AdminController::class, 'storeTeam'])->name('admin.teams.store');
        Route::get('/teams/{team}/edit', [AdminController::class, 'editTeam'])->name('admin.teams.edit');
        Route::put('/teams/{team}', [AdminController::class, 'updateTeam'])->name('admin.teams.update');
        Route::delete('/teams/{team}', [AdminController::class, 'deleteTeam'])->name('admin.teams.delete');
        
        // Player management
        Route::get('/players', [AdminController::class, 'players'])->name('admin.players');
        Route::get('/players/create', [AdminController::class, 'createPlayer'])->name('admin.players.create');
        Route::post('/players', [AdminController::class, 'storePlayer'])->name('admin.players.store');
        Route::get('/players/{player}/edit', [AdminController::class, 'editPlayer'])->name('admin.players.edit');
        Route::put('/players/{player}', [AdminController::class, 'updatePlayer'])->name('admin.players.update');
        Route::delete('/players/{player}', [AdminController::class, 'deletePlayer'])->name('admin.players.delete');
        
        // Match management
        Route::get('/matches', [AdminController::class, 'matches'])->name('admin.matches');
        Route::get('/matches/create', [AdminController::class, 'createMatch'])->name('admin.matches.create');
        Route::post('/matches', [AdminController::class, 'storeMatch'])->name('admin.matches.store');
        Route::get('/matches/{match}/edit', [AdminController::class, 'editMatch'])->name('admin.matches.edit');
        Route::put('/matches/{match}', [AdminController::class, 'updateMatch'])->name('admin.matches.update');
        Route::delete('/matches/{match}', [AdminController::class, 'deleteMatch'])->name('admin.matches.delete');
        
        // SQL Query Logs (for academic demonstration)
        Route::get('/sql-logs', [AdminController::class, 'sqlLogs'])->name('admin.sql.logs');
    });
});
