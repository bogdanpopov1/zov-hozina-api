<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\SuccessStoryController;
use App\Http\Controllers\Api\TelegramAuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\DataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==================================================
// 1. ПУБЛИЧНЫЕ МАРШРУТЫ
// ==================================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/stories/happy', [SuccessStoryController::class, 'getStories']);
Route::get('/announcements/urgent', [AnnouncementController::class, 'getUrgent']);
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show']);
Route::get('/categories', [DataController::class, 'getCategories']);
Route::get('/breeds/search', [DataController::class, 'searchBreeds']);

// ==================================================
// 2. ЗАЩИЩЕННЫЕ МАРШРУТЫ (требуют Bearer токен)
// ==================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- Управление сессией ---
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Пользователь ---
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user', [ProfileController::class, 'update']);

    // --- Объявления ---
    // Этот маршрут теперь не конфликтует и будет работать корректно
    Route::post('/announcements', [AnnouncementController::class, 'store']);

});