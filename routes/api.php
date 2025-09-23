<?php

use App\Http\Controllers\Api\SuccessStoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnnouncementController; // <-- Убедитесь, что эта строка есть!

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/announcements/urgent', [AnnouncementController::class, 'getUrgent']);
Route::get('/stories/happy', [SuccessStoryController::class, 'getStories']);
