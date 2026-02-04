<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Photo;
use App\Models\VolunteerSubscription;
use App\Notifications\NewAnnouncementInZoneNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with(['user', 'photos']);

        if ($request->query('actual', 'true') === 'true') {
            $query->where('status', 'active');
        }

        $query->when($request->query('search'), function ($q, $searchTerm) {
            return $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('pet_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pet_breed', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('location_address', 'LIKE', '%' . $searchTerm . '%');
            });
        });

        $query->when($request->query('announcement_type'), function ($q, $type) {
            if ($type === 'lost' || $type === 'found') {
                return $q->where('announcement_type', $type);
            }
        });

        $query->when($request->query('pet_type_slug'), function ($q, $petTypeSlug) {
            $category = \App\Models\Category::where('slug', $petTypeSlug)->first();
            if ($category) {
                return $q->where('pet_type', 'LIKE', '%' . $category->name . '%');
            }
            return $q;
        });

        $query->when($request->query('location'), function ($q, $location) {
            return $q->where('location_address', 'LIKE', '%' . $location . '%');
        });

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

        $query->when($request->query('date_from'), function ($q, $dateFrom) {
            return $q->whereDate('created_at', '>=', $dateFrom);
        });
        $query->when($request->query('date_to'), function ($q, $dateTo) {
            return $q->whereDate('created_at', '<=', $dateTo);
        });

        $sortBy = $request->query('sort_by', 'default');
        switch ($sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $fiveDaysAgo = now()->subDays(5);
                $query->orderByRaw("CASE WHEN status = 'archived' THEN 2 WHEN created_at >= ? THEN 0 ELSE 1 END ASC", [$fiveDaysAgo])
                    ->orderBy('created_at', 'desc');
                break;
        }

        $announcements = $query->paginate(50);

        return response()->json($announcements);
    }

    public function searchSuggestion(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);

        $searchTerm = $request->query('search');

        $announcement = Announcement::with('photos')
            ->where('status', 'active')
            ->where(function ($query) use ($searchTerm) {
                $query->where('pet_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('pet_breed', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('location_address', 'LIKE', '%' . $searchTerm . '%');
            })
            ->latest()
            ->first();

        return response()->json($announcement ? [$announcement] : []);
    }

    public function getUrgent()
    {
        $announcements = Announcement::with(['user', 'photos'])
            ->where('status', 'active')
            ->latest('created_at')
            ->take(4)
            ->get();

        return response()->json($announcements);
    }

    public function show(Announcement $announcement)
    {
        $announcement->load(['user', 'photos']);
        return response()->json($announcement);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'announcement_type' => 'required|in:lost,found',
            'pet_type' => 'required|string|max:255',
            'pet_name' => 'nullable|string|max:255',
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

        $createData = Arr::except($validatedData, ['photos']);

        // ИСПРАВЛЕНИЕ ОШИБКИ 1048:
        // Если порода не указана (null), устанавливаем дефолтное значение,
        // так как поле в БД не принимает NULL.
        if (empty($createData['pet_breed'])) {
            $createData['pet_breed'] = 'Не указана';
        }

        $announcement = Announcement::create($createData);

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

        // --- START: Volunteer Notification Logic ---
        try {
            $lat = (float) $announcement->latitude;
            $lon = (float) $announcement->longitude;

            // Используем формулу гаверсинуса с защитой от ошибок float (least/greatest)
            $matchingSubscriptions = VolunteerSubscription::select('*')
                ->selectRaw("
                    ( 6371 * acos( 
                        least(1.0, greatest(-1.0, 
                            cos( radians(?) ) *
                            cos( radians( latitude ) ) *
                            cos( radians( longitude ) - radians(?) ) +
                            sin( radians(?) ) *
                            sin( radians( latitude ) )
                        ))
                    ) ) AS distance", [$lat, $lon, $lat])
                ->havingRaw("distance <= radius")
                ->with('user')
                ->get();

            Log::info("Notification System: Found " . $matchingSubscriptions->count() . " matching subscriptions for announcement " . $announcement->announcement_id);

            $usersToNotify = $matchingSubscriptions->pluck('user')
                ->unique('user_id')
                ->reject(function ($user) use ($announcement) {
                    return $user->user_id == $announcement->user_id;
                });

            Log::info("Notification System: Sending notifications to " . $usersToNotify->count() . " users.");

            if ($usersToNotify->isNotEmpty()) {
                Notification::send($usersToNotify, new NewAnnouncementInZoneNotification($announcement));
            }

        } catch (\Exception $e) {
            Log::error('Failed to send volunteer notifications: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
        }
        // --- END: Volunteer Notification Logic ---

        return response()->json($announcement->load('photos'), 201);
    }

    public function update(Request $request, Announcement $announcement)
    {
        if (Auth::id() !== $announcement->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'announcement_type' => 'sometimes|required|in:lost,found',
            'pet_type' => 'sometimes|required|string|max:255',
            'pet_name' => 'sometimes|nullable|string|max:255',
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

        $dataToUpdate = Arr::except($validatedData, ['photos', 'photos_to_delete', 'primary_photo_id']);

        // ИСПРАВЛЕНИЕ ОШИБКИ 1048 для обновления:
        if (array_key_exists('pet_breed', $dataToUpdate) && empty($dataToUpdate['pet_breed'])) {
            $dataToUpdate['pet_breed'] = 'Не указана';
        }

        $announcement->update($dataToUpdate);

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