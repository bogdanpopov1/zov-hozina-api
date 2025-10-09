<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\SuccessStoryController;
use App\Http\Controllers\Api\TelegramAuthController;
use App\Models\Announcement; // Импортируем модель для использования в маршруте

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Этот файл определяет все маршруты для вашего API.
| Они разделены на две группы: публичные и защищенные.
|
*/

// ==================================================
// 1. ПУБЛИЧНЫЕ МАРШРУТЫ (доступны всем без токена)
// ==================================================

// --- Аутентификация ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/telegram/callback', [TelegramAuthController::class, 'handleCallback']);

// --- Получение данных для главной страницы ---
Route::get('/stories/happy', [SuccessStoryController::class, 'getStories']);

// Используем этот маршрут для получения 4 срочных объявлений.
// Дублирующийся маршрут, который вел на контроллер, был удален.
Route::get('/announcements/urgent', function () {
    return Announcement::with(['user', 'category', 'breed', 'photos'])
        ->where('status', 'active')
        ->where('is_featured', true)
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();
});

// --- Получение всех объявлений (для будущей страницы с картой/списком) ---
Route::get('/announcements', function () {
    return Announcement::with(['user', 'category', 'breed', 'photos'])
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->paginate(10); // Пагинация важна для производительности
});


// ==================================================
// 2. ЗАЩИЩЕННЫЕ МАРШРУТЫ (требуют Bearer токен)
// ==================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- Управление сессией ---
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Получение данных о текущем пользователе ---
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /*
    |--------------------------------------------------------------------------
    | Маршруты для будущих функций (требуют авторизации)
    |--------------------------------------------------------------------------
    |
    | Сюда вы будете добавлять маршруты для создания, редактирования
    | и удаления объявлений, когда пользователь вошел в систему.
    |
    | Пример:
    | Route::post('/announcements', [AnnouncementController::class, 'store']);
    |
    */
});