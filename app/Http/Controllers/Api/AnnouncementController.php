<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

class AnnouncementController extends Controller
{
    /**
     * Получить все активные объявления с пагинацией.
     */
    public function index()
    {
        // Загружаем связанные модели user и photos для эффективности
        $announcements = Announcement::with(['user', 'photos'])
            ->where('status', 'active')
            ->orderBy('is_featured', 'desc') // Сначала срочные
            ->orderBy('created_at', 'desc')
            ->paginate(20); 

        return response()->json($announcements);
    }

    /**
     * Получить 4 последних "срочных" объявления для главной страницы.
     */
    public function getUrgent()
    {
        $announcements = Announcement::with(['user', 'photos'])
            ->where('status', 'active')
            ->where('is_featured', true) // Предполагаем, что "срочные" - это is_featured
            ->latest('created_at') // Более короткая запись для orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return response()->json($announcements);
    }

    /**
     * Сохранить новое объявление в базе данных.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'announcement_type' => 'required|in:lost,found',
            'pet_type' => 'required|string|max:255',
            'pet_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'location_address' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'gender' => 'required|in:male,female,unknown',
            'color' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'pet_breed' => 'nullable|string|max:255',
            // Валидация для фото (пока опционально)
            // 'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Добавляем ID пользователя к данным
        $validatedData['user_id'] = Auth::id();

        // Создаем объявление
        $announcement = Announcement::create($validatedData);

        // TODO: Логика для загрузки и сохранения фотографий

        return response()->json($announcement, 201);
    }
}