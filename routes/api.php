<?php

use App\Http\Controllers\PlayerStateController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomQueueController;
use App\Http\Controllers\YouTubeMusicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes for easy web consumption
Route::get('/public/music/search', [YouTubeMusicController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::post('/rooms/join', [RoomController::class, 'join']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);
    Route::put('/rooms/{room}', [RoomController::class, 'update']);
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy']);
    Route::post('/rooms/{room}/queue', [RoomQueueController::class, 'store']);
    Route::get('/rooms/{room}/queue', [RoomQueueController::class, 'index']);
    Route::put('/rooms/{room}/queue/reorder', [RoomQueueController::class, 'reorder']);
    Route::put('/rooms/{room}/queue/{queue}/played', [RoomQueueController::class, 'played']);
    Route::delete('/rooms/{room}/queue/{queue}', [RoomQueueController::class, 'destroy']);
    Route::get('/rooms/{room}/player', [PlayerStateController::class, 'show']);
    Route::put('/rooms/{room}/player', [PlayerStateController::class, 'update']);
    Route::get('/music/search', [YouTubeMusicController::class, 'search']);
    Route::get('/music/songs/{youtubeId}', [YouTubeMusicController::class, 'show']);
});
