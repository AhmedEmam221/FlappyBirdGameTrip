<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

// Public routes
Route::get('/', [GameController::class, 'index'])->name('home');
Route::get('/play', [GameController::class, 'play'])->name('play');
Route::get('/game-session/create', [GameController::class, 'createSession'])->name('game.session.create');
Route::post('/game-session', [GameController::class, 'storeSession'])->name('game.session.store');
Route::post('/score/submit', [SubmissionController::class, 'store'])->name('score.submit');
Route::get('/leaderboard', [GameController::class, 'leaderboard'])->name('leaderboard');
Route::get('/rules', [GameController::class, 'rules'])->name('rules');
Route::get('/privacy', [GameController::class, 'privacy'])->name('privacy');
Route::get('/instagram/visit', [GameController::class, 'instagramVisit'])->name('instagram.visit');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes (grouped with middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/entries', [AdminController::class, 'entries'])->name('admin.entries');
    Route::get('/admin/players', [AdminController::class, 'players'])->name('admin.players');
    Route::get('/admin/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
    Route::get('/admin/settings', [SettingController::class, 'edit'])->name('admin.settings');
    Route::post('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::post('/admin/favicon', [SettingController::class, 'faviconUpload'])->name('admin.favicon.upload');
    Route::post('/admin/competition/finalize', [AdminController::class, 'finalize'])->name('admin.competition.finalize');
    Route::post('/admin/entries/{entry}/approve', [AdminController::class, 'approve'])->name('admin.entries.approve');
    Route::post('/admin/entries/{entry}/reject', [AdminController::class, 'reject'])->name('admin.entries.reject');
    Route::post('/admin/entries/{entry}/flag', [AdminController::class, 'flag'])->name('admin.entries.flag');
    Route::get('/admin/exports/entries.csv', [AdminController::class, 'exportCsv'])->name('admin.exports.csv');
});

// Routes