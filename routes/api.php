<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\SuccessStoryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\VolunteerSubscriptionController;
use App\Http\Controllers\Api\SearchLogController;
use App\Http\Controllers\Api\NotificationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/stories/happy', [SuccessStoryController::class, 'getStories']);
Route::get('/announcements/urgent', [AnnouncementController::class, 'getUrgent']);
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::get('/announcements/search-suggestion', [AnnouncementController::class, 'searchSuggestion']);
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show']);

Route::get('/announcements/{announcement}/logs', [SearchLogController::class, 'index']);

Route::get('/categories', [DataController::class, 'getCategories']);
Route::get('/breeds/search', [DataController::class, 'searchBreeds']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user', [ProfileController::class, 'update']);
    Route::put('/user/volunteer-status', [ProfileController::class, 'updateVolunteerStatus']);
    Route::get('/user/announcements', [ProfileController::class, 'getAnnouncements']);

    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update']);
    Route::patch('/announcements/{announcement}/status', [AnnouncementController::class, 'updateStatus']);
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy']);

    Route::get('/subscriptions', [VolunteerSubscriptionController::class, 'index']);
    Route::post('/subscriptions', [VolunteerSubscriptionController::class, 'store']);
    Route::delete('/subscriptions/{subscription}', [VolunteerSubscriptionController::class, 'destroy']);

    Route::post('/announcements/{announcement}/logs', [SearchLogController::class, 'store']);
    Route::put('/logs/{searchLog}', [SearchLogController::class, 'update']);
    Route::delete('/logs/{searchLog}', [SearchLogController::class, 'destroy']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/log/{log}/read', [NotificationController::class, 'markSingleAsRead']);
});