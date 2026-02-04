<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SearchLogController extends Controller
{
    public function index(Announcement $announcement)
    {
        $logs = $announcement->searchLogs()->with(['user', 'photos'])->get();
        return response()->json($logs);
    }

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
            'photos' => ['nullable', 'array', 'max:3'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        $searchLog = null;

        DB::transaction(function () use ($request, $announcement, $user, &$searchLog) {
            $logData = [
                'user_id' => $user->user_id,
                'comment' => $request->input('comment'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ];

            $searchLog = $announcement->searchLogs()->create($logData);

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

        return response()->json($searchLog->load(['user', 'photos']), 201);
    }

    public function update(Request $request, SearchLog $searchLog)
    {
        if (Auth::id() !== $searchLog->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $searchLog->update($validatedData);

        return response()->json($searchLog->load(['user', 'photos']));
    }

    public function destroy(SearchLog $searchLog)
    {
        if (Auth::id() !== $searchLog->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        foreach ($searchLog->photos as $photo) {
            Storage::disk('public')->delete($photo->getAttributes()['path']);
        }

        Storage::disk('public')->deleteDirectory('search_logs/' . $searchLog->log_id);

        $searchLog->delete();

        return response()->json(null, 204);
    }
}