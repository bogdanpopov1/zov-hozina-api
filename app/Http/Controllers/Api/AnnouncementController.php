<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

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
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Используем пагинацию для больших списков

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
}