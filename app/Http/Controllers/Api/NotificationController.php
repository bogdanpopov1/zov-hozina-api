<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SearchLog;

class NotificationController extends Controller
{
    /**
     * Получить непрочитанные уведомления для текущего пользователя.
     * Уведомлением считается новая запись в журнале поиска по его объявлению.
     */
    public function index()
    {
        $user = Auth::user();

        $announcementIds = $user->announcements()->pluck('announcement_id');

        $notifications = SearchLog::whereIn('announcement_id', $announcementIds)
            ->where('is_read', false)
            ->where('user_id', '!=', $user->user_id)
            ->with(['user:user_id,name', 'announcement:announcement_id,pet_name'])
            ->latest()
            ->get();

        return response()->json($notifications);
    }

    /**
     * Пометить одно конкретное уведомление как прочитанное.
     */
    public function markSingleAsRead(SearchLog $log)
    {
        // Проверка: убеждаемся, что пользователь является владельцем объявления,
// к которому относится это уведомление (запись в логе).
        if (Auth::id() !== $log->announcement->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $log->update(['is_read' => true]);

        return response()->json(['message' => 'Notification marked as read.']);
    }
}