<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    /**
     * Получить все объявления с пагинацией и фильтрацией.
     */
    public function index(Request $request)
    {
        $query = Announcement::with(['user', 'photos']);

        // Фильтр по статусу (только актуальные)
        if ($request->query('actual', 'true') === 'true') {
            $query->where('status', 'active');
        }

        // Полнотекстовый поиск
        $query->when($request->query('search'), function ($q, $searchTerm) {
            return $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('pet_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pet_breed', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('location_address', 'LIKE', '%' . $searchTerm . '%');
            });
        });

        // Фильтр по типу объявления (lost/found)
        $query->when($request->query('announcement_type'), function ($q, $type) {
            if ($type === 'lost' || $type === 'found') {
                return $q->where('announcement_type', $type);
            }
        });

        // Фильтр по виду животного (по slug категории)
        $query->when($request->query('pet_type_slug'), function ($q, $petTypeSlug) {
            $category = \App\Models\Category::where('slug', $petTypeSlug)->first();
            if ($category) {
                return $q->where('pet_type', 'LIKE', '%' . $category->name . '%');
            }
            return $q;
        });


        // Фильтр по населенному пункту
        $query->when($request->query('location'), function ($q, $location) {
            return $q->where('location_address', 'LIKE', '%' . $location . '%');
        });

        // Фильтр по времени публикации
        $query->when($request->query('time_period'), function ($q, $time) {
            if ($time === 'today') {
                return $q->whereDate('created_at', today());
            }
            if ($time === 'week') {
                return $q->where('created_at', '>=', now()->subWeek());
            }
            if ($time === 'month') {
                return $q->where('created_at', '>=', now()->subMonth());
            }
            if ($time === 'year') {
                return $q->where('created_at', '>=', now()->subYear());
            }
        });

        // Фильтр по кастомному периоду
        $query->when($request->query('date_from'), function ($q, $dateFrom) {
            return $q->whereDate('created_at', '>=', $dateFrom);
        });
        $query->when($request->query('date_to'), function ($q, $dateTo) {
            return $q->whereDate('created_at', '<=', $dateTo);
        });

        // Логика сортировки
        $sortBy = $request->query('sort_by', 'default');
        switch ($sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // 'default' case
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
        }

        $announcements = $query->paginate(50);

        return response()->json($announcements);
    }

    /**
     * Получить одно объявление-подсказку для живого поиска.
     */
    public function searchSuggestion(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2',
        ]);

        $searchTerm = $request->query('search');

        $announcement = Announcement::with('photos')
            ->where('status', 'active')
            ->where(function ($query) use ($searchTerm) {
                $query->where('pet_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pet_breed', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('location_address', 'LIKE', '%' . $searchTerm . '%');
            })
            ->latest() // Сначала самые новые
            ->first(); // Берем только одно

        return response()->json($announcement ? [$announcement] : []);
    }


    /**
     * Получить 4 последних "срочных" объявления для главной страницы.
     */
    public function getUrgent()
    {
        $announcements = Announcement::with(['user', 'photos'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->latest('created_at')
            ->take(4)
            ->get();

        return response()->json($announcements);
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        $announcement->load(['user', 'photos']);
        return response()->json($announcement);
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
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validatedData['user_id'] = Auth::id();

        $announcement = Announcement::create($validatedData);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                $path = $file->store('announcements/' . $announcement->announcement_id, 'public');

                $announcement->photos()->create([
                    'path' => $path,
                    'is_primary' => $index === 0,
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => basename($path),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return response()->json($announcement->load('photos'), 201);
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        if (Auth::id() !== $announcement->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'announcement_type' => 'sometimes|required|in:lost,found',
            'pet_type' => 'sometimes|required|string|max:255',
            'pet_name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string|max:2000',
            'location_address' => 'sometimes|required|string|max:255',
            'latitude' => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180',
            'gender' => 'sometimes|required|in:male,female,unknown',
            'color' => 'sometimes|required|string|max:255',
            'age' => 'sometimes|nullable|integer|min:0',
            'pet_breed' => 'sometimes|nullable|string|max:255',
            'photos' => 'sometimes|nullable|array|max:5',
            'photos.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos_to_delete' => 'sometimes|nullable|array',
            'photos_to_delete.*' => 'integer|exists:photos,photo_id',
            'primary_photo_id' => 'sometimes|nullable|integer|exists:photos,photo_id',
        ]);

        $announcement->update($validatedData);

        // 1. Удаление отмеченных фотографий
        if ($request->input('photos_to_delete')) {
            $photosToDeleteIds = $request->input('photos_to_delete');
            $photosToDelete = Photo::whereIn('photo_id', $photosToDeleteIds)
                ->where('announcement_id', $announcement->announcement_id)
                ->get();
            foreach ($photosToDelete as $photo) {
                Storage::disk('public')->delete($photo->getAttributes()['path']);
                $photo->delete();
            }
        }

        // 2. Загрузка новых фотографий
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('announcements/' . $announcement->announcement_id, 'public');
                $announcement->photos()->create([
                    'path' => $path,
                    'is_primary' => false,
                    'original_name' => $file->getClientOriginalName(),
                    'filename' => basename($path),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // 3. Установка главной фотографии
        $primaryId = $request->input('primary_photo_id');
        if ($primaryId) {
            DB::transaction(function () use ($announcement, $primaryId) {
                $announcement->photos()->update(['is_primary' => false]);
                Photo::where('photo_id', $primaryId)
                    ->where('announcement_id', $announcement->announcement_id)
                    ->update(['is_primary' => true]);
            });
        }

        return response()->json($announcement->load('photos'));
    }

    /**
     * Update only the status of the announcement.
     */
    public function updateStatus(Request $request, Announcement $announcement)
    {
        if (Auth::id() !== $announcement->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'status' => 'required|in:active,archived',
        ]);

        $announcement->update($validatedData);

        return response()->json($announcement->load('photos'));
    }


    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if (Auth::id() !== $announcement->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        foreach ($announcement->photos as $photo) {
            Storage::disk('public')->delete($photo->getAttributes()['path']);
        }
        Storage::disk('public')->deleteDirectory('announcements/' . $announcement->announcement_id);

        $announcement->photos()->delete();
        $announcement->delete();

        return response()->json(null, 204);
    }
}