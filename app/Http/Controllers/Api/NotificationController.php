<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SearchLog;

class NotificationController extends Controller
{
    /**
     * Получить все уведомления (SearchLogs + DatabaseNotifications).
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Получаем уведомления о записях в журнале (для владельцев объявлений)
        $announcementIds = $user->announcements()->pluck('announcement_id');

        $logNotifications = SearchLog::whereIn('announcement_id', $announcementIds)
            ->where('is_read', false)
            ->where('user_id', '!=', $user->user_id)
            ->with(['user:user_id,name', 'announcement:announcement_id,pet_name'])
            ->latest()
            ->get()
            ->map(function ($log) {
                return [
                    'id' => 'log_' . $log->log_id, // Префикс для уникальности ключа на фронте
                    'real_id' => $log->log_id,
                    'type' => 'log',
                    'is_read' => $log->is_read,
                    'created_at' => $log->created_at,
                    'data' => [
                        'user_name' => $log->user->name,
                        'pet_name' => $log->announcement->pet_name,
                        'announcement_id' => $log->announcement_id,
                    ]
                ];
            });

        // 2. Получаем системные уведомления (для волонтеров)
        $systemNotifications = $user->unreadNotifications->map(function ($notification) {
            return [
                'id' => $notification->id, // UUID
                'real_id' => $notification->id,
                'type' => $notification->data['type'] ?? 'system',
                'is_read' => $notification->read_at !== null,
                'created_at' => $notification->created_at,
                'data' => $notification->data
            ];
        });

        // Объединяем и сортируем по дате (новые сверху)
        $allNotifications = $logNotifications->concat($systemNotifications)->sortByDesc('created_at')->values();

        return response()->json($allNotifications);
    }

    /**
     * Пометить уведомление как прочитанное.
     * Принимает ID, который может быть int (log_id) или UUID (notification_id).
     */
    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();

        // Проверяем, является ли ID числом (значит это SearchLog)
        if (is_numeric($id)) {
            $log = SearchLog::find($id);

            if (!$log) {
                return response()->json(['error' => 'Log not found'], 404);
            }

            if ($user->user_id !== $log->announcement->user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $log->update(['is_read' => true]);
            return response()->json(['message' => 'Log marked as read']);
        }
        // Иначе это UUID (Database Notification)
        else {
            $notification = $user->notifications()->where('id', $id)->first();

            if ($notification) {
                $notification->markAsRead();
                return response()->json(['message' => 'Notification marked as read']);
            }

            return response()->json(['error' => 'Notification not found'], 404);
        }
    }

    /**
     * Legacy метод для совместимости, если где-то остался прямой вызов
     */
    public function markSingleAsRead(SearchLog $log)
    {
        if (Auth::id() !== $log->announcement->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $log->update(['is_read' => true]);

        return response()->json(['message' => 'Notification marked as read.']);
    }
}