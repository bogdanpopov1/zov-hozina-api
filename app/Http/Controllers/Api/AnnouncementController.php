<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function getUrgent(Request $request)
    {
        // 1. Получаем 4 последних объявления из базы данных
        $announcements = Announcement::latest('created_at')->take(4)->get();

        // 2. Трансформируем данные в формат, который ждет фронтенд
        $formattedAnnouncements = $announcements->map(function ($ad) {
            return [
                "id" => $ad->announcement_id,
                "location_city" => "Казань", // Пока заглушка
                "status_text" => "В поисках", // Пока заглушка
                "pet_breed" => $ad->pet_breed,
                "location_address" => $ad->location_address,
                "last_updated" => $ad->updated_at->format('d.m.Y'),
                "image_url" => "/images/mock/dog1.jpg" // Пока оставляем моковые картинки
            ];
        });

        return response()->json($formattedAnnouncements);
    }
}
