<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Announcement $announcement)
    {
        $logs = $announcement->searchLogs()->with(['user', 'photos'])->get();
        return response()->json($logs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Announcement $announcement)
    {
        $user = Auth::user();

        if (!$user->is_volunteer) {
            return response()->json(['error' => 'Only volunteers can add search log entries.'], 403);
        }

        $validatedData = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'photos' => ['nullable', 'array', 'max:3'], // Ограничим 3 фото на одну запись
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        $searchLog = null;

        // Используем транзакцию, чтобы гарантировать целостность данных:
        // либо создается и лог, и все фото, либо ничего.
        DB::transaction(function () use ($request, $announcement, $user, &$searchLog) {
            $logData = [
                'user_id' => $user->user_id,
                'comment' => $request->input('comment'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ];

            // Создаем запись в логе, привязанную к объявлению
            $searchLog = $announcement->searchLogs()->create($logData);

            // Если есть фото, обрабатываем и сохраняем их
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $file) {
                    $path = $file->store('search_logs/' . $searchLog->log_id, 'public');

                    $searchLog->photos()->create([
                        'path' => $path,
                        'filename' => basename($path),
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }
        });

        // Возвращаем созданную запись с подгруженными связями
        return response()->json($searchLog->load(['user', 'photos']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SearchLog $searchLog)
    {
        // Не используется
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchLog $searchLog)
    {
        // Не используется
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchLog $searchLog)
    {
        // Не используется
    }
}