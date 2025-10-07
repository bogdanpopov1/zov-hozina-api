<?php

use App\Http\Controllers\Api\TelegramAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\SuccessStoryController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- Роуты, доступные всем ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/announcements/urgent', [AnnouncementController::class, 'getUrgent']);
Route::get('/stories/happy', [SuccessStoryController::class, 'getStories']);
Route::post('/auth/telegram/callback', [TelegramAuthController::class, 'handleCallback']);


// --- Роуты, доступные только авторизованным пользователям ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Получить информацию о текущем пользователе
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Выйти из системы
    Route::post('/logout', [AuthController::class, 'logout']);

    // Здесь в будущем будут другие роуты для авторизованных
    // например, Route::post('/announcements', [AnnouncementController::class, 'store']);
});