<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('music.home');
});

// Autentikasi (Login, Register, Logout, Google OAuth)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Google OAuth Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get'); // Fallback direct link
});

// Music App Routes
Route::get('/music', [MusicPageController::class, 'home'])
    ->name('music.home');

Route::get('/search', [MusicPageController::class, 'search'])
    ->name('music.search');

Route::get('/genres', [MusicPageController::class, 'genres'])
    ->name('music.genres');

Route::get('/rooms', [MusicPageController::class, 'rooms'])
    ->name('music.rooms');

Route::get('/rooms/{room}', [MusicPageController::class, 'roomDetail'])
    ->name('music.room.detail');

Route::get('/library', [MusicPageController::class, 'library'])
    ->name('music.library');

Route::get('/music/stream/{youtubeId}', [MusicPageController::class, 'stream'])
    ->name('music.stream');
